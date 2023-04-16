@extends('frontend.layouts.app')

@section('title', 'Order Item Details' )

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
              <h3>Invoice Id #{{$invoice->invoice_no}} | <span class="text-success">{{readable_status($invoice->status)}}</span></h3>
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
                      <td class=" align-middle">{{$item->status}}</td>
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
                      <td class="text-right"><span class="courier_bill">{{floating($invoice->total_courier)}}</span></td>
                    </tr>
                    <tr>
                      <td colspan="4" class="text-right">Total Payable</td>
                      <td class="text-center">-</td>
                      <td class="text-right"><span class="total_payable"
                          data-user="4">{{floating($invoice->total_payable)}}</span>
                      </td>
                    </tr>
                  </tfoot>
                </table>
              </div> <!-- table-responsive -->
            </div> <!-- card-body -->
          </div> <!-- .card -->
        </div> <!-- .card -->
      </div> <!-- col-lg-9 -->
    </div> <!-- row-->
  </div>
</div>
</div> <!-- END MAIN CONTENT -->
@endsection
