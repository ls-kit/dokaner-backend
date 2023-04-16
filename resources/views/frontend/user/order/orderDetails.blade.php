@extends('frontend.layouts.app')

@section('title', 'Order Item Details')

@php
    $currency = get_setting('currency_icon');
    $productLoader = get_setting('product_image_loader');
@endphp
@section('content')
    <div class="main_content">
        <div class="section pb-5">
            <div class="container">
                <div class="justify-content-around row">
                    {{-- <div class="col-md-9"> --}}
                    <div class='page-content'>
                        <div class='container'>
                            <div class='row justify-content-center'>
                                <aside class='col-md-12'>

                                    <div class='card bg-white'>
                                        <div class='card-body'>
                                            <div id='exportOrder'>
                                                <div class='row'>
                                                    <div class='col-md-6'>
                                                        <p class='m-0'>
                                                            <b>CUSTOMER</b>
                                                        </p>
                                                        <p class='m-0'>Invoice No: #{{ $order->order_number }}</p>
                                                        <p class='m-0'>Name: {{ $order->name }}</p>
                                                        <p class='m-0'>Email: {{ $order->email }}</p>
                                                        <p class='m-0'>Phone: {{ $order->phone }}</p>
                                                        {{-- <p class='m-0'>Address: </p> --}}
                                                    </div>
                                                    <div class='col-md-6 text-md-right'>
                                                        <p class='m-0'>
                                                            <b>
                                                            </b>
                                                        </p>
                                                        <p class='m-0'>
                                                            <span class='mr-2'>Method: {{ $order->pay_method }}</span>
                                                        </p>
                                                        <p class='m-0'>
                                                            <span class='mr-2'></span>
                                                        </p>
                                                        @php
                                                            $trxId = json_decode($order->trxId);
                                                        @endphp
                                                        @isset($trxId->payment_1st)
                                                            <p class='m-0'>Transaction Num (Initial):
                                                                {{ $trxId->payment_1st }}</p>
                                                            <p class='m-0'>Transaction Num (Final): {{ $trxId->payment_2nd }}
                                                            </p>
                                                        @endisset
                                                        @if (!isset($trxId->payment_1st))
                                                            <p class='m-0'>Transaction Num: {{ $order->trxId }}</p>
                                                        @endif
                                                        <p class='m-0'>Reference Number: {{ $order->refNumber }}</p>
                                                    </div>
                                                </div>
                                                <hr />
                                                <table class='table table-responsive table-bordered table-cart'>
                                                    <thead>
                                                        <tr>
                                                            <th>Product</th>
                                                            <th class='text-right minw-td'>Total</th>
                                                            <th class='text-right'>Pay&Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($order->orderItems as $item)
                                                            <tr>
                                                                <td>
                                                                    <div class='d-flex'>
                                                                        <div class='mr-2'>
                                                                            <span class='bold'>OrderNo:</span> <br />
                                                                            <span
                                                                                class='text-danger'>{{ $item->order_item_number }}</span>
                                                                        </div>
                                                                        <div class='mt-1'>
                                                                            <img src="{{ $item->image }}"
                                                                                style="width: 50px" />
                                                                        </div>
                                                                        <div class='ml-2'>
                                                                            <span>{{ $item->name }}</span>
                                                                        </div>
                                                                    </div>
                                                                    <div>
                                                                        @foreach ($item->itemVariations as $variation)
                                                                            @php
                                                                                $attributes = json_decode($variation->attributes);
                                                                            @endphp
                                                                            <div class='row'
                                                                                style="borderTop: 1px solid lightGray">
                                                                                @foreach ($attributes as $attribute)
                                                                                    {{-- <div class='col-md-5 Attributes fs-13 '> --}}
                                                                                    {{-- <div class='row'> --}}
                                                                                    <div class='col-md-2 plain-attribute'>
                                                                                        <b>{{ $attribute->PropertyName }}:</b>
                                                                                        <span>{{ $attribute->Value }}</span>
                                                                                    </div>
                                                                                    {{-- </div> --}}
                                                                                    {{-- </div> --}}
                                                                                @endforeach

                                                                                <div class='col-md-2 plain-attribute'>
                                                                                    <b>Quantity :</b>
                                                                                    <span>{{ $variation->quantity }}</span>
                                                                                </div>

                                                                                <div class='col-md-2 plain-attribute'>
                                                                                    <b>Price :</b>
                                                                                    <span>{{ $currency }}
                                                                                        {{ $variation->price }}</span>
                                                                                </div>
                                                                                <div class='col-md-3 plain-attribute'>
                                                                                    <b>SubTotal :</b>
                                                                                    <span>{{ $currency }}
                                                                                        {{ $variation->subTotal }}</span>
                                                                                </div>
                                                                            </div>
                                                                            <div class='row'
                                                                                style="borderTop: 1px solid lightGray">
                                                                            </div>
                                                                        @endforeach

                                                                    </div>
                                                                </td>

                                                                <td class='text-right'>
                                                                    <div>
                                                                        <span class='bold'>Shipping charge:
                                                                            @isset($item->actual_weight)
                                                                                {{ $currency }}
                                                                                {{ $item->actual_weight * $item->shipping_rate }}
                                                                            @endisset
                                                                        </span>
                                                                        <span class='d-block'>
                                                                            Rate(KG): {{ $currency }}
                                                                            {{ $item->shipping_rate }}
                                                                        </span>
                                                                        @isset($item->actual_weight)
                                                                            <span class='d-block'>Weight:
                                                                                {{ $item->actual_weight }}KG</span>
                                                                        @endisset
                                                                    </div>
                                                                    <div style="borderTop: 1px solid lightGray">
                                                                        <span class='bold'>Quantity:
                                                                            {{ $item->quantity }}</span>
                                                                    </div>
                                                                    <div>
                                                                        <span class='bold'>Amount:
                                                                            {{ $currency }}
                                                                            {{ $item->product_value + $item->chinaLocalDelivery }}</span>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class='text-right '></div>
                                                                    <div>
                                                                        <div class='text-right '>
                                                                            {{ $item->status }}
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @endforeach

                                                        <tr>
                                                            <td colSpan="3">
                                                                <h3 class='border-0 m-0 py-3 summary-title'>Cart Total
                                                                    Summary
                                                                </h3>
                                                            </td>
                                                        </tr>
                                                        <tr class='summary-total'>
                                                            <td colSpan="2" class='text-md-right'>
                                                                Subtotal:
                                                            </td>
                                                            <td class='text-right'>{{ $currency . ' ' . $order->amount }}
                                                            </td>
                                                        </tr>
                                                        <tr class='summary-total'>
                                                            <td colSpan="2" class='text-md-right'>
                                                                Need To Pay:
                                                            </td>
                                                            <td class='text-right'>
                                                                {{ $currency . ' ' . $order->needToPay }}
                                                            </td>
                                                        </tr>
                                                        <tr class='summary-total'>
                                                            <td colSpan="2" class='text-md-right'>
                                                                Due Amount:
                                                            </td>
                                                            <td class='text-right'>
                                                                {{ $currency . ' ' . $order->dueForProducts }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </aside>
                            </div>
                        </div>
                    </div>
                    {{-- </div> <!-- .card --> --}}
                </div> <!-- col-lg-9 -->
            </div> <!-- row-->
        </div>
    </div>
    </div> <!-- END MAIN CONTENT -->
@endsection
