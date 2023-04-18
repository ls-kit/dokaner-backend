<div class="card">
    <div class="card-header mb-2">
        <h2 class="mb-0 text-center" style="color: orange;">Orders No: #{{ $order->order_item_number }}</h2>
    </div>
    {{-- <span class="text-success">{{ $order->status }}</span> --}}

    {{-- <div class="card-body pb-0">
        <div class="row">
            <div class="col-sm-6">
                <table class="table table-bordered table-sm">
                    <tr>
                        <th colspan="2" class="text-center">Customer Details</th>
                    </tr>
                    <tr>
                        <td style="width: 50%">Name</td>
                        <td>{{ $order->user->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td>Phone</td>
                        <td>{{ $order->user->phone ?? 'N/A' }}</td>
                    </tr>
                </table>
            </div>

            <div class="col-sm-6">
                <table class="table table-bordered table-sm">
                    <tr>
                        <th colspan="2" class="text-center">Refund Details</th>
                    </tr>
                    <tr>
                        <td>Refund Method</td>
                        <td>{{ $order->user->refund_method ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td>Refund Credentials</td>
                        <td>{{ $order->user->refund_credentials ?? 'N/A' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div> --}}

    <div class="card-body">
        <div class="d-flex">
            <div>
                <img src="{{ asset($order->image) }}" style="width: 100px;">
            </div>
            <div class="ml-3">
                <h5>{{ $order->name }}</h5>
                @php
                    $product = explode('-', $order->link);
                @endphp
                <a href="https://alibainternational.com{{ $order->link }}" target="_blank" class="btn"
                    style="background-color: orange; color: white;">Alibainternational.com<i
                        class="fa fa-external-link ml-2"></i></a>
                <a href="https://detail.1688.com/offer/{{ end($product) }}.html" target="_blank" class="btn ml-2"
                    style="background-color: orange; color: white;">1688.com<i class="fa fa-external-link ml-2"></i></a>
            </div>
        </div>
        <div class="table-responsive mt-3">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 100px">#</th>
                        <th class="text-center" colspan="2">Details</th>
                        <th class="text-center" style="width:20%">Quantity</th>
                        <th class="text-center" style="width:20%">Price</th>
                        <th class="text-center" style="width:20%">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $totalItemQty = 0;
                        $totalItemPrice = 0;
                    @endphp
                    @forelse ($order->itemVariations as $variation)
                        @php
                            $attributes = json_decode($variation->attributes);
                            $attrLength = count($attributes);
                            $price = $variation->price;
                            $sinQuantity = $variation->quantity;
                            $subTotal = $variation->subTotal;
                            $totalItemQty += $sinQuantity;
                            $totalItemPrice += $subTotal;
                        @endphp
                        @forelse ($attributes as $attribute)
                            @php
                                $PropertyName = $attribute->PropertyName;
                                $Value = $attribute->Value;
                            @endphp
                            @if ($loop->first)
                                <tr>
                                    <td class="align-middle text-center" rowspan="{{ $attrLength }}">
                                        @php
                                            $variation_img = $variation->image ? $variation->image : $variation->product->MainPictureUrl ?? '';
                                        @endphp
                                        <img class="img-fluid b2bLoading" style="width: 50px;"
                                            src="{{ asset($variation_img) }}">
                                    </td>
                                    <td class="align-middle text-capitalize text-center">{!! $PropertyName !!}</td>
                                    <td class="align-middle text-center text-break" style="max-width: 120px">
                                        {{ $Value }}</td>
                                    <td class="align-middle text-center" rowspan="{{ $attrLength }}">
                                        {{ $sinQuantity }}</td>
                                    <td class="align-middle text-center text-break" rowspan="{{ $attrLength }}"
                                        style="max-width: 120px">
                                        {{ $currency }} {{ floating($price) }}</td>
                                    <td class="align-middle text-right" rowspan="{{ $attrLength }}">
                                        <span class="SingleTotal">{{ $currency }} {{ floating($subTotal) }}</span>
                                    </td>
                                </tr>
                            @else
                                <tr>
                                    <td class="text-capitalize align-middle  text-center">{!! $PropertyName !!}</td>
                                    <td class=" text-center text-break" style="max-width: 120px">{{ $Value }}
                                    </td>
                                </tr>
                            @endif
                        @empty
                            <tr>
                                <td class="align-middle text-center">
                                    @php
                                        $variation_img = $variation->image ? $variation->image : $variation->product->MainPictureUrl ?? '';
                                    @endphp
                                    <img src="{{ asset($variation_img) }}" class="img-fluid">
                                </td>
                                <td colspan="2" class="align-middle text-center">No Attribites</td>
                                <td class="align-middle text-center">{{ $sinQuantity }}</td>
                                <td class="align-middle text-center"><span
                                        class="unitPrice">{{ floating($variation->price) }}</span>
                                </td>
                                <td class="align-middle text-right">
                                    <span class="SingleTotal">{{ floating($subTotal) }}</span>
                                </td>
                            </tr>
                        @endforelse
                    @empty
                        @php
                            $totalItemPrice = $order->product_value;
                        @endphp
                        <tr>
                            <td class="align-middle text-center">
                                <img class="img-fluid b2bLoading" style="width: 50px;"
                                    src="{{ asset($order->image) }}">
                            </td>
                            <td colspan="2" class="align-middle text-center">No data</td>
                            <td class="align-middle text-center">{{ $order->quantity }}</td>
                            <td class="align-middle text-center">{{ $currency }}
                                {{ $order->product_value / $order->quantity }}</td>
                            <td class="align-middle text-center">{{ $currency }} {{ $order->product_value }}</td>
                        </tr>
                    @endforelse
                    <tr>
                        <td colspan="3"></td>
                        <td class="text-center"><b>Total Quantity: </b>{{ $order->quantity }}</td>
                        <td colspan="2"></td>
                    </tr>
                    @php
                        $totalItemPrice = $totalItemPrice + $order->chinaLocalDelivery;
                        $discount = json_decode($order->order->pay_discount);
                    @endphp
                    <tr>
                        <td class="text-right" colspan="2"><b>China Local Delivery (+)</b></td>
                        <td class="text-left">{{ $currency }}
                            <span>{{ floating($order->chinaLocalDelivery) }}</span>
                        </td>
                        <td class="text-right" colspan="2"><b>Products Value</b></td>
                        <td class="text-right">{{ $currency }} <span
                                class="totalItemPrice">{{ floating($totalItemPrice) }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-right" colspan="2"><b>Payment Method</b></td>
                        <td class="text-left">{{ $order->order->pay_method }}</td>
                        <td class="text-right" colspan="2"><b>First Payment ({{ $order->order->pay_percent }}%)</b>
                        </td>
                        <td class="text-right">{{ $currency . ' ' . floating($order->first_payment) }}</td>
                    </tr>
                    <tr>
                        <td class="text-right" colspan="2"><b>Coupon</b></td>
                        <td class="text-left">{{ $currency }} {{ $order->coupon_contribution }}</td>
                        <td class="text-center"><b>Discount {{ $discount->percent }}% :</b> {{ $currency }}
                            {{ $discount->amount / $discount->product_count }}</td>
                        <td class="text-right"><b>Total Discount (-)</b></td>
                        <td class="text-right">{{ $currency }}
                            {{ $order->coupon_contribution + $discount->amount / $discount->product_count }}</td>
                    </tr>
                    {{-- <tr>
                        <td class=" text-right" colspan="5">
                            Shipping Charge (+) <span class="text-danger">(Shipping Rate X Actual weight)</span> <br>
                            {{ $order->shipped_by . ' - ' . $currency . ' ' . floating($order->shipping_rate) }} X
                            {{ $order->actual_weight ? $order->actual_weight : '0.00' }} Kg
                        </td>
                        <td class="text-right text-danger">
                            {{ $order->shipping_charge ? $order->shipping_charge : '0.00' }} </td>
                    </tr> --}}
                    @if ($order->out_of_stock)
                        <tr>
                            <td class="text-right" colspan="5">Out Of Stock (-)</td>
                            <td class="text-right">{{ $currency . ' ' . floating($order->out_of_stock) }}</td>
                        </tr>
                    @endif
                    @if ($order->missing)
                        <tr>
                            <td class="text-right" colspan="5">Missing (-)</td>
                            <td class="text-right">{{ $currency . ' ' . floating($order->missing) }}</td>
                        </tr>
                    @endif
                    @if ($order->refunded)
                        <tr>
                            <td class="text-right" colspan="5">Refunded (-)</td>
                            <td class="text-right">{{ $currency . ' ' . floating($order->refunded) }}</td>
                        </tr>
                    @endif
                    {{-- @if ($order->adjustment)
                        <tr>
                            <td class="text-right" colspan="5">Adjustment (+-)</td>
                            <td class="text-right">{{ $currency . ' ' . floating($order->adjustment) }}</td>
                        </tr>
                    @endif --}}
                    @if ($order->courier_bill)
                        <tr>
                            <td class="text-right" colspan="5">Courier Bill (+)</td>
                            <td class="text-right">{{ $currency . ' ' . floating($order->courier_bill) }}</td>
                        </tr>
                    @endif

                    @if ($order->coupon_contribution)
                        <tr>
                            <td class="text-right" colspan="5">Coupon (-)</td>
                            <td class="text-right">{{ $currency . ' ' . floating($order->coupon_contribution) }}</td>
                        </tr>
                    @endif

                    @if ($order->due_payment)
                        <tr>
                            <td class="text-right text-success" colspan="2">Status</td>
                            <td class="text-left text-success">{{ $order->status }}</td>
                            <td class="text-right" colspan="2"><b>PRODUCT DUE</b></td>
                            <td class="text-right">{{ $currency . ' ' . floating($order->due_payment) }}</td>
                        </tr>
                    @endif

                    <tr>
                        <td class="text-right" colspan="4"><b>Shipping Per KG <span style="color: orange;">(Total
                                    Weight x Shipping Fee)</span></b></td>
                        <td class="text-right"><b class="">
                                <form action="{{ route('admin.order.shipping-rate', $order->id) }}" method="POST"
                                    id="shippingRateForm">
                                    @method('PUT')
                                    @csrf
                                    <span class="col-md-7">
                                        {{ $order->actual_weight ? $order->actual_weight : 0 }} KG &nbsp; x
                                    </span>
                                    <input class="col-md-5" style="border: 1px solid orange; border-radius: 2px;"
                                        type="number" name="shipping_rate" value="{{ $order->shipping_rate }}"
                                        id="rate">
                                    <input type="text" name="item_id" value="{{ $order->id }}" hidden>
                                    <button class="btn btn-sm btn-success mt-2">Update</button>
                                </form>
                            </b></td>
                        @php
                            $shipping_charge = ($order->actual_weight ? $order->actual_weight : 0) * $order->shipping_rate;
                        @endphp
                        <td class="text-right">{{ $currency . ' ' . floating($shipping_charge) }}</td>
                    </tr>

                    <tr>
                        <td class="text-right" colspan="4"><b>Adjustment (+-)</b></td>
                        <td class="text-right">
                            <form action="{{ route('admin.order.adjustment', $order->id) }}" method="POST"
                                id="adjustmentForm">
                                @method('PUT')
                                @csrf
                                <input class="col-md-5" style="border: 1px solid orange; border-radius: 2px;"
                                    type="number" name="adjustment" value="{{ $order->adjustment }}"
                                    id="rate">
                                <input type="text" name="item_id" value="{{ $order->id }}" hidden>
                                <div>
                                    <button class="btn btn-sm btn-success mt-2">Update</button>
                                </div>
                            </form>
                        </td>
                        <td class="text-right">{{ $currency . ' ' . floating($order->adjustment) }}</td>
                    </tr>

                    <tr style="background-color: orange;">
                        <td class="text-right" colspan="5">
                            <h4>NET DUE</h4>
                        </td>
                        @php
                            $total = $shipping_charge + $order->due_payment;
                        @endphp
                        <td class="text-right">
                            <h4>{{ $currency . ' ' . floating($total) }}</h4>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div> <!-- table-responsive -->

    </div> <!-- card-body-->
</div> <!-- card-->

<script src="https://code.jquery.com/jquery-3.6.1.min.js"
    integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
<script>
    $('#shippingRateForm').submit(function(e) {
        e.preventDefault();
        let rate = $(this).children('input[name=shipping_rate]').val();
        let item = $(this).children('input[name=item_id]').val();

        $.ajax({
            type: "PUT",
            url: "{{ route('admin.order.shipping-rate', ' . item . ') }}",
            data: {
                _token: "{{ csrf_token() }}",
                shipping_rate: rate,
                item_id: item
            },
            dataType: "dataType",
            success: function(response) {
                console.log('hi');
            }
        });
    });

    $('#adjustmentForm').submit(function(e) {
        e.preventDefault();
        let rate = $(this).children('input[name=adjustment]').val();
        let item = $(this).children('input[name=item_id]').val();

        $.ajax({
            type: "PUT",
            url: "{{ route('admin.order.adjustment', ' . item . ') }}",
            data: {
                _token: "{{ csrf_token() }}",
                adjustment: rate,
                item_id: item
            },
            dataType: "dataType",
            success: function(response) {
                console.log('hi');
            }
        });
    });
</script>
