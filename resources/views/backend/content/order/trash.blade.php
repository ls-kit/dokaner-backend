@extends('backend.layouts.app')

@section('title', 'Trashed Orders')

@section('content')
  <div class="card">
    <div class="card-body">
      <div class="row">
        <div class="col-sm-5">
          <h4 class="card-title mb-0">
            Trashed Orders
            <a href="#" class="ml-3 btn btn-light process_multiple_delete btn-sm" data-table="orders" data-permanent="permanent"><i
                  class="fa fa-trash-o"></i> Multiple Delete</a>
          </h4>
        </div> <!-- col-->
        <div class="col-sm-7 pull-right">
          <div class="btn-toolbar float-right" role="toolbar" aria-label="@lang('labels.general.toolbar_btn_groups')">
            <a href="{{ route('admin.order.index') }}" class="btn btn-success ml-1" data-toggle="tooltip"
               title="Show Orders"><i class="fa fa-list-alt"></i></a>
          </div>
        </div> <!-- col-->
      </div> <!-- row-->

      <div class="row mt-4">
        <div class="col-12">
          @livewire('trashed-order-table')
        </div> <!-- col-->
      </div> <!-- row-->

    </div> <!-- card-body-->
  </div> <!-- card-->
@endsection

@push('after-styles')

  @livewireStyles

@endpush

@push('after-scripts')
  {!! script('assets/js/manage-product.js') !!}
  @livewireScripts

@endpush