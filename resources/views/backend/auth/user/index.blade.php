@extends('backend.layouts.app')

@section('title', app_name() . ' | ' . __('labels.backend.access.users.management'))

@section('breadcrumb-links')
  @include('backend.auth.user.includes.breadcrumb-links')
@endsection

@section('content')
  <div class="card">
    <div class="card-header">
      <div class="row">
        <div class="col-sm-5">
          <h4 class="my-1">
            {{ __('labels.backend.access.users.management') }} <small
                class="text-muted">{{ __('labels.backend.access.users.active') }}</small>
          </h4>
        </div> <!-- col-->
        <div class="col-sm-7 pull-right">
          @include('backend.auth.user.includes.header-buttons')
        </div> <!-- col-->
      </div> <!-- row-->
    </div>
    <div class="card-body">
      @livewire('user-table')
    </div><!--card-body-->
  </div><!--card-->
@endsection

@push('after-styles')
  @livewireStyles
@endpush

@push('after-scripts')
  @livewireScripts
@endpush