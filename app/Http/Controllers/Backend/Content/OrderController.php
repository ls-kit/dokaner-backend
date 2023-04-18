<?php

namespace App\Http\Controllers\Backend\Content;

use App\Http\Controllers\Controller;
use App\Models\Auth\User;
use App\Models\Content\Order;
use App\Models\Content\OrderItem;
use App\Models\Content\OrderItemVariation;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Throwable;
use Yajra\DataTables\Facades\DataTables;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Factory|View
     */
    public function index()
    {
        $status = request('status');
        if ($status == 'incomplete') {
            return view('backend.content.order.incomplete');
        }
        return view('backend.content.order.index');
    }


    public function makeAsPayment($id)
    {
        $order = Order::findOrFail($id);
        $order_id = $id;
        $order_user_id = $order->user_id;
        if ($order) {
            DB::transaction(function () use ($order, $order_id, $order_user_id) {
                $order->update([
                    'status' => 'partial-paid',
                    'order_approved_at' => Carbon::now()
                ]);

                OrderItem::where('order_id', $order_id)
                    ->where('user_id', $order_user_id)
                    ->update([
                        'status' => 'partial-paid',
                    ]);
            });
        }
        $tran = $order->order_number ?? '';
        return redirect()->back()->withFlashSuccess('Incomplete order #' . $tran . ' make as partial paid');
    }

    public function makeAsFullPayment($id)
    {
        $order = Order::findOrFail($id);
        $order_id = $id;
        $order_user_id = $order->user_id;
        if ($order) {
            DB::transaction(function () use ($order, $order_id, $order_user_id) {
                $order->update([
                    'status' => 'full-paid',
                    'dueForProducts' => 0
                ]);

                OrderItem::where('order_id', $order_id)
                    ->where('user_id', $order_user_id)
                    ->update([
                        'status' => 'full-paid',
                        'due_payment' => 0
                    ]);
            });
        }
        $tran = $order->order_number ?? '';
        return redirect()->back()->withFlashSuccess('Incomplete order #' . $tran . ' make as full paid');
    }

    public function getOrderItems(Request $request)
    {
        if ($request->ajax()) {
            $items = OrderItem::where('order_id', $request->orderId)->get();
            return response()->json([
                'items' => $items
            ]);
        }
    }


    public function orderPrint($id)
    {
        dd('development not finished');
        return redirect()->route('admin.order.print', $id);
        // $orderItem = OrderItem::with('order', 'itemVariations')->findOrFail($id);
        // return view('backend.content.order.print', compact('orderItem'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws Throwable
     */
    public function store(Request $request)
    {
        $status = request('status');
        $item_id = request('item_id');
        $orderItem = null;
        $is_array = false;
        if (is_array($item_id)) {
            $is_array = true;
            foreach ($item_id as $item) {
                $orderItem[] = $this->update_order_wallet_status($item, $status, $request);
            }
        } else {
            $is_array = false;
            $orderItem = $this->update_order_wallet_status($item_id, $status, $request);
        }

        $csrf = csrf_token();

        if (!empty($orderItem)) {
            $order_data = [
                'status' => true,
                'csrf' => $csrf,
                'is_array' => $is_array,
                'orderItem' => $orderItem,
            ];
            return \response()->json($order_data);
        }

        return \response()->json(['status' => false, 'csrf' => $csrf]);
    }


    public function update_order_wallet_status($item_id, $status, $request)
    {
        $orderItem = OrderItem::find($item_id);
        $data = [];
        $order_id = $orderItem->order_item_number;
        $amount = '';
        $tracking = '';
        if ($status === 'purchased') {
            $data = $request->only('order_number', 'status');
        } elseif ($status === 'shipped-from-suppliers') {
            $data = $request->only('tracking_number', 'status');
            $tracking = $data['tracking_number'];
        } elseif ($status === 'received-in-china-warehouse') {
            $data = $request->only('status');
        } elseif ($status === 'shipped-from-china-warehouse') {
            $data = $request->only('status');
        } elseif ($status === 'ready-to-deliver') {
            $data = $request->only('actual_weight', 'status');
            $data['shipping_charge'] = $orderItem->shipping_rate * $data['actual_weight'];
            $data['ready_to_deliver_at'] = date('Y-m-d H:i:s' ,time());
        } elseif ($status === 'ready-to-deliver') {
            $data = $request->only('status');
        } elseif ($status === 'BD-customs') {
            $data = $request->only('status');
        } elseif ($status === 'on-transit-to-customer') {
            $data = $request->only('status');
        } elseif ($status === 'delivered') {
            $data = $request->only('status');

            $order = Order::findOrFail($orderItem->order_id);
            $order->update([
                'status' => 'order-completed'
            ]);
        } elseif ($status === 'out-of-stock') {
            $data = $request->only('out_of_stock', 'out_of_stock_type', 'status');
            $amount = $data['out_of_stock'];
        } elseif ($status === 'adjustment') {
            $data = $request->only('adjustment');
            $amount = $data['adjustment'];
        } elseif ($status === 'refunded') {
            $data = $request->only('refunded', 'status');
            $amount = $data['refunded'];
        }

        // manage customer Messages
        $user = $orderItem->user;
        if ($request->input('notify')) {
            generate_customer_notifications($status, $user, $order_id, $amount, $tracking);
        }

        if (!empty($data)) {
            $orderItem->update($data);

            $product_value = (int)$orderItem->product_value;
            $chinaLocalDelivery = (int)$orderItem->chinaLocalDelivery;
            $coupon_contribution = (int)$orderItem->coupon_contribution;
            $first_payment = (int)$orderItem->first_payment;

            $out_of_stock = (int)$orderItem->out_of_stock;
            $adjustment = $orderItem->adjustment;
            $refunded = (int)$orderItem->refunded;
            $shipping_charge = (int)$orderItem->shipping_charge;
            $courier_bill = (int)$orderItem->courier_bill;
            $last_payment = (int)$orderItem->last_payment;
            $missing = (int)$orderItem->missing;

            $due_payment = $orderItem->due_payment;

            $due_payment = $adjustment > 0 ? $due_payment + abs($adjustment) : $due_payment - abs($adjustment);

            $orderItem->update(['due_payment' => $due_payment, 'first_payment' => $first_payment]);
        }

        return $orderItem;
    }

    public function updateShippingRate($id, Request $request)
    {
        if ($request->ajax()) {

            $order_item = OrderItem::where('id', $request->item_id)->first();

            if (!empty($order_item)) {
                $order_item->update([
                    'shipping_rate' => $request->shipping_rate
                ]);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Shipping rate updated succcessfully!'
                ]);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Order Item not found!'
            ]);
        }
    }

    public function updateAdjustment($id, Request $request)
    {
        if ($request->ajax()) {

            $order_item = OrderItem::where('id', $request->item_id)->first();

            if (!empty($order_item)) {
                $order_item->update([
                    'adjustment' => $request->adjustment,
                    'due_payment' => $order_item->due_payment + $request->adjustment
                ]);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Adjustment updated succcessfully!'
                ]);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Order Item not found!'
            ]);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|Response
     */
    public function show($id)
    {
        $order = Order::with('orderItems')->findOrFail($id);

        $render = '';
        $title = 'Wallet details';
        $status = false;
        if ($order) {
            $customer = $order->user->name ?? "Customer";
            $item_no = $order->order_number;
            $status = true;
            $title = "Order details of Mr. {$customer} and Order No #{$item_no}";
            $render = view('backend.content.order.show', compact('order'))->render();
        }

        return \response([
            'status' => $status,
            'title' => $title,
            'render' => $render,
        ]);
    }


    public function walletOrders()
    {
        $customers = User::withCount('orders')->role('user')->orderBy('first_name')->get();

        $findable[''] = ' - Select Customer - ';
        foreach ($customers as $customer) {
            $findable[$customer->id] = $customer->full_name;
        }

        return view('backend.content.order.wallet.index', ['findable' => $findable]);
    }

    public function walletDetails($id)
    {
        $order = OrderItem::with('user', 'order', 'product', 'itemVariations')->find($id);
        $render = '';
        $title = 'Wallet details';
        $status = false;
        if ($order) {
            $customer = $order->user->first_name . ' ' . $order->user->last_name;
            $item_no = $order->order_item_number;
            $status = true;
            $title = "Wallet details of Mr. {$customer} and Item No #{$item_no}";
            $render = view('backend.content.order.wallet.details', compact('order'))->render();
        }

        return \response([
            'status' => $status,
            'title' => $title,
            'render' => $render,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        dd($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $page
     * @return Response
     */
    public function destroy($id)
    {
        $order = Order::withTrashed()->find($id);
        $order_id = $id;
        $order_user_id = $order->user_id ?? null;
        $orderItem = OrderItem::withTrashed()->where('order_id', $order_id)
            ->where('user_id', $order_user_id);
        $orderItemItems = $orderItem->pluck('id')->toArray();
        $OrderItemVariation = OrderItemVariation::withTrashed()->whereIn('order_item_id', $orderItemItems)->where('user_id', $order_user_id);

        if ($order->trashed()) {
            $order->forceDelete();
            $orderItem->forceDelete();
            $OrderItemVariation->forceDelete();
            return \response([
                'status' => true,
                'icon' => 'success',
                'msg' => 'Order, Order Item and Item variation permanently deleted',
            ]);
        } else if ($order->delete()) {
            $orderItem->delete();
            $OrderItemVariation->delete();
            return \response([
                'status' => true,
                'icon' => 'success',
                'msg' => 'Order, Order Item and Item variation delete successfully',
            ]);
        }
        return \response([
            'status' => false,
            'icon' => 'error',
            'msg' => 'Delete failed',
        ]);
    }

    public function trashed()
    {
        $orders = Order::onlyTrashed()->latest()->paginate(10);
        return view('backend.content.order.trash', compact('orders'));
    }

    public function restore($id)
    {
        $trashOrder = Order::onlyTrashed()->findOrFail($id);

        $order_id = $id;
        $order_user_id = $trashOrder->user_id;

        $orderItem = OrderItem::onlyTrashed()->where('order_id', $order_id)
            ->where('user_id', $order_user_id);
        $orderItemItems = $orderItem->pluck('id')->toArray();
        $OrderItemVariation = OrderItemVariation::onlyTrashed()->whereIn('order_item_id', $orderItemItems)->where('user_id', $order_user_id);

        $trashOrder->restore();
        $orderItem->restore();
        $OrderItemVariation->restore();

        return redirect()->route('admin.order.index')->withFlashSuccess('Order Recovered Successfully');
    }


    public function paymentValidator($update_id = null)
    {

        return request()->validate([
            'type' => 'required|string|max:155|exists:package_types,slug',
            'plan' => 'required|string|max:155|exists:packages,slug',
            'package' => 'required|numeric|max:9999|exists:packages,id',
            'domain' => $update_id ? 'required|string|max:191|unique:orders,domain,' . $update_id : 'required|string|max:191|unique:orders,domain',
            'payment_method' => 'required|string|max:191',
            'agent_account' => 'required|string|max:191', // payment received agent number
            'subs_year' => 'required|numeric|max:5',
            'subs_total' => 'required|numeric|max:9999',
            'client_account' => 'required|string|max:191',
            'transaction_no' => 'required|string|max:191',
        ]);
    }
}
