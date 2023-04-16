@extends('backend.layouts.app')

@section('title', __('Frequently Asked Question'))

@section('content')
  <div class="card">
    <div class="card-header">
      <div class="row">
        <div class="col-sm-5">
          <h2 class="card-title my-1">
            @lang('Frequently Asked Question')
          </h2>
        </div> <!-- col-->

        <div class="col-sm-7 pull-right">
          @include('backend.content.faq.includes.header-buttons')
        </div> <!-- col-->
      </div> <!-- row-->
    </div>
    <div class="card-body">
      @livewire('faq-table')
    </div> <!-- card-body-->
  </div> <!-- card-->
@endsection