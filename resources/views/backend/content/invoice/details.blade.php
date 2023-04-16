@php
    $currency = currency_icon();
    $order = $invoice->user;
    $address = json_decode($invoice->customer_address, true);
    if (is_array($address)) {
        $address = array_key_exists('address', $address) ? $address['address'] : 'N/A';
    } else {
        $address = 'N/A';
    }

@endphp

<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Invoice No : {{ $invoice->invoice_no }}</title>
    {{--  <link rel="stylesheet" href="{{asset('assets/plugins/print/font-awesome/css/font-awesome.min.css')}}"> --}}
    <link rel="stylesheet" href="{{ asset('assets/plugins/print/bootstrap/dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/print/order_print.css') }}" type="text/css" />
    <style>
        th {
            background-color: orange !important;
        }

        * {
            font-family: 'Bebas Neue', sans-serif;
        }

        table, th, td, tr {
            border: 1px solid black;
        }

        @media print {
            body {-webkit-print-color-adjust: exact;}
        }
    </style>
</head>

<body>
    <div id="wrapper">
        <div id="receiptData">
            <div id="receipt-data">
                <div id="receipt-data">
                    {{-- <div class="logo_header">
                        <table class="width_100_p">
                            <tr>
                                <td style="width: 20% !important;">
                                    <img class="width_75_p"
                                        src="{{ asset('storage/setting/logo/600x600 international.png') }}">
                                </td>
                                <td>
                                    <h1 class="p_txt_1">{{ app_name() }}</h1>
                                    <h3 class="p_txt_3">Fair S Plaza, Shop- 28 & 29, 3rd Floor</h3>
                                    <h3 class="p_txt_3">Mirpur-1, Dhaka-1216</h3>
                                    <h4 class="p_txt_2">01999-577318</h4>
                                    <h3 class="p_txt_3">Email: admin@alibainternational.com</h3>
                                    <p class="inv_black">Invoice</p>
                                </td>
                                <td style="width: 20% !important;"></td>
                            </tr>
                        </table>
                    </div> --}}

                    {{-- <div class="row" style="margin-bottom: 15px">
                        <div class="col-sm-4">
                            <table class="table table-bordered table-condensed">
                                <tr>
                                    <td class="p_txt_5"> Invoice: </td>
                                    <td class="p_txt_6"> {{ $invoice->invoice_no }} </td>
                                </tr>
                                <tr>
                                    <td class="p_txt_5"> Date: </td>
                                    <td class="p_txt_6"> {{ date('M d, Y', strtotime($invoice->created_at)) }}</td>
                                </tr>
                                <tr>
                                    <td class="p_txt_5"> Payment: </td>
                                    <td class="p_txt_6">{{ $invoice->status }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-sm-4">
                        </div>
                        <div class="col-sm-4">
                            <table class="table table-bordered table-condensed">
                                <tr>
                                    <td class="p_txt_5"><b>Customer:</b></td>
                                    <td class="p_txt_6"><b>{{ $invoice->customer_name }}</b></td>
                                </tr>
                                <tr>
                                    <td class="p_txt_5"><b>Phone:</b></td>
                                    <td class="p_txt_6">{{ $invoice->customer_phone }}</td>
                                </tr>

                                <tr>
                                    <td class="p_txt_5">
                                        <b>Address:</b>
                                    </td>
                                    <td class="p_txt_6">{{ $invoice->customer_address }}</td>
                                </tr>
                            </table>
                        </div>
                    </div> --}}

                    {{-- <table class="table">
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
                            @foreach ($invoice->invoiceItems as $item)
                                @php
                                    $weight = $item->weight ? $item->weight : 0;
                                @endphp
                                <tr>
                                    <td class=" align-middle">{{ $loop->iteration }}</td>
                                    <td class=" align-middle">{{ $item->order_item_number }}</td>
                                    <td class=" align-middle">{{ $item->product_name }}</td>
                                    <td class=" align-middle">{{ $item->status }}</td>
                                    <td class="text-right align-middle">{{ floating($weight, 3) }}</td>
                                    <td class="text-right align-middle">{{ floating($item->total_due) }}</td>
                                </tr>
                                @php
                                    $actual_weight += $weight;
                                @endphp
                            @endforeach
                        </tbody>
                        <tfoot id="invoiceFooter">
                            <tr>
                                <td colspan="4" class="text-right">Total Due</td>
                                <td class="text-right"><span
                                        class="total_weight">{{ floating($actual_weight, 3) }}</span></td>
                                <td class="text-right"><span
                                        class="total_due">{{ floating($invoice->total_due) }}</span></td>
                            </tr>
                            <tr>
                                <td colspan="4" class="align-middle text-right">
                                    Courier Bill
                                </td>
                                <td class="text-center">-</td>
                                <td class="text-right"><span
                                        class="courier_bill">{{ floating($invoice->total_courier) }}</span></td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-right">Total Payable</td>
                                <td class="text-center">-</td>
                                <td class="text-right"><span class="total_payable"
                                        data-user="{{ $invoice->user_id }}">{{ floating($invoice->total_payable) }}</span>
                                </td>
                            </tr>
                        </tfoot>
                    </table> --}}
                    <table class="table ">
                        <thead>
                            <tr style="border: 1px solid;">
                                <td colspan="8" style="background-color: rgb(236, 252, 247) !important;" class="text-center">
                                    <h2><b>ALIBA INTERNATIONAL</b></h2>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="8" style="background-color: rgb(233, 135, 43) !important;" class="text-center">
                                    <h4><b>Your Trusted Import & Wholesale Partner</b></h4>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="8" style="background-color: rgb(236, 252, 247) !important;" class="text-center">
                                    <h5>Address: Fair Plaza (4th Floor, Room No: 28 & 29), Mirpur-1, Section-1, Phone:
                                        01999577318 (Whatsapp)</h5>
                                </td>
                            </tr>
                            <tr>
                                <td><b>Name</b></td>
                                <td colspan="2">{{ $invoice->customer_name }}</td>
                                <td><b>Address</b></td>
                                <td colspan="4">{{ $invoice->customer_address }}</td>
                            </tr>
                            <tr>
                                <td style="display: table-cell; vertical-align: middle;"><b>Phone</b></td>
                                <td style="display: table-cell; vertical-align: middle;" colspan="2">{{ $invoice->customer_phone }}</td>
                                <td style="display: table-cell; vertical-align: middle;"><b>BY AIR</b></td>
                                <td style="display: table-cell; vertical-align: middle;"><b>LOT NO.</b></td>
                                <td style="display: table-cell; vertical-align: middle;"></td>
                                <td style="display: table-cell; vertical-align: middle;"><b>REF:</b></td>
                                <td style="display: table-cell; vertical-align: middle;"><b>Date:</b> {{ date('d M, Y', strtotime($invoice->created_at)) }}</td>
                            </tr>
                            <tr>
                                <td colspan="8" class="text-center" style="background-color: rgb(236, 252, 247) !important;">
                                    <h4><b>INVOICE / BILL</b></h4>
                                </td>
                            </tr>
                            <tr>
                                <th class="text-center" style="display: table-cell; vertical-align: middle; width: 100px;">Date</th>
                                <th class="text-center" style="display: table-cell; vertical-align: middle;">Order No.</th>
                                <th class="text-center" style="display: table-cell; vertical-align: middle;">Weight</th>
                                <th class="text-center" style="display: table-cell; vertical-align: middle;">Rate per KG</th>
                                <th class="text-center" style="display: table-cell; vertical-align: middle;">Shipping Charge</th>
                                <th class="text-center" style="display: table-cell; vertical-align: middle;">Product Due</th>
                                <th class="text-center" style="display: table-cell; vertical-align: middle;">Total Payable</th>
                                <th class="text-center" style="display: table-cell; vertical-align: middle;">NET PAYABLE</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $i = 1;
                                $total_shipping = 0;
                                $total_due = 0;
                                $net_total = 0;
                            @endphp
                            @foreach ($invoice->invoiceItems as $item)
                                @php
                                    $net_total += $item->total_due;
                                @endphp
                            @endforeach

                            @foreach ($invoice->invoiceItems as $item)
                                <tr>
                                    @if ($i == 1)
                                        <td rowspan="0"></td>
                                    @endif
                                    <td class="text-center">{{ $item->order_item_number }}</td>
                                    <td class="text-center">{{ $item->weight }}</td>
                                    <td class="text-center">{{ $currency }} {{ $item->order_item->shipping_rate }}
                                    </td>
                                    <td class="text-center">{{ $currency }}
                                        {{ $item->order_item->shipping_charge }}</td>
                                    <td class="text-center">{{ $currency }}
                                        {{ $item->total_due - $item->order_item->shipping_charge }}</td>
                                    <td class="text-center">{{ $currency }} {{ $item->total_due }}</td>
                                    @if ($i == 1)
                                        <td class="text-center align-middle" style="display: table-cell; vertical-align: middle;" rowspan="0"><h5><b>{{ $currency }} {{ $net_total }}</b></h5></td>
                                    @endif
                                    @php
                                        $total_shipping += $item->order_item->shipping_charge;
                                        $total_due += $item->total_due - $item->order_item->shipping_charge;
                                    @endphp
                                </tr>
                                @php
                                    $i++;
                                @endphp
                            @endforeach
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="text-center"><b>Total</b></td>
                                <td class="text-center"><b>Total</b></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="text-center"><h5><b>{{ $currency }} {{ $total_shipping }}</b></h5></td>
                                <td class="text-center"><h5><b>{{ $currency }} {{ $total_due }}</b></h5></td>
                                <td></td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="8" class="text-center">
                                    <h4 style="color: red !important;">
                                       Note: This invoice is automated, no need to sign
                                    </h4>
                                </td>
                            </tr>
                            <tr>
                                <td rowspan="2" style="background-color: rgb(236, 252, 247) !important;"></td>
                                <td colspan="3" style="height: 60px;"></td>
                                <td rowspan="2" style="background-color: rgb(236, 252, 247) !important;"></td>
                                <td colspan="3"></td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-center" style="background-color: rgb(236, 252, 247) !important;"><b>Customer Signature</b></td>
                                <td colspan="3" class="text-center" style="background-color: rgb(236, 252, 247) !important;"><b>Authorized Signature</b></td>
                            </tr>
                            <tr>
                                <td colspan="8" class="text-center">
                                    <h4>
                                        Website: alibainternational.com &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Facebook: fb/alibainternational
                                    </h4>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="8" style="background-color: rgb(236, 252, 247) !important;">
                                    <p>* Please confirm the details before order. After order we are not responsible for
                                        any scam or damaged goods that are done by seller.</p>
                                    <p>* No objection will be acceptable after goods are delivered.</p>
                                    <p>* ALIBAINTERNATIONAL will not be liable or held responsible for any loss, damage
                                        or delay of goods conveyed by them due to hold customs, robbery, accidents or
                                        unfair circumstances beyond the control of the company.</p>
                                    <p>* The goods transported by us are at the owner's own risk. After the delivery of
                                        any local authority selling permission or BSTI issue we are not liable for this.
                                    </p>
                                    <p>* For suppliers payment on behalf of you that invoice only valid till supplier
                                        received payment. After payment supplier account this transaction will be closed
                                        & no further complain would be granted.</p>
                                    <p>* During shipping time any issues like customs delay, natural disaster, transport
                                        issues, government act that may cause deliverying goods delayed to our customer
                                        we are not resposible for this.</p>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="clear_both"></div>
        </div>
        {{-- @if ($invoice->status != 'Pending')
            <div style="text-align: center">
                <img style="opacity: .4;" src="{{ asset('assets/plugins/print/img/paid_seal.png') }}">
            </div>
        @endif --}}
        {{-- <footer style="margin-top: 70px;">
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
        </footer> --}}
        <div class="p_txt_16 no_print">
            <hr>
            <span class="pull-right col-xs-12">
                <button onclick="window.print();" class="btn btn-block btn-primary">Print</button> </span>
            <div class="clear_both"></div>
            {{-- <div class="p_txt_17">
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
      </div> --}}
            <div class="clear_both"></div>
        </div>
    </div>
    {{--  <script src="{{asset("assets/plugins/print/print/jquery-2.0.3.min.js") }}"></script> --}}
    {{--  <script src="{{asset('assets/plugins/print/bootstrap/dist/js/bootstrap.min.js')}}"></script> --}}
    <script src="{{ asset('assets/plugins/print/print/custom.js') }}"></script>
    {{--  <script src="{{asset("assets/plugins/print/onload_print.js") }}"></script> --}}

</body>

</html>
