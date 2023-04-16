@extends('backend.layouts.app')

@section('title', 'Manage Customers')

@section('content')
<div class="card">
  <div class="card-body">
    <div class="row">
      <div class="col-sm-5">
        <h4 class="card-title mb-0"> Manage Customers </h4>
      </div> <!-- col-->

      <div class="col-sm-7 pull-right">
        @include('backend.content.customer.includes.header-buttons')
      </div> <!-- col-->
    </div> <!-- row-->

    <div class="mt-4">
      @livewire('customer-table')
    </div> <!-- row-->

  </div> <!-- card-body-->
</div> <!-- card-->
@endsection

@push('after-styles')
@livewireStyles
@endpush

@push('after-scripts')
@livewireScripts
@endpush