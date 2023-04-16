<?php

namespace App\Http\Controllers\Backend\Content;

use App\Http\Controllers\Controller;
use App\Models\Content\Order;
use App\Models\Content\OrderItem;
use App\Models\Content\OrderItemVariation;
use App\Models\Content\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('backend.content.product.index');
    }


    public function multiDelete()
    {
        $product_ids = request('product_ids', []);
        $data_table = request('data_table');
        $permanent = request('permanent', false);

        $response['status'] = false;
        $response['icon'] = 'error';
        $response['msg'] = 'Failed to deleted';

        if (!empty($product_ids)) {
            if ($data_table == "products") {
                if ($permanent) {
                    Product::withTrashed()->whereIn('id', $product_ids)->forceDelete();
                } else {
                    Product::whereIn('id', $product_ids)->delete();
                }
            } else if ($data_table == "orders") {
                if ($permanent) {
                    Order::withTrashed()->whereIn('id', $product_ids)->forceDelete();
                    $orderItems = OrderItem::withTrashed()->whereIn('order_id', $product_ids)->pluck('id')->toArray();
                    OrderItem::withTrashed()->whereIn('order_id', $product_ids)->forceDelete();
                    OrderItemVariation::withTrashed()->whereIn('order_item_id', $orderItems)->forceDelete();
                } else {
                    Order::whereIn('id', $product_ids)->delete();
                    $orderItems = OrderItem::whereIn('order_id', $product_ids)->pluck('id')->toArray();
                    OrderItem::whereIn('order_id', $product_ids)->delete();
                    OrderItemVariation::whereIn('order_item_id', $orderItems)->delete();
                }
            }
            $response['status'] = true;
            $response['icon'] = 'success';
            $response['msg'] = 'Successfully deleted your selected items';
        }

        return response($response);
    }

    /**
     * Display the specified resource.
     *
     * @param Product $product
     * @return Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Product $product
     * @return Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Product $product
     * @return Response
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Product $product
     * @return Response
     * @throws \Exception
     */
    public function destroy($id)
    {
        $product = Product::withTrashed()->find($id);
        if ($product->trashed()) {
            $product->forceDelete();
            return \response([
                'status' => true,
                'icon' => 'success',
                'msg' => 'Product permanently deleted',
            ]);
        } else if ($product->delete()) {
            return \response([
                'status' => true,
                'icon' => 'success',
                'msg' => 'Product moved to trashed successfully',
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
        dd("Developing");
        $products = Product::onlyTrashed()->latest()->paginate(10);
        return view('backend.content.product.trash', compact('products'));
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
}
