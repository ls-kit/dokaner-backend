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
              <h3>Orders Id #{{$order->order_number}} / <span class="text-danger">{{$order->status}}</span></h3>
            </div>
            <div class="card-body">
              <div class="table-responsive mt-3">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th class="text-center" style="min-width: 100px">#</th>
                      <th class="text-center" colspan="2">Details</th>
                      <th class="text-center" style="width:20%">Rate({{$currency}})</th>
                      <th class="text-center" style="width:20%">Quantity</th>
                      <th class="text-center" style="width:20%">Total({{$currency}})</th>
                    </tr>
                  </thead>
                  <tbody>
                    @php
                    $totalProductPrice = 0;
                    @endphp

                    @foreach ($order->orderItems as $item)
                    <tr>
                      <td class="text-left" colspan="9">
                        <span style="font-size: 16px;" class="text-danger">{{$item->order_item_number}}</span> /
                        <a href="{{url($item->link)}}">{{strip_tags($item->name)}}</a>
                      </td>
                    </tr>
                    @php
                    $itemTotalPrice = 0;
                    @endphp

                    @foreach($item->itemVariations as $variationKey => $variation )

                    @php
                    $attributes = json_decode($variation->attributes);
                    $attrLength = count($attributes);
                    $sinQuantity = $variation->quantity;
                    $subTotal = $variation->subTotal;
                    $itemTotalPrice += $subTotal;
                    @endphp
                    @forelse ($attributes as $attribute)
                    @php
                    $PropertyName = $attribute->PropertyName;
                    $Value = $attribute->Value;
                    @endphp
                    @if ($loop->first)
                    <tr>
                      <td class="align-middle text-center" rowspan="{{$attrLength}}">
                        <img src="{{asset($variation->image)}}" class="img-fluid">
                      </td>
                      <td class="text-capitalize align-middle text-center">{!! $PropertyName !!}</td>
                      <td class="align-middle text-center">{{$Value}}</td>
                      <td class="align-middle text-center" rowspan="{{$attrLength}}"> <span
                          class="unitPrice">{{floating($variation->price)}}</span></td>
                      <td class="align-middle text-center" rowspan="{{$attrLength}}">{{$sinQuantity}}</td>
                      <td class="align-middle text-right" rowspan="{{$attrLength}}">
                        <span class="SingleTotal">{{floating($subTotal)}}</span>
                      </td>
                      @if ($variationKey === 0)
                      @php
                      $LengthTotal = (count($item->itemVariations) * $attrLength) + 4;
                      @endphp
                      @endif
                    </tr>
                    @else
                    <tr>
                      <td class="text-capitalize  text-center">{!! $PropertyName !!}</td>
                      <td class=" text-center">{{$Value}}</td>
                    </tr>
                    @endif
                    @empty
                    <tr>
                      <td class="align-middle text-center">
                        <img src="{{asset($variation->image)}}" class="img-fluid">
                      </td>
                      <td colspan="2" class="align-middle text-center">No Attribites</td>
                      <td class="align-middle text-center"> <span
                          class="unitPrice">{{floating($variation->price)}}</span></td>
                      <td class="align-middle text-center">{{$sinQuantity}}</td>
                      <td class="align-middle text-right">
                        <span class="SingleTotal">{{floating($subTotal)}}</span>
                      </td>
                    </tr>
                    @endforelse

                    @if ($variationKey === 0)
                    @php
                    $LengthTotal = (count($item->itemVariations) * $attrLength) + 4;
                    @endphp
                    @endif

                    @endforeach
                    @php
                    $chinaLocalDelivery = $item->chinaLocalDelivery;
                    $product_value = $item->product_value + $chinaLocalDelivery;
                    $shippingCharge = 0;
                    @endphp
                    <tr>
                      <td class="text-right" colspan="4">China Local Delivery</td>
                      <td class="text-center">-</td>
                      <td class="text-right"><span>{{floating($chinaLocalDelivery)}}</span></td>
                    </tr>

                    @if($item->coupon_contribution)
                    <tr>
                      <td class="text-right" colspan="4">Coupon (-)</td>
                      <td></td>
                      <td class="text-right"> <span
                          class="totalItemPrice">{{floating($item->coupon_contribution)}}</span>
                      </td>
                    </tr>
                    @endif
                    <tr>
                      <td class="text-right" colspan="4">
                        Shipping Rate <span class="text-danger">({{$currency}})
                          {{floating($item->shipping_rate)}}</span>
                        Per KG
                      </td>
                      <td class="text-center align-middle">-</td>
                      <td class="text-right align-middle"><span>{{floating($shippingCharge)}}</span>
                      </td>
                    </tr>
                    <tr>
                      <td class="text-right" colspan="4">
                        Product Value
                      </td>
                      <td class="text-center align-middle">-</td>
                      <td class="text-right align-middle"><span>{{floating($product_value)}}</span>
                      </td>
                    </tr>
                    @php
                    $totalProductPrice += $product_value;
                    @endphp

                    @endforeach
                  </tbody>
                  <tfoot>

                    <tr>
                      <td class="text-right" colspan="4">Total Products Price</td>
                      <td class="text-center">-</td>
                      <td class="text-right align-middle"><span>{{floating($totalProductPrice)}}</span>
                      </td>
                    </tr>
                    <tr>
                      <td class="text-right" colspan="4">Need to Pay 50%</td>
                      <td class="text-center">-</td>
                      <td class="text-right align-middle"> <span
                          id="needToaPayAmount">{{floating($totalProductPrice * 0.5)}}</span>
                      </td>
                    </tr>
                    <tr>
                      <td class="text-right text-danger" colspan="4">Due (Only for products)</td>
                      <td class="text-center">-</td>
                      <td class="text-right align-middle">
                        <span>{{floating($totalProductPrice * 0.5)}}</span>
                      </td>
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

                <button class="w-100 btn btn-fill-out" id="incompletePayNowBtn" data-transaction="{{$order->transaction_id}}">@lang('Pay
                  Now')</button>
              </div>

            </div> <!-- div -->
          </div>
        </div> <!-- .card -->
      </div> <!-- col-lg-9 -->
    </div> <!-- row-->
  </div>
</div>
</div> <!-- END MAIN CONTENT -->
@endsection

