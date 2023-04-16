@extends('backend.layouts.app')

@section('title', 'Trashed Invoice')

@section('content')
<div class="card">
  <div class="card-body">
    <div class="row">
      <div class="col-sm-5">
        <h4 class="card-title mb-0">
          Trashed Invoice
        </h4>
      </div> <!-- col-->
      <div class="col-sm-7 pull-right">
        <div class="btn-toolbar float-right" role="toolbar" aria-label="@lang('labels.general.toolbar_btn_groups')">
          <a href="{{ route('admin.invoice.index') }}" class="btn btn-success ml-1" data-toggle="tooltip"
            title="Show Orders"><i class="fa fa-list-alt"></i></a>
        </div>
      </div> <!-- col-->
    </div> <!-- row-->

    <div class="row mt-4">
      <div class="col">
        <div class="table-responsive">
          <table class="table table-striped text-center">
            <thead>
              <tr class="vertical-middle">
                <th style="width:105px" class="text-center">Transaction</th>
                <th style="width:120px" class="text-center">Customer Name</th>
                <th style="width:120px" class="text-center">Customer Phone</th>
                <th style="width:120px" class="text-center">Total Due</th>
                <th style="width:102px" class="text-center">Total Courier</th>
                <th style="width:100px" class="text-center">Total Payable</th>
                <th style="width:90px" class="text-center">Status</th>
                <th style="width:110px" class="text-center">Delete Date</th>
                <th style="width:105px" class="text-center">@lang('labels.general.actions')</th>
              </tr>
            </thead>
            <tbody>
              @foreach($invoices as $invoice)
              <tr class="vertical-middle">
                <td>{{ $invoice->transaction_id }}</td>
                <td>{{ $invoice->customer_name }}</td>
                <td>{{ $invoice->customer_phone }}</td>
                <td class="text-center">{{ floating($invoice->total_due) }}</td>
                <td class="text-center">{{ floating($invoice->total_courier) }}</td>
                <td class="text-center">{{ floating($invoice->total_payable) }}</td>
                <td class="text-center">{{ $invoice->status }}</td>
                <td>{{ date('M d, Y', strtotime($invoice->deleted_at)) }}</td>
                <td class="text-center">@include('backend.content.invoice.includes.actions-trash', ['invoice' =>
                  $invoice])
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div> <!-- col-->
    </div> <!-- row-->
    <div class="row">
      <div class="col-7">
        <div class="float-left">
          {!! $invoices->total() !!}
          {{ trans_choice('Invoice Trashed Found', $invoices->total()) }}
        </div>
      </div> <!-- col-->

      <div class="col-5">
        <div class="float-right">
          {!! $invoices->render() !!}
        </div>
      </div> <!-- col-->
    </div> <!-- row-->
  </div> <!-- card-body-->
</div> <!-- card-->
@endsection