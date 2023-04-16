@extends('frontend.layouts.app')

@section('title', 'Confirm Your Payment')

@php
  $order_id = 'uni'.uniqid();
@endphp

@section('content')
  <div class="breadcrumb_section tittle_breaddrumb p-4 bg_gray page-title-mini">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-md-12">
          <ol class="breadcrumb justify-content-md-start">
            <li class="breadcrumb-item"><a href="{{route('frontend.index')}}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{route('frontend.shoppingCart')}}">Checkout</a></li>
            <li class="breadcrumb-item active">Payment</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <div class="main_content">

    <div class="section" data-page="payment">
      <div class="container">
        <div class="row justify-content-center mb-5">
          <div class="col-md-9">

            <div class="card">
              <div class="card-body">
                <div class="heading_s1 mb-3">
                  <h4>Shipping Address</h4>
                </div>
                <div class="table-responsive">
                  <table class="table">
                    <tbody id="address_body"></tbody>
                  </table>
                </div>
              </div> <!-- card-body -->
              <hr>
              <div class="card-body">
                <div class="heading_s1 mb-3">
                  <h4>Payment Summary</h4>
                </div>
                <div class="table-responsive">
                  <table class="table">
                    <tbody>
                    <tr>
                      <td class="cart_total_label">Products Price</td>
                      <td class="cart_total_amount text-right"><span id="productTotalPrice">0.00</span></td>
                    </tr>
                    <tr id="couponRow">
                      <td class="cart_total_label">Coupon</td>
                      <td class="cart_total_amount text-right" id="couponDiscount">0.00</td>
                    </tr>
                    <tr>
                      <td class="cart_total_label">Need to Pay 50%</td>
                      <td class="cart_total_amount text-right"><span id="needToPay">0.00</span>
                      </td>
                    </tr>
                    <tr>
                      <td class="cart_total_label">Due (Only for products)</td>
                      <td class="cart_total_amount text-right"><strong id="dueForProducts">0.00</strong></td>
                    </tr>
                    <tr>
                      <td colspan="2" class="text-center text-danger">
                        {{get_setting('payment_summary_bottom_message')}}
                      </td>
                    </tr>
                    </tbody>
                  </table>
                </div>

                <p class="text-center mb-0">
                  Pay Online(Credit/Debit Card/MobileBanking/NetBanking/bKash)
                </p>
                <p class="text-center">
                  Varified By
                  <img class="img-fluid mt-2" src="{{asset('img/frontend/sslcommerz.png')}}" style="width: 120px"
                       alt="sslcommerz">
                </p>

                <p class="text-center">Your personal data will be used to process your order, support
                  your
                  experience throughout this website, and for other purposes described in our <a
                      href="{{url('privacy-policy')}}" class="btn-link" target="_blank">privacy
                    policy</a>.
                </p>
                <div class="form-check mb-4 text-center">
                  <input class="form-check-input" name="terms-field" type="checkbox" value="1" id="termsField">
                  <label class="form-check-label text-justify" for="termsField">
                  <span>I have read and agree to the website <a class="btn-link"
                                                                href="{{url('terms-conditions')}}}}">Terms and Conditions</a>, <a
                        class="btn-link"
                        href="{{url('prohibited-items')}}">Prohibited Items</a> and <a class="btn-link"
                                                                                       href="{{url('return-and-refund-policy')}}">Refund Policy</a></span>
                  </label>
                </div>

                <button class="w-100 btn btn-fill-out" id="payNowBtn" data-order="{{$order_id}}"> Pay Now</button>

              </div> <!-- card-body -->
            </div>
          </div>
        </div> <!-- row -->
        <div class="row justify-content-center">
          <div class="col-sm-12">
            <p class="text-center">
              <img src="{{asset('img/frontend/ssl-commerz-pay-with-logo-payment.webp')}}" alt="SSLCommerz">
            </p>
          </div>
        </div> <!-- row -->
      </div>
    </div>

  </div> <!-- END MAIN CONTENT -->

@endsection

