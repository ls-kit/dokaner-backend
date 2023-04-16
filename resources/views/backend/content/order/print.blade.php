@php
$currency = currency_icon();
$order = $orderItem->order;
$itemVariations = $orderItem->itemVariations;

@endphp

<!doctype html>
<html>

<head>
  <meta charset="utf-8">
  <title>Invoice No : {{$order->Id}}</title>
  <link rel="stylesheet" href="{{asset('assets/plugins/print/font-awesome/css/font-awesome.min.css')}}">
  <link rel="stylesheet" href="{{asset('assets/plugins/print/bootstrap/dist/css/bootstrap.min.css')}}">
  <link rel="stylesheet" href="{{asset('assets/plugins/print/order_print.css')}}" type="text/css" />
</head>

<body>
  <div id="wrapper">
    <div id="receiptData">
      <div id="receipt-data">
        <div id="receipt-data">
          <div class="logo_header">
            <table class="width_100_p">
              <tr>
                <td style="width: 20% !important;">
                  <img class="width_75_p" src="{{asset("img/frontend/brand/logo.svg")}}">
                </td>
                <td>
                  <h1 class="p_txt_1">{{config('app.name')}}</h1>
                  <h4 class="p_txt_2">01871-778844</h4>
                  <h3 class="p_txt_3">info@chinabazarb2b.com</h3>
                  <h3 class="p_txt_3">Fair Plaza, Shop- 28 & 29, 4th Floor, Mirpur-1, Dhaka-1216</h3>
                  <p class="inv_black">Invoice</p>
                </td>
                <td style="width: 20% !important;"></td>
              </tr>
            </table>
          </div>

          <table class="bold p_txt_4">
            <tr>
              <td class="p_txt_5"> Invoice: </td>
              <td class="p_txt_6"> {{$order->id}} </td>
            </tr>
            <tr>
              <td class="p_txt_5"> Invoice Date: </td>
              <td class="p_txt_6"> {{ date('M d, Y', strtotime($item->updated_at)) }}</td>
            </tr>
            <tr>
              <td class="p_txt_5"> Sales Associate: </td>
              <td class="p_txt_6">Online Payment</td>
            </tr>
          </table>
          <table class="width_40_p">
            <tr>
              <td class="p_txt_5"><b>Customer:</b></td>
              <td class="p_txt_6"><b>{{$order->name}}</b></td>
            </tr>
            <tr>
              <td class="p_txt_5"><b>Phone:</b></td>
              <td class="p_txt_6">{{$order->phone}}</td>
            </tr>


            <tr>
              <td class="p_txt_5">
                <b>Address:</b>
              </td>
              <td class="p_txt_6">Full Address</td>
            </tr>
          </table>

          <table class="tbl width_100_p">
            <thead>
              <tr>
                <th class="text-center" colspan="2">Details</th>
                <th class="text-center" style="width:20%">Quantity</th>
                <th class="text-center" style="width:20%">Total</th>
              </tr>
            </thead>
            <tbody>
              @php
              $totalItemQty = 0;
              $totalItemPrice = 0;
              @endphp

              <tr>
                <td class="text-left" colspan="4">
                  <a href="{{url($orderItem->link)}}">{{$orderItem->name}}</a>
                </td>
              </tr>

              @foreach($orderItem->itemVariations as $variation )

              @php
              $attributes = json_decode($variation->attributes);
              $attrLength = count($attributes) + 3;
              $sinQuantity = $variation->quantity;
              $subTotal = $variation->subTotal;
              $totalItemQty += $sinQuantity;
              $totalItemPrice += $subTotal;
              @endphp
              @foreach ($attributes as $attribute)
              @php
              $PropertyName = $attribute->PropertyName;
              $Value = $attribute->Value;
              @endphp
              @if ($loop->first)
              <tr>
                <td class="text-capitalize text-center">{!! $PropertyName !!}</td>
                <td class="align-middle text-center">{{$Value}}</td>
                <td class="align-middle text-center" rowspan="{{$attrLength}}"> {{$sinQuantity}}</td>
                <td class="align-middle text-right" rowspan="{{$attrLength}}">
                  <span class="SingleTotal">{{$currency}} {{floating($subTotal)}}</span>
                </td>
              </tr>
              @else
              <tr>
                <td class="text-capitalize  text-center">{!! $PropertyName !!}</td>
                <td class=" text-center">{{$Value}}</td>
              </tr>
              @endif
              @endforeach
              <tr>
                <td class=" text-center">Shipping Method:</td>
                <td class="text-center text-danger">
                  {{$orderItem->shipped_by .' - '.$currency .' '.floating($orderItem->shipping_rate)}}
                  Per KG
                </td>
              </tr>
              <tr>
                <td class=" text-center">Approx weight:</td>
                <td class="text-center ">{{$variation->approxWeight}} KG</td>
              </tr>
              <tr>
                <td class=" text-center">Per unit Price</td>
                <td class="text-center ">{{$currency}} <span class="unitPrice">{{floating($variation->price)}}</span>
                </td>
              </tr>
              @endforeach

              <tr>
                <td class="text-right" colspan="2">Sub Total</td>
                <td class="text-center">{{$totalItemQty}}</td>
                <td class="text-right">{{$currency}} <span class="totalItemPrice">{{floating($totalItemPrice)}}</span>
                </td>
              </tr>

              <tr>
                <td class="text-right" colspan="3">Products Price</td>
                <td class="text-right">{{$currency.' '.floating($order->amount)}}</td>
              </tr>
              <tr>
                <td class="text-right" colspan="3">Coupon Victory</td>
                <td class="text-right">{{$currency.' '.floating(0)}}</td>
              </tr>
              <tr>
                <td class="text-right" colspan="3">Need to Pay 50%</td>
                <td class="text-right">{{$currency.' '.floating($order->needToPay)}}</td>
              </tr>
              <tr>
                <td class="text-right text-danger" colspan="3">Due (Only for products)</td>
                <td class="text-right text-danger">{{$currency.' '.floating($order->dueForProducts)}}</td>
              </tr>
            </tbody>
          </table>


        </div>
      </div>
      <div class="clear_both"></div>
    </div>
    <?php if ( $order->dueForProducts !== " ") : ?>
    <div style="text-align: center">
      <img style="opacity: .5;" src="{{asset('assets/plugins/print/img/paid_seal.png')}}">
    </div>
    <?php  endif; ?>
    <footer>
      <td class="p_txt_12">
        <div class="p_txt_13">
          <p class="p_txt_14">&nbsp;&nbsp;&nbsp;&nbsp; Customer Signature</p>
        </div>
        <div class="p_txt_13">
          <p>&nbsp;</p>
        </div>
        <div class="p_txt_13">
          <p>&nbsp;</p>
        </div>
        <p class="p_txt_14">Authorized Signature</p>
      </td>
    </footer>
    <div class="p_txt_16 no_print">
      <hr>
      <span class="pull-right col-xs-12">
        <button onclick="window.print();" class="btn btn-block btn-primary">Print</button> </span>
      <div class="clear_both"></div>
      <div class="p_txt_17">
        <div class="p_txt_18">
          Please follow these steps before you print for first tiem:
        </div>
        <p class="p_txt_19">
          1. Disable Header and Footer in browser's print setting<br>
          For Firefox: File &gt; Page Setup &gt; Margins &amp; Header/Footer &gt; Headers & Footers &gt; Make all
          --blank--<br>
          For Chrome: Menu &gt; Print &gt; Uncheck Header/Footer in More Options
        </p>
        <p class="p_txt_19">
          2. Set margin 0.5<br>
          For Firefox: File &gt; Page Setup &gt; Margins &amp; Header/Footer &gt; Headers & Footers &gt; Margins
          (inches) &gt; set all margins
          0.5<br>
          For Chrome: Menu &gt; Print &gt; Set Margins to Default
        </p>
      </div>
      <div class="clear_both"></div>
    </div>
  </div>
  <script src="{{asset("assets/plugins/print/print/jquery-2.0.3.min.js") }}"></script>
  <script src="{{asset('assets/plugins/print/bootstrap/dist/js/bootstrap.min.js')}}"></script>
  <script src="{{asset("assets/plugins/print/print/custom.js") }}"></script>
  <script src="{{asset("assets/plugins/print/onload_print.js") }}"></script>
</body>

</html>