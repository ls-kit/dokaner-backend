@extends('frontend.layouts.app')

@section('title', 'Pay Incomplete Order' )

@php
$currency = get_setting('currency_icon');
$productLoader = get_setting('product_image_loader');
@endphp
@section('content')
<div class="main_content">
  <div class="section pb-5">
    <div class="container">
      <div class="justify-content-around row">
        <div class="col-md-9">
          <div class="card">
            <div class="card-header">
              <h3>Invoice Id #{{$invoice->invoice_no}} | <span
                  class="text-danger">{{readable_status($invoice->status)}}</span></h3>
            </div>
            <div class="card-body">
              <div class="table-responsive mt-3">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th scope="col" class="text-center">SL</th>
                      <th scope="col">Item No.</th>
                      <th scope="col">Product</th>
                      <th scope="col" class="text-center">Status</th>
                      <th scope="col" class="text-center">Weight</th>
                      <th scope="col" class="text-center">Due</th>
                    </tr>
                  </thead>

                  <tbody>
                    @php
                    $actual_weight = 0;
                    @endphp
                    @foreach($invoice->invoiceItems as $item)
                    @php
                    $weight = $item->weight ? $item->weight : 0;
                    @endphp
                    <tr>
                      <td class=" align-middle">{{$loop->iteration}}</td>
                      <td class=" align-middle">{{$item->order_item_number}}</td>
                      <td class=" align-middle">{{$item->product_name}}</td>
                      <td class=" align-middle">{{readable_status($item->status)}}</td>
                      <td class="text-right align-middle">{{floating($weight, 3)}}</td>
                      <td class="text-right align-middle">{{floating($item->total_due)}}</td>
                    </tr>
                    @php
                    $actual_weight += $weight;
                    @endphp
                    @endforeach
                  </tbody>
                  <tfoot id="invoiceFooter">
                    <tr>
                      <td colspan="4" class="text-right">Total</td>
                      <td class="text-right"><span class="total_weight">{{floating($actual_weight, 3)}}</span></td>
                      <td class="text-right"><span class="total_due">{{floating($invoice->total_due)}}</span></td>
                    </tr>
                    <tr>
                      <td colspan="4" class="align-middle text-right">
                        Courier Bill
                      </td>
                      <td class="text-center">-</td>
                      <td class="text-right"><span class="courier_bill">{{floating($invoice->total_courier)}}</span>
                      </td>
                    </tr>
                    <tr>
                      <td colspan="4" class="text-right">Total Payable</td>
                      <td class="text-center">-</td>
                      <td class="text-right">
                        <span class="total_payable" id="needToaPayAmount"
                          data-user="{{$invoice->user_id}}">{{floating($invoice->total_payable)}}</span>
                      </td>
                    </tr>
                    <tr>
                      <td colspan="4" class="text-right">Payment Method</td>
                      <td class="text-center">-</td>
                      <td class="text-center">{{$invoice->payment_method ? $invoice->payment_method : 'N/A'}}</td>
                    </tr>
                  </tfoot>
                </table>
              </div> <!-- table-responsive -->

              <div class="payment-process-area">
                <div class="text-center my-4">
                  <input type="hidden" id="payment_method" value="">
                  @if (get_setting('is_bkash'))
                  <div class="form-check form-check-inline ">
                    <label class="form-check-label payment_label" for="bkash">
                      <img src="{{asset('img/frontend/payment/bkash.png')}}">
                    </label>
                  </div>
                  @endif
                  @if (get_setting('is_nagad'))
                  <div class="form-check form-check-inline">
                    <label class="form-check-label payment_label" for="nagad">
                      <img src="{{asset('img/frontend/payment/nagod.png')}}">
                    </label>
                  </div>
                  @endif
                  @if (get_setting('is_sslcommerz'))
                  <div class="form-check form-check-inline">
                    <label class="form-check-label payment_label" for="ssl-commerz">
                      <img src="{{asset('img/frontend/payment/ssl-commerz.png')}}">
                    </label>
                  </div>
                  @endif
                </div>

                <p class="text-center">Your personal data will be used to process your order, support
                  your experience throughout this website, and for other purposes described in our <a
                    href="{{url('privacy-policy')}}" class="btn-link" target="_blank">privacy
                    policy</a>.
                </p>
                <div class="form-check text-center mb-4">
                  <input class="form-check-input" name="terms-field" type="checkbox" value="1" id="termsField">
                  <label class="form-check-label text-justify" for="termsField">
                    <span>I have read and agree to the website <a class="btn-link"
                        href="{{url('terms-conditions')}}}}">Terms and Conditions</a>, <a class="btn-link"
                        href="{{url('prohibited-items')}}">Prohibited Items</a> and <a class="btn-link"
                        href="{{url('return-and-refund-policy')}}">Refund Policy</a></span>
                  </label>
                </div>

                <button class="w-100 btn btn-fill-out" id="incompletePayNowBtn" data-order="{{$invoice->invoice_no}}">@lang('Pay
                  Now')</button>
              </div>



            </div> <!-- div -->
          </div>
        </div> <!-- .card -->
      </div> <!-- col-lg-9 -->
    </div> <!-- row-->
  </div>
</div> <!-- END MAIN CONTENT -->
@endsection
