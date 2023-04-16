@extends('backend.layouts.app')

@section('title', 'Products Management')

@section('content')
<div class="card">
  <div class="card-header">
    <div class="row">
      <div class="col-sm-5">
        <h4 class="my-1">
          @lang('Products Management')
          <a href="#" class="ml-3 btn btn-light process_multiple_delete btn-sm" data-table="products"><i class="fa fa-trash-o"></i> Multiple Delete</a>
        </h4>
      </div> <!-- col-->
      <div class="col-sm-7 pull-right">
        @include('backend.content.product.includes.header-buttons')
      </div> <!-- col-->
    </div> <!-- row-->
  </div>
  <div class="card-body">
    @livewire('product-table')
  </div> <!-- card-body-->
</div> <!-- card-->

@endsection

@push('after-styles')
@livewireStyles
@endpush

@push('after-scripts')

@livewireScripts
{!! script('assets/js/manage-product.js') !!}

@endpush