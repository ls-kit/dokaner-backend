<?php

namespace App\Http\Controllers;

use App\Models\Auth\User;
use App\Models\Content\Invoice;
use App\Models\Content\Order;
use App\Models\Content\OrderItem;
use App\Notifications\OrderAuthInformation;
use App\Notifications\OrderPending;
use Illuminate\Http\Request;
use Notification;

class SManagerPaymentController extends Controller
{


  public function initial_payment($tran_id = '')
  {
    $order = Order::withCount('orderItems')->where('transaction_id', $tran_id)->first();
    $link = null;
    $message = 'Order not found';
    $status = false;
    $site_url = get_setting('site_url', url('/'));
    if ($order) {
      $customer_name = $order->name ?? '';
      $customer_phone = $order->phone ?? '';
      $total_items = $order->order_items_count ?? '';
      $amount = $order->needToPay ?? '';
      $info = [
        'amount'          => $amount,
        'transaction_id'  => $tran_id,
        'success_url'     => "{$site_url}/online/payment/success/{$tran_id}",
        'fail_url'        => "{$site_url}/online/payment/failed/{$tran_id}",
        'customer_name'   => $customer_name,
        'customer_mobile' => $customer_phone,
        'purpose'         => 'Online Payment',
        'payment_details' => 'Payment for buying ' . $total_items . ' items'
      ];
      // dd($info);
      $response = sManagerInitiatePayment($info);
      // return ['$response' => $response];
      if (is_array($response)) {
        $code = array_key_exists('code', $response) ? $response['code'] : [];
        $message = array_key_exists('message', $response) ? $response['message'] : [];
        $data = array_key_exists('data', $response) ? $response['data'] : [];

        if ($code == 200) {
          $status = true;
          $link = array_key_exists('link', $data) ? $data['link'] : null;
        }
      }
    }

    return [
      'status' => $status,
      'message' => $message,
      'redirect' => $link
    ];
  }


  public function success($tran_id)
  {
    $user_id = auth()->id();
    $is_admin = auth()->user()->hasRole('administrator');
    $order = Order::with('user')->where('transaction_id', $tran_id);
    $order = $is_admin ? $order->first() : $order->where('user_id', $user_id)->first();

    if ($order) {
      if ($order->status == 'waiting-for-payment') {
        $order->update(['status' => 'partial-paid']);
        OrderItem::where('order_id', $order->id)->update(['status' => 'partial-paid']);
        $users = User::role('administrator')->get();
        Notification::send($users, new OrderAuthInformation($order));
        if (get_setting('active_partial_paid')) {
          $order->user->notify(new OrderPending($order));
        }
        return true;
      } else if ($order->status == 'partial-paid' || $order->status == 'complete') {
        return true;
      }
      return false;
    }

    $invoice = Invoice::with('user')->where('invoice_no', $tran_id);
    $invoice = $is_admin ? $invoice->first() : $invoice->where('user_id', $user_id)->first();

    if ($invoice) {
      if ($invoice->status == 'pending') {
        $invoice->update(['status' => 'confirm-received']);
        // $users = User::role('administrator')->get();
        // Notification::send($users, new InvoicePaymentInformation($invoice));
        return true;
      }
    }

    return false;
  }

  public function fail($tran_id)
  {
    $order = Order::where('transaction_id', $tran_id)
      ->select('transaction_id', 'status', 'currency', 'amount')->first();
    if ($order->status == 'waiting-for-payment') {
      $order->update(['status' => 'waiting-for-payment']);
      return true;
    } else if ($order->status == 'partial-paid' || $order->status == 'complete') {
      return true;
    }

    return false;
  }
}
