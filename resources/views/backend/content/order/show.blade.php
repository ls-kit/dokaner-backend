@php
    $currency = currency_icon();
@endphp

<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>@lang('Order Details') | {{ $order->order_number }} | {{ $order->user->name ?? 'N/A' }} </title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        body {
            font-family: Source Sans Pro, Helvetica Neue, Helvetica, Arial, sans-serif;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <main class="container-fluid">
        <div class="row">
            <div class="col-sm-12">

                <div class="card my-3">
                    <div class="card-header">
                        <h4 class="my-2">@lang('Order Details') #{{ $order->order_number }}</h4>
                    </div> <!-- card-header -->
                    <div class="card-body pb-0">
                        <div class="row">
                            <div class="col-sm-6">
                                <table class="table table-bordered table-sm">
                                    <tr>
                                        <th colspan="2" class="text-center">Customer Details</th>
                                    </tr>
                                    <tr>
                                        <td>Customer Name</td>
                                        <td>{{ $order->user->name ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td style="width: 50%">Customer Email</td>
                                        <td>{{ $order->user->email }}</td>
                                    </tr>
                                    @php
                                        $trxId = json_decode($order->trxId);
                                        $discount = json_decode($order->pay_discount);
                                    @endphp
                                    <tr>
                                        <td>Trx ID#</td>
                                        <td>
                                            @if (isset($trxId->payment_1st))
                                                1st Payment: {{ $trxId->payment_1st }} <br>
                                                2nd Payment: {{ $trxId->payment_2nd }}
                                            @else
                                                {{ $order->trxId }}
                                            @endif
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>Payment Method</td>
                                        <td>{{ $order->pay_method ?? 'N/A' }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-sm-6">
                                <table class="table table-bordered table-sm">
                                    <tr>
                                        <th colspan="2" class="text-center">Shipping Details</th>
                                    </tr>
                                    @php
                                        $address = json_decode($order->address) ?? null;
                                    @endphp
                                    <tr>
                                        <td style="width: 50%">Shipping Name</td>
                                        <td>{{ $address ? $address->name : 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Phone</td>
                                        <td>{{ $address->phone_one ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td>District</td>
                                        <td>{{ $address->phone_three ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Address</td>
                                        <td>{{ $address->address ?? 'N/A' }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 130px;">#</th>
                                        <th class="text-center" colspan="2">Details</th>
                                        <th class="text-center" style="width:80px">Quantity</th>
                                        <th class="text-center" style="width:100px">Total</th>
                                        <th class="text-center" style="width:100px">1688.com</th>
                                        <th class="text-center" style="width:100px">Order Date</th>
                                        <th class="text-center" style="width:100px">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $invoiceTotal = 0;
                                    @endphp
                                    @foreach ($order->orderItems as $item)
                                        <tr>
                                            <td class="text-left" colspan="9">
                                                <span style="font-size: 16px;"
                                                    class="text-danger">{{ $item->order_item_number }}</span> / <a
                                                    href="https://1688cart.com{{ $item->link }}">{{ strip_tags($item->name) }}</a>
                                            </td>
                                        </tr>
                                        @php
                                            $itemTotalPrice = 0;
                                        @endphp

                                        @foreach ($item->itemVariations as $variationKey => $variation)
                                            @php
                                                $attributes = json_decode($variation->attributes);
                                                $attrLength = count($attributes) + 1;
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
                                                        <td class="align-middle text-center"
                                                            rowspan="{{ $attrLength }}">
                                                            @php
                                                                $variation_img = $variation->image ? $variation->image : $variation->product->MainPictureUrl ?? '';
                                                            @endphp
                                                            <img src="{{ asset($variation_img) }}" class="img-fluid">
                                                        </td>
                                                        <td class="text-capitalize text-center">{!! $PropertyName !!}
                                                        </td>
                                                        <td class="align-middle text-center">{{ $Value }}</td>
                                                        <td class="align-middle text-center"
                                                            rowspan="{{ $attrLength }}"> {{ $sinQuantity }}</td>
                                                        <td class="align-middle text-right"
                                                            rowspan="{{ $attrLength }}">
                                                            <span class="SingleTotal">{{ floating($subTotal) }}</span>
                                                        </td>
                                                        @if ($variationKey === 0)
                                                            @php
                                                                $LengthTotal = count($item->itemVariations) * $attrLength + 4;
                                                            @endphp
                                                            <td class="align-middle text-center"
                                                                rowspan="{{ $LengthTotal }}">
                                                                @php
                                                                    $product_id = $item->product ? $item->product->ItemId : '';
                                                                    $product = explode('-', $product_id);
                                                                @endphp
                                                                <a href="https://detail.1688.com/offer/{{ end($product) }}.html"
                                                                    class="btn btn-sm btn-secondary"
                                                                    target="_blank">Click</a>
                                                            </td>
                                                            <td class="align-middle text-center"
                                                                rowspan="{{ $LengthTotal }}">
                                                                <p class="m-0">
                                                                    {{ date('d M, Y', strtotime($item->created_at)) }}
                                                                </p>
                                                                <p class="m-0">at
                                                                    {{ date('h:ia', strtotime($item->created_at)) }}
                                                                </p>
                                                            </td>
                                                            <td class="align-middle text-center"
                                                                rowspan="{{ $LengthTotal }}">
                                                                <span
                                                                    class="singleStatus text-capitalize">{{ $item->status }}</span>
                                                            </td>
                                                        @endif
                                                    </tr>
                                                @else
                                                    <tr>
                                                        <td class="text-capitalize  text-center">{!! $PropertyName !!}
                                                        </td>
                                                        <td class=" text-center">{{ $Value }}</td>
                                                    </tr>
                                                @endif
                                            @empty
                                                <tr>
                                                    <td class="align-middle text-center" rowspan="2">
                                                        @php
                                                            $variation_img = $variation->image ? $variation->image : $variation->product->MainPictureUrl ?? '';
                                                        @endphp
                                                        <img src="{{ asset($variation_img) }}" class="img-fluid">
                                                    </td>
                                                    <td colspan="2" class="align-middle text-center">No Attributes
                                                    </td>
                                                    <td class="align-middle text-center" rowspan="2">
                                                        {{ $sinQuantity }}</td>
                                                    <td class="align-middle text-right" rowspan="2">
                                                        <span class="SingleTotal">{{ floating($subTotal) }}</span>
                                                    </td>
                                                    <td class="align-middle text-center" rowspan="5">
                                                        @php
                                                            $product_id = $item->product ? $item->product->ItemId : '';
                                                            $product = explode('-', $product_id);
                                                            $p = $product[1];
                                                        @endphp
                                                        <a href="https://detail.1688.com/offer/{{ $p }}.html"
                                                            class="btn btn-sm btn-secondary" target="_blank">Click</a>
                                                    </td>
                                                    <td class="align-middle text-center" rowspan="5">
                                                        {{ date('d M, Y', strtotime($item->created_at)) }}
                                                    </td>
                                                    <td rowspan="5"></td>
                                                </tr>
                                            @endforelse
                                            <tr>
                                                <td class="text-right">Per unit Price</td>
                                                <td class="text-right">
                                                    <span class="unitPrice">{{ floating($variation->price) }}</span>
                                                </td>
                                            </tr>
                                        @endforeach

                                        @if ($itemTotalPrice == 0)
                                            <tr>
                                                <td class="align-middle text-center" rowspan="2">
                                                    <img src="{{ asset($item->image) }}" class="img-fluid">
                                                </td>
                                                <td colspan="2" class="align-middle text-center">No Attributes
                                                </td>
                                                <td class="align-middle text-center" rowspan="2">
                                                    {{ $item->quantity }}</td>
                                                <td class="align-middle text-right" rowspan="2">
                                                    <span class="SingleTotal">{{ $item->product_value }}</span>
                                                </td>
                                                <td class="align-middle text-center" rowspan="5">
                                                    @php
                                                        $product_id = $item->link;
                                                        $product = explode('-', $product_id);
                                                        $p = $product[1];
                                                    @endphp
                                                    <a href="https://detail.1688.com/offer/{{ $p }}.html"
                                                        class="btn btn-sm btn-secondary" target="_blank">Click</a>
                                                </td>
                                                <td class="align-middle text-center" rowspan="5">
                                                    {{ date('d M, Y', strtotime($item->created_at)) }}
                                                </td>
                                                <td rowspan="5"></td>
                                            </tr>
                                            <tr>
                                                <td class="text-right">Per unit Price</td>
                                                <td class="text-right">
                                                    <span
                                                        class="unitPrice">{{ floating($item->product_value / $item->quantity) }}</span>
                                                </td>
                                                <td class="align-middle text-center" rowspan="1">
                                                    <span
                                                        class="singleStatus text-capitalize">{{ $item->status }}</span>
                                                </td>
                                            </tr>
                                            @php
                                                $itemTotalPrice += $item->product_value;
                                            @endphp
                                        @endif

                                        @php
                                            $chinaLocalDelivery = $item->chinaLocalDelivery;
                                        @endphp
                                        <tr>
                                            <td class="text-right" colspan="3">China Local Delivery</td>
                                            <td class="text-center">-</td>
                                            <td class="text-right"><span>{{ floating($chinaLocalDelivery) }}</span>
                                            </td>
                                        </tr>

                                        @php
                                            $coupon_contribution = $item->coupon_contribution;
                                        @endphp
                                        @if ($coupon_contribution)
                                            <tr>
                                                <td class="text-right" colspan="3">Coupon (-)</td>
                                                <td class="text-center align-middle">-</td>
                                                <td class="text-right"><span
                                                        class="totalItemPrice">{{ floating($coupon_contribution) }}</span>
                                                </td>
                                            </tr>
                                        @endif

                                        <tr>
                                            <td class="text-right" colspan="3"> Shipping Rate (Per KG)
                                                <form action="{{ route('admin.order.shipping-rate', $item->id) }}"
                                                    method="POST" id="">
                                                    @method('PUT')
                                                    @csrf
                                                    <input class="col-md-4 offset-md-8 form-control mt-2"
                                                        type="number" name="shipping_rate"
                                                        value="{{ $item->shipping_rate }}" id="rate">
                                                    <input type="text" name="item_id" value="{{ $item->id }}"
                                                        hidden>
                                                    <button class="btn btn-md btn-success mt-2">Update</button>
                                                </form>
                                                {{-- <p class="m-0 text-danger">Shipping Method {{ $item->shipped_by . ' - ' . floating($item->shipping_rate) }} Per KG</p> --}}
                                                {{-- <p class="m-0 text-danger">Approx weight - {{ $item->actual_weight ? $item->actual_weight : 0 }} KG</p> --}}
                                            </td>
                                            <td class="text-center align-middle">KG: {{ $item->actual_weight }}</td>
                                            <td class="text-right align-middle">
                                                <span>Shipping Cost
                                                    {{ $item->shipping_rate * $item->actual_weight }}</span>
                                            </td>
                                        </tr>
                                        @php
                                            if ($discount->percent != 0) {
                                                $amount = $discount->amount / $discount->product_count;
                                            } else {
                                                $amount = 0;
                                            }

                                            $itemTotalPrice = $itemTotalPrice + $chinaLocalDelivery + $item->shipping_charge - $amount;
                                            $invoiceTotal += $itemTotalPrice;
                                        @endphp

                                        @if ($discount->percent != 0)
                                            <tr>
                                                <td class="text-right" colspan="3">Discount</td>
                                                <td class="text-center">-</td>
                                                <td class="text-right"><span
                                                        class="totalItemPrice">{{ $discount->amount / $discount->product_count }}</span>
                                                </td>
                                            </tr>
                                        @endif
                                        <tr>
                                            <td class="text-right" colspan="3">Sub Total</td>
                                            <td class="text-center">{{ $item->quantity }}</td>
                                            <td class="text-right"><span
                                                    class="totalItemPrice">{{ floating($itemTotalPrice) }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <table class="table table-bordered table-striped">
                                <tr>
                                    <td class="text-right" colspan="5">Products Price</td>
                                    <td class="text-right">{{ $order->amount }}</td>
                                </tr>
                                @if ($order->pay_discount)
                                    <tr>
                                        <td class="text-right" colspan="5">Discount ({{ $discount->percent }}%)
                                        </td>
                                        <td class="text-right">{{ $discount->amount }}</td>
                                    </tr>
                                @endif
                                @if ($order->coupon_victory)
                                    <tr>
                                        <td class="text-right" colspan="5">Coupon</td>
                                        <td class="text-right">{{ floating($order->coupon_victory) }}</td>
                                    </tr>
                                @endif

                                <tr>
                                    <td class="text-right" colspan="5">Initial Payment (After Discount)
                                        ({{ $order->pay_percent }}%)</td>
                                    <td class="text-right">{{ $order->needToPay }}</td>
                                </tr>
                                <tr>
                                    <td class="text-right text-danger" colspan="5">Due (Only for products)</td>
                                    <td class="text-right text-danger">{{ $order->dueForProducts }}</td>
                                </tr>
                            </table>

                            @if ($order->status == 'waiting-for-payment')
                                <form action="{{ route('admin.order.makeAsPayment', $order) }}" method="POST"
                                    id="approve_initial">
                                    @csrf
                                    @method('GET')
                                    <button class="btn btn-info float-right">Approve Initial Payment</button>
                                </form>
                            @endif

                            @if ($order->status == 'partial-paid')
                                <form action="{{ route('admin.order.makeAsFullPayment', $order) }}" method="POST"
                                    id="approve_full">
                                    @csrf
                                    @method('GET')
                                    <button class="btn btn-info float-right">Approve Full Payment</button>
                                </form>
                            @endif
                        </div>
                    </div> <!-- card-body -->
                </div> <!-- card -->
            </div>
        </div>
    </main>

    <script src="https://code.jquery.com/jquery-3.6.1.min.js"
        integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
    <script>
        $('form').submit(function(e) {
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

        $('#approve_initial').submit(function(e) {
            e.preventDefault();

            Swal.fire({
                    title: 'Are you sure you want to approve Initial Payment??',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#218838',
                    cancelButtonColor: '#5a6268',
                    confirmButtonText: 'Yes, approve!'
                })
                .then((result) => {
                    if (result.value) {
                        Swal.fire(
                            'Approved!',
                            'Initial payment approved!',
                            'success'
                        )
                        $(this).submit();
                    }
                })
        });

        $('#approve_full').submit(function(e) {
            e.preventDefault();

            Swal.fire({
                    title: 'Are you sure you want to approve Full Payment??',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#218838',
                    cancelButtonColor: '#5a6268',
                    confirmButtonText: 'Yes, approve!'
                })
                .then((result) => {
                    if (result.value) {
                        Swal.fire(
                            'Approved!',
                            'Full payment approved!',
                            'success'
                        )
                        $(this).submit();
                    }
                })
        });
    </script>
</body>

</html>
