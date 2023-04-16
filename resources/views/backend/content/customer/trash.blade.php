@extends('backend.layouts.app')

@section('title', 'Trashed Orders')

@section('content')
<div class="card">
  <div class="card-body">
    <div class="row">
      <div class="col-sm-5">
        <h4 class="card-title mb-0">
          Trashed Customers
        </h4>
      </div> <!-- col-->
      <div class="col-sm-7 pull-right">
        <div class="btn-toolbar float-right" role="toolbar" aria-label="@lang('labels.general.toolbar_btn_groups')">
          <a href="{{ route('admin.customer.index') }}" class="btn btn-success ml-1" data-toggle="tooltip"
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
                <th>Customer Name</th>
                <th style="width:120px" class="text-center">Email</th>
                <th style="width:120px" class="text-center">Phone</th>
                <th style="width:102px" class="text-center">Total Orders</th>
                <th style="width:100px" class="text-center">Register At</th>
                <th style="width:105px" class="text-center">@lang('labels.general.actions')</th>
              </tr>
            </thead>
            <tbody>
              @foreach($customers as $customer)
              <tr class="vertical-middle">
                <td>{{ $customer->full_name }}</td>
                <td class="text-center">{{ $customer->email }}</td>
                <td class="text-center">{{ $customer->phone }}</td>
                <td class="text-center">{{ $customer->orders_count }}</td>
                <td>{{ date('M d, Y', strtotime($customer->created_at)) }}</td>
                <td class="text-center">@include('backend.content.customer.includes.actions-trash', ['customer' =>
                  $customer])
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
          {!! $customers->total() !!}
          {{ trans_choice('Customer Trashed Found', $customers->total()) }}
        </div>
      </div> <!-- col-->

      <div class="col-5">
        <div class="float-right">
          {!! $customers->render() !!}
        </div>
      </div> <!-- col-->
    </div> <!-- row-->
  </div> <!-- card-body-->
</div> <!-- card-->
@endsection