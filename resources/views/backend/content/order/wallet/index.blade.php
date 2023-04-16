@extends('backend.layouts.app')

@section('title', 'Manage Customer Wallet')

@php
    $options = [
        'purchased' => 'Purchased',
        'shipped-from-suppliers' => 'Shipped from Suppliers',
        'received-in-china-warehouse' => 'Received in China Warehouse',
        'shipped-from-china-warehouse' => 'Shipped from China Warehouse',
        'received-in-BD-warehouse' => 'Received in BD Warehouse',
        'BD-customs' => 'BD Customs',
        'ready-to-deliver' => 'Ready to Deliver',
        'on-transit-to-customer' => 'On Transit to Customer',
        'out-of-stock' => 'Out of Stock',
        'adjustment' => 'Adjustment',
        'refunded' => 'Refunded',
        'delivered' => 'Delivered',
        'Waiting for Payment' => 'Waiting for Payment',
        'Partial Paid' => 'Partial Paid',
    ];
@endphp

@section('styles')
    <style>
        .sticky {
            position: sticky;
            background-color: white;
        }

        .sticky1 {
            left: 0;
        }

        .sticky2 {
            left: 100px;
        }

        .sticky3 {
            left: 220px;
        }

        .sticky4 {
            left: 343px;
        }

        .sticky5 {
            left: 443px;
        }

        .sticky6 {
            left: 563px;
        }

        .sticky7 {
            left: 683px;
        }

        .sticky8 {
            left: 840px;
        }
    </style>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-2">
                    <h4 class="my-1">@lang('Manage Wallet')</h4>
                </div> <!-- col-->
                <div class="col-md-8">
                    <form id="filterWalletForm">
                        <div class="form-row">
                            <div class="col-md-4">
                                {{ html()->select('customer', $findable, request('customer'))->class('form-control mr-sm-2 select2')->attribute('maxlength', 255) }}
                            </div>
                            <div class="col-md-8">
                                @php
                                    $requ_status = explode(',', request('status'));
                                @endphp
                                <div class="input-group">
                                    {{ html()->multiselect('findStatus[]', $options, $requ_status)->class('form-control mb-2 mr-sm-2 select2')->id('findStatus')->attributes(['data-placeholder' => 'Select a Status']) }}
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-info findResultButton" data-toggle="tooltip"
                                            title="@lang('Search')">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form> <!-- form-inline -->
                </div> <!-- col-->
                <div class="col-md-2 text-right">
                    <div class="btn-group" role="group" aria-label="header_button_group">
                        <button type="button" class="btn btn-primary" id="changeGroupStatusButton" data-toggle="tooltip"
                            title="@lang('Change Status')" disabled="true">
                            @lang('Status')
                        </button>
                        <button type="button" class="btn btn-danger" id="generateInvoiceButton" data-toggle="tooltip"
                            title="Generate Invoice" disabled="true">
                            @lang('Generate')
                        </button>
                        <a href="{{ route('admin.export', 'order_item') }}" class="btn btn-warning" data-toggle="tooltip"
                            title="Full Export">
                            <i class="fa fa-download"></i>
                        </a>
                    </div> <!-- btn-group-->
                </div> <!-- col-->
            </div> <!-- row-->
        </div>
        <div class="card-body p-0">

            @livewire('wallet-table', ['status' => request('status'), 'customer' => request('customer')])

        </div> <!-- card-body-->
    </div> <!-- card-->


    <div class="modal fade" id="changeStatusButton" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form method="POST" action="{{ route('admin.order.store') }}" id="statusChargeForm">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalCenterTitle">Change Status <span class="orderId"></span>
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="hiddenField">
                            {{-- hidden input field append here --}}
                        </div>

                        <div class="form-group">
                            @php
                                unset($options['Waiting for Payment'], $options['Partial Paid']);
                            @endphp
                            {{ html()->select('status', $options)->class('form-control')->attribute('maxlength', 255)->required() }}
                        </div> <!--  form-group-->

                        <div class="form-group" id="additionInputStatusForm">

                        </div> <!-- additionInputStatusForm -->

                        <div class="form-group form-check">
                            <input type="checkbox" name="notify" value="1" class="form-check-input" id="notify"
                                checked="true">
                            <label class="form-check-label" for="notify">Notify User</label>
                        </div>

                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="submit" class="btn btn-primary" id="statusSubmitBtn">Save changes</button>
                    </div>

                </form>
            </div>
        </div>
    </div> <!-- changeStatusButton -->


    <div class="modal fade" id="generateInvoiceModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog  modal-xl" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Generate Invoice</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="hiddenField">
                        {{-- hidden input field append here --}}
                    </div>

                    <table class="table table-bordered text-center">
                        <thead>
                            <tr>
                                <th class="align-middle" scope="col">SL</th>
                                <th class="align-middle" scope="col">Item No.</th>
                                <th class="align-middle" colspan="3" scope="col" class="text-left">Product</th>
                                <th class="align-middle" scope="col">Status</th>
                                {{-- <th class="align-middle" scope="col">Weight</th> --}}
                                <th class="text-right align-middle" scope="col">Due</th>
                            </tr>
                        </thead>
                        <tbody id="invoiceItem"></tbody>
                        <tfoot id="invoiceFooter">
                            {{-- <tr>
              <td colspan="3" class="text-right">Total Due</td>
              <td class="text-right"><span class="total_weight">0.000</span></td>
              <td class="text-right"><span class="total_due">0.00</span></td>
            </tr> --}}
                            <tr>
                                <td colspan="6" class="align-middle text-right">
                                    <div class="row">
                                        <div class="col">
                                            @php
                                                $payment_method = [
                                                    '' => '- Payment Method -',
                                                    'Cash' => 'Cash',
                                                    'Bkash' => 'Bkash',
                                                    'Cash on Delivery' => 'Cash on Delivery',
                                                    'Bank' => 'Bank',
                                                    'sslcommerz' => 'SSLcommerz',
                                                    'Nagod' => 'Nagod',
                                                    'Rocket' => 'Rocket',
                                                    'others' => 'Others',
                                                ];
                                            @endphp
                                            {{ html()->select('payment_method', $payment_method)->class('form-control')->required() }}
                                        </div>
                                        <div class="col">
                                            @php
                                                $delivery_method = [
                                                    '' => '- Delivery Method -',
                                                    'office_delivery' => 'Office Delivery',
                                                    'sundarban' => 'Sundarban',
                                                    'sa_poribohon' => 'SA Poribohon',
                                                    'papperfly' => 'Papperfly',
                                                    'pathao' => 'Pathao',
                                                    'tiger' => 'Tiger',
                                                    'others' => 'Others',
                                                ];
                                            @endphp
                                            {{ html()->select('delivery_method', $delivery_method)->class('form-control')->required() }}
                                        </div>
                                        <div class="col">
                                            <p class="courier_bill_text m-0" style="display: none">
                                                Courier Bill <a href="#"
                                                    class="ml-3 removeCourierBtn text-danger">Remove</a>
                                            </p>
                                            <div class="input-group courierSubmitForm">
                                                <input type="text" class="form-control" placeholder="Courier Bill"
                                                    aria-label="Courier Bill" aria-describedby="Courier-addon2">
                                                <div class="input-group-append applyCourierBtn" style="cursor: pointer">
                                                    <span class="input-group-text" id="Courier-addon2">Apply</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div> <!-- row -->
                                </td>
                                {{-- <td class="text-center">-</td> --}}
                                <td class="text-right"><span class="courier_bill">0.00</span></td>
                            </tr>
                            <tr>
                                <td colspan="6" class="text-right"><b>Total Payable</b></td>
                                {{-- <td class="text-center">-</td> --}}
                                <td class="text-right"><span class="total_payable">0.00</span></td>
                            </tr>
                        </tfoot>
                    </table>

                    <div class="form-group form-check">
                        <input type="checkbox" name="notify" value="1" class="form-check-input" id="notifyUser"
                            checked="true">
                        <label class="form-check-label" for="notifyUser">Notify User</label>
                    </div>
                </div>
                <div class="modal-footer justify-content-start">
                    <button type="submit" class="btn btn-success" data-action="{{ route('admin.invoice.store') }}"
                        id="generateSubmitBtn">Generate
                    </button>
                    <button type="button" data-dismiss="modal" class="btn btn-primary">Cancel</button>
                </div>

            </div>
        </div>
    </div> <!-- changeStatusButton -->


@endsection


@push('before-styles')
    {{ style('assets/plugins/select2/css/select2.min.css') }}
    {{-- {{style('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}} --}}
@endpush

@push('after-styles')
    @livewireStyles
@endpush

@push('middle-scripts')
    {{ script('assets/plugins/select2/js/select2.full.min.js') }}
    @livewireScripts

    {!! script('assets/js/manage-wallet.js') !!}
@endpush
