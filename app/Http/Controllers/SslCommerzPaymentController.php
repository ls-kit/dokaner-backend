<?php

namespace App\Http\Controllers;

use App\Models\Content\Invoice;
use App\Models\Content\Order;
use App\Notifications\InvoicePaymentInformation;
use DB;
use Illuminate\Http\Request;
use App\Library\SslCommerz\SslCommerzNotification;
use App\Models\Auth\User;
use App\Models\Content\OrderItem;
use App\Notifications\OrderAuthInformation;
use App\Notifications\OrderPending;
use Notification;

class SslCommerzPaymentController extends Controller
{


  public function index(Request $request)
  {
    $tran_id = \request('tran_id');
    $user = auth()->user();
    $order = Order::where('transaction_id', $tran_id)->where('user_id', $user->id)->first();
    $invoice = Invoice::where('invoice_no', $tran_id)->where('user_id', $user->id)->first();

    if ($invoice || $order) {
      $address = $order ? $order->address : $invoice->customer_address;
      $amount = $order ? $order->needToPay : $invoice->total_payable;
      $fullName = $user->first_name . ' ' . $user->last_name;
      $email = $user->email ?? 'customer@mail.com';
      $address = json_decode($address, true) ?? [];
      $fullAddress = $address['address'] ?? 'no address';
      $phone = $user->phone ?? $address['phone_one'] ?? '8801XXXXXXXXX';

      // dd(ceil($amount));

      $post_data = array();
      $post_data['total_amount'] = ceil($amount); # You cant not pay less than 10
      $post_data['currency'] = "BDT";
      $post_data['tran_id'] = $tran_id; // tran_id must be unique

      # CUSTOMER INFORMATION
      $post_data['cus_name'] = $fullName;
      $post_data['cus_email'] = $email;
      $post_data['cus_add1'] = $fullAddress;
      $post_data['cus_add2'] = "";
      $post_data['cus_city'] = "";
      $post_data['cus_state'] = "";
      $post_data['cus_postcode'] = "";
      $post_data['cus_country'] = "Bangladesh";
      $post_data['cus_phone'] = $phone;
      $post_data['cus_fax'] = "";

      # SHIPMENT INFORMATION
      $post_data['ship_name'] = "Store Test";
      $post_data['ship_add1'] = "Dhaka";
      $post_data['ship_add2'] = "Dhaka";
      $post_data['ship_city'] = "Dhaka";
      $post_data['ship_state'] = "Dhaka";
      $post_data['ship_postcode'] = "1000";
      $post_data['ship_phone'] = "";
      $post_data['ship_country'] = "Bangladesh";

      $post_data['shipping_method'] = "NO";
      $post_data['product_name'] = "Computer";
      $post_data['product_category'] = "Goods";
      $post_data['product_profile'] = "physical-goods";


      $sslc = new SslCommerzNotification();
      $payment_options = $sslc->makePayment($post_data, 'checkout', 'json');

      return response($payment_options);
    } else {
      return response(['status' => false]);
    }
  }

  public function payViaAjax(Request $request)
  {
    $trans_id = \request('order');
    $postData = json_decode(\request('cart_json'), true);
    $summary = array_key_exists('summary', $postData) ? $postData['summary'] : [];
    $address = array_key_exists('address', $postData) ? $postData['address'] : [];
    $amount = array_key_exists('needToPay', $summary) ? $summary['needToPay'] : 0;
    $customer = auth()->user();
    $shipping = empty($address) ? $customer->shipping : $address;

    $phone = null;
    $email = null;
    $fullAddress = null;
    $fullName = null;

    if (isset($customer->phone)) {
      $phone = $customer->phone;
    }
    if (isset($customer->email)) {
      $email = $customer->email;
    }
    if (isset($customer->full_name)) {
      $fullName = $customer->full_name ? $customer->full_name : null;
    }

    if (is_array($shipping)) {
      $fullAddress = $shipping['address'] ?? 'Shipping Address Not Found';
      $phone = $shipping['phone_one'] ?? null;
      $name = $shipping['name'] ?? null;
      $fullName = $customer->full_name ? $customer->full_name : $name;
    } else {
      $fullAddress = isset($shipping->address) ? $shipping->address : 'Shipping Address Not Found';
      $phone = $shipping->phone_one ?? null;
      $name = $shipping->name ?? null;
      $fullName = $customer->full_name ? $customer->full_name : $name;
    }

    if (!$phone) {
      $phone = $phone ? $phone : '8801XXXXXXXXX';
    }
    if (!$email) {
      $email = 'guest@chinabazarb2b.com';
    }

    // dd(request()->all());
    $post_data = array();
    $post_data['total_amount'] = $amount; # You cant not pay less than 10
    $post_data['currency'] = "BDT";
    $post_data['tran_id'] = $trans_id; // tran_id must be unique

    # CUSTOMER INFORMATION
    $post_data['cus_name'] = $fullName ? $fullName : 'Guest Customer';
    $post_data['cus_email'] = $email;
    $post_data['cus_add1'] = $fullAddress;
    $post_data['cus_add2'] = "";
    $post_data['cus_city'] = "";
    $post_data['cus_state'] = "";
    $post_data['cus_postcode'] = "";
    $post_data['cus_country'] = "Bangladesh";
    $post_data['cus_phone'] = $phone;
    $post_data['cus_fax'] = "";

    # SHIPMENT INFORMATION
    $post_data['ship_name'] = "Store Test";
    $post_data['ship_add1'] = "Dhaka";
    $post_data['ship_add2'] = "Dhaka";
    $post_data['ship_city'] = "Dhaka";
    $post_data['ship_state'] = "Dhaka";
    $post_data['ship_postcode'] = "1000";
    $post_data['ship_phone'] = "";
    $post_data['ship_country'] = "Bangladesh";

    $post_data['shipping_method'] = "NO";
    $post_data['product_name'] = "Computer";
    $post_data['product_category'] = "Goods";
    $post_data['product_profile'] = "physical-goods";

    dd($post_data);
    $sslc = new SslCommerzNotification();

    # initiate(Transaction Data , false: Redirect to SSLCOMMERZ gateway/ true: Show all the Payement gateway here )
    $payment_options = $sslc->makePayment($post_data, 'checkout', 'json');
    // dd($payment_options);
    if (!is_array($payment_options)) {
      print_r($payment_options);
      $payment_options = array();
    }
  }


  public function success(Request $request)
  {
    $tran_id = $request->input('tran_id');
    $amount = $request->input('amount');
    $currency = $request->input('currency');

    $sslc = new SslCommerzNotification();

    $order = Order::with('user')->where('transaction_id', $tran_id)->first();

    if ($order) {
      if ($order->status == 'Waiting for Payment') {
        $validation = $sslc->orderValidate($tran_id, $amount, $currency, $request->all());
        if ($validation == TRUE) {
          $order->update(['status' => 'Partial Paid']);
          OrderItem::where('order_id', $order->id)->update(['status' => 'Partial Paid']);

          try {
            $users = User::role('administrator')->get();
            Notification::send($users, new OrderAuthInformation($order));
            if (get_setting('active_partial_paid')) {
              $order->user->notify(new OrderPending($order));
            }
          } catch (\Exception $ex) {
          }

          return redirect()->route('frontend.user.dashboard', ['payment' => 'successful', 'tab' => 'orders'])
            ->withFlashSuccess('Thanks for Order. Your Order is Processing');
        } else {
          $order->update(['status' => 'Failed']);
          return redirect('payment')->withFlashDanger('Your Payment Not completed. Your Order is Failed');
        }
      } else if ($order->status == 'Partial Paid' || $order->status == 'Complete') {
        return redirect()->route('frontend.user.dashboard')
          ->withFlashSuccess('Thanks for Order. Your Order is Processing');
      } else {
        return redirect('payment')->withFlashDanger('Your Transaction is Invalid');
      }
    }

    $invoice = Invoice::with('user')->where('invoice_no', $tran_id)->first();

    if ($invoice) {
      if ($invoice->status == 'Pending') {
        $validation = $sslc->orderValidate($tran_id, $amount, $currency, $request->all());
        if ($validation == TRUE) {
          $invoice->update(['status' => 'confirm_received']);
          try {
            $users = User::role('administrator')->get();
            Notification::send($users, new InvoicePaymentInformation($invoice));
          } catch (\Exception $ex) {
          }

          return redirect()->route('frontend.user.dashboard', ['tab' => 'invoice'])
            ->withFlashSuccess('Thanks for Payment');
        }
      } else {
        return redirect('payment')->withFlashDanger('Your Transaction is Invalid');
      }

      return redirect()->route('frontend.user.dashboard', ['tab' => 'invoice'])
        ->withFlashDanger('Your Payment Fail');
    }
  }


  public function fail(Request $request)
  {
    $tran_id = $request->input('tran_id');
    $order = Order::where('transaction_id', $tran_id)->first();

    if ($order->status == 'Waiting for Payment') {
      $order->update(['status' => 'Waiting for Payment']);
      return redirect('payment')->withFlashDanger('Transaction is Falied');
    } else if ($order->status == 'Partial Paid' || $order->status == 'Complete') {
      // echo "Transaction is already Successful";
      return redirect('payment')->withFlashSuccess('Transaction is already Successful');
    } else {
      // echo "Transaction is Invalid";
      return redirect('payment')->withFlashDanger('Transaction is Invalid');
    }
  }

  public function cancel(Request $request)
  {
    $tran_id = $request->input('tran_id');
    $order = Order::where('transaction_id', $tran_id)->first();
    if ($order) {
      if ($order->status == 'Waiting for Payment') {
        return redirect()->route('frontend.user.failedOrderPayNow', $tran_id)->withFlashSuccess('Transaction is Cancel');
      } else if ($order->status == 'Partial Paid' || $order->status == 'Complete') {
        return redirect()->route('frontend.user.failedOrderPayNow', $tran_id)->withFlashSuccess('Transaction is already Successful');
      } else {
        return redirect()->route('frontend.user.failedOrderPayNow', $tran_id)->withFlashWarning('Transaction is Invalid');
      }
    }

    $invoice = Invoice::with('user')->where('invoice_no', $tran_id)->first();
    if ($invoice) {
      return redirect()->route('frontend.user.invoice.payNow', $tran_id)->withFlashDanger('Nagad transaction fail');
    }

    return redirect()->route('frontend.user.dashboard', ['tab' => 'invoice'])
      ->withFlashDanger('Your Payment Fail');
  }

  public function ipn(Request $request)
  {
    #Received all the payment information from the gateway
    if ($request->input('tran_id')) #Check transaction id is posted or not.
    {
      $tran_id = $request->input('tran_id');
      #Check order status in order tabel against the transaction id or order id.
      $order_details = Order::where('transaction_id', $tran_id)
        ->select('transaction_id', 'status', 'currency', 'amount')->first();

      if ($order_details->status == 'Waiting for Payment') {
        $sslc = new SslCommerzNotification();
        $validation = $sslc->orderValidate($tran_id, $order_details->amount, $order_details->currency, $request->all());
        if ($validation == TRUE) {
          Order::where('transaction_id', $tran_id)
            ->update(['status' => 'Partial Paid']);
          echo "Transaction is successfully Completed";
        } else {
          Order::where('transaction_id', $tran_id)
            ->update(['status' => 'Failed']);
          echo "validation Fail";
        }
      } else if ($order_details->status == 'Partial Paid' || $order_details->status == 'Complete') {
        #That means Order status already updated. No need to udate database.
        echo "Transaction is already successfully Completed";
      } else {
        #That means something wrong happened. You can redirect customer to your product page.

        echo "Invalid Transaction";
      }
    } else {
      echo "Invalid Data";
    }
  }


  /**
   * @param Request $request
   */
  public function payInvoiceDue(Request $request)
  {
    $trans_id = \request('order');
    $postData = json_decode(\request('cart_json'), true);
    $summary = array_key_exists('summary', $postData) ? $postData['summary'] : [];
    $address = array_key_exists('address', $postData) ? $postData['address'] : [];
    $amount = array_key_exists('needToPay', $summary) ? $summary['needToPay'] : 0;
    $customer = auth()->user();
    $shipping = empty($address) ? $customer->shipping : $address;
    $phone = null;
    $email = null;
    $fullAddress = null;
    $fullName = null;

    if (isset($customer->phone)) {
      $phone = $customer->phone;
    }
    if (isset($customer->email)) {
      $email = $customer->email;
    }
    if (isset($customer->full_name)) {
      $fullName = $customer->full_name ? $customer->full_name : null;
    }

    if (is_array($shipping)) {
      $fullAddress = $shipping['address'] ?? 'Shipping Address Not Found';
      $phone = $shipping['phone_one'] ?? null;
      $name = $shipping['name'] ?? null;
      $fullName = $customer->full_name ? $customer->full_name : $name;
    } else {
      $fullAddress = isset($shipping->address) ? $shipping->address : 'Shipping Address Not Found';
      $phone = $shipping->phone_one ?? null;
      $name = $shipping->name ?? null;
      $fullName = $customer->full_name ? $customer->full_name : $name;
    }

    if (!$phone) {
      $phone = $phone ? $phone : '8801XXXXXXXXX';
    }
    if (!$email) {
      $email = 'guest@chinabazarb2b.com';
    }

    $post_data = array();
    $post_data['total_amount'] = $amount; # You cant not pay less than 10
    $post_data['currency'] = "BDT";
    $post_data['tran_id'] = $trans_id; // tran_id must be unique

    # CUSTOMER INFORMATION
    $post_data['cus_name'] = $fullName ? $fullName : 'Guest Customer';
    $post_data['cus_email'] = $email;
    $post_data['cus_add1'] = $fullAddress;
    $post_data['cus_add2'] = "";
    $post_data['cus_city'] = "";
    $post_data['cus_state'] = "";
    $post_data['cus_postcode'] = "";
    $post_data['cus_country'] = "Bangladesh";
    $post_data['cus_phone'] = $phone;
    $post_data['cus_fax'] = "";

    # SHIPMENT INFORMATION
    $post_data['ship_name'] = "Store Test";
    $post_data['ship_add1'] = "Dhaka";
    $post_data['ship_add2'] = "Dhaka";
    $post_data['ship_city'] = "Dhaka";
    $post_data['ship_state'] = "Dhaka";
    $post_data['ship_postcode'] = "1000";
    $post_data['ship_phone'] = "";
    $post_data['ship_country'] = "Bangladesh";

    $post_data['shipping_method'] = "NO";
    $post_data['product_name'] = "Computer";
    $post_data['product_category'] = "Goods";
    $post_data['product_profile'] = "physical-goods";

    // $post_data['success_url'] = "/invoice-payment-receive";


    $sslc = new SslCommerzNotification();

    # initiate(Transaction Data , false: Redirect to SSLCOMMERZ gateway/ true: Show all the Payement gateway here )
    $payment_options = $sslc->makePayment($post_data, 'checkout', 'json');
    // dd($payment_options);
    if (!is_array($payment_options)) {
      print_r($payment_options);
      $payment_options = array();
      //            return response()->json($payment_options);
    }
  }
}
