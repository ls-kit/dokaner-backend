@extends('backend.layouts.app')

@section('title', app_name() . ' | '. __('Manage Contact Messages'))

@inject('carbon','Carbon\Carbon')

@section('content')
<div class="row">

  <div class="col-md-3">
    <a href="{{route('admin.contact.index')}}" class="btn btn-primary btn-block mb-3">Back to Inbox</a>
    @include('backend.content.contact.includes.mail-sidebar')
  </div> <!-- /.col -->
  <div class="col-md-9">
    <div class="card card-primary card-outline">
      <div class="card-header">
        <h3 class="card-title">Read Mail</h3>

        <div class="card-tools">
          <a href="#" class="btn btn-tool" data-toggle="tooltip" title="Previous"><i
              class="fas fa-chevron-left"></i></a>
          <a href="#" class="btn btn-tool" data-toggle="tooltip" title="Next"><i class="fa fa-chevron-right"></i></a>
        </div>
      </div>
      <!-- /.card-header -->
      <div class="card-body p-0">
        <div class="mailbox-read-info">
          <h5>{{$contact->name}}</h5>
          <h6>From: {{$contact->email}}
            <span class="mailbox-read-time float-right">
              {{ $carbon->parse($contact->created_at)->toDateTimeString()}}
            </span>
          </h6>
        </div>
        <!-- /.mailbox-read-info -->
        <div class="mailbox-controls with-border text-center">
          <div class="btn-group">
            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-container="body"
              title="Delete">
              <i class="fa fa-trash"></i></button>
            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-container="body"
              title="Reply">
              <i class="fa fa-reply"></i></button>
            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-container="body"
              title="Forward">
              <i class="fa fa-share"></i></button>
          </div>
          <!-- /.btn-group -->
          <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" title="Print">
            <i class="fa fa-print"></i></button>
        </div>
        <!-- /.mailbox-controls -->
        <div class="mailbox-read-message">
          <p>{{$contact->message}}</p>
        </div>
        <!-- /.mailbox-read-message -->
      </div> <!-- /.card-body -->
      <div class="card-footer">
        <div class="float-right">
          <button type="button" class="btn btn-default"><i class="fa fa-reply"></i> Reply</button>
          <button type="button" class="btn btn-default"><i class="fa fa-share"></i> Forward</button>
        </div>
        <button type="button" class="btn btn-default"><i class="fa fa-trash"></i> Delete</button>
        <button type="button" class="btn btn-default"><i class="fa fa-print"></i> Print</button>
      </div>
      <!-- /.card-footer -->
    </div>
    <!-- /.card -->
  </div> <!-- /.col -->
</div> <!-- /.row -->

@endsection