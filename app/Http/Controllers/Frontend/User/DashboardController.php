<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Models\Auth\User;
use App\Models\Content\Frontend\Address;
use App\Models\Content\Invoice;
use App\Models\Content\Order;
use App\Models\Content\OrderItem;
use Auth;

/**
 * Class DashboardController.
 */
class DashboardController extends Controller
{
  /**
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function index()
  {
    $auth = Auth::user();
    // // $auth = Auth::user()->with('orders', 'address', 'shipped');
    $orders = OrderItem::where('user_id', $auth->id)->latest()->paginate();
    $addresses = Address::where('user_id', $auth->id)->latest()->get();

    //     dd($orders);
    return view('frontend.user.dashboard', compact('orders', 'addresses'));
  }

  public function notification()
  {
    $unread = auth()->user()->notifications;
    dd($unread);
  }



  public function orderDetails($order_id)
  {
    $order = Order::with('orderItems.itemVariations')->where('id', $order_id)->where('user_id', auth()->id())->firstOrFail();

    return view('frontend.user.order.orderDetails', compact('order'));
  }


  public function failedOrderPayNow($tran_id)
  {
    $developer = request('developer');
    $user_id = auth()->id();
    if ($developer) {
      $order = Order::with('orderItems')->where('transaction_id', $tran_id)->firstOrFail();
    } else {
      $order = Order::with('orderItems')->where('transaction_id', $tran_id)->where('user_id', $user_id)->firstOrFail();
    }


    // $test = bkash_refund_status(
    //   [
    //     "paymentID" => "J60GBO71622310941598",
    //     "trxID" => "8ET404SRGO",
    //   ]
    // );
    // $token = session('bkash_token');
    // dd($test);


    // transaction_cookie_set($tran_id);

    return view('frontend.user.order.payNow', compact('order'));
  }



  public function invoiceDetails($invoice_id)
  {
    $invoice = Invoice::with('invoiceItems')->where('id', $invoice_id)->where('user_id', auth()->id())->firstOrFail();
    return view('frontend.user.invoice.invoiceDetails', compact('invoice'));
  }

  public function invoicePayNow($invoice_id)
  {
    $invoice = Invoice::with('invoiceItems')
      ->where('id', $invoice_id)
      ->where('user_id', auth()->id())
      ->firstOrFail();

    //    transaction_cookie_set($invoice_id);
    return view('frontend.user.invoice.invoicePayNow', compact('invoice'));
  }
}
