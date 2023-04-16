@extends('backend.layouts.app')

@section('title', app_name() . ' | '. __('Manage Contact Messages'))

@inject('carbon','Carbon\Carbon')

@section('content')
<div class="row">

  <div class="col-md-3">
    {{-- <a href="#" class="btn btn-primary btn-block mb-3">Compose</a> --}}
    @include('backend.content.contact.includes.mail-sidebar')
  </div> <!-- /.col -->

  <div class="col-md-9">
    <div class="card card-primary card-outline">
      <div class="card-header">
        <h3 class="card-title">Inbox</h3>
        <div class="card-tools">
          <div class="input-group input-group-sm">
            <input type="text" class="form-control" placeholder="Search Mail">
            <div class="input-group-append">
              <div class="btn btn-primary">
                <i class="fa fa-search"></i>
              </div>
            </div>
          </div>
        </div> <!-- /.card-tools -->
      </div>
      <!-- /.card-header -->
      <div class="card-body p-0">
        <div class="mailbox-controls">
          <!-- Check all button -->
          <button type="button" class="btn btn-default btn-sm checkbox-toggle">
            <i class="fa fa-square-o"></i>
          </button>
          <div class="btn-group">
            <button type="button" class="btn btn-default btn-sm"><i class="fa fa-trash"></i></button>
            <button type="button" class="btn btn-default btn-sm"><i class="fa fa-repeat"></i></button>
            <button type="button" class="btn btn-default btn-sm"><i class="fa fa-share"></i></button>
          </div>
          <!-- /.btn-group -->
          <button type="button" class="btn btn-default btn-sm"><i class="fa fa-refresh"></i></button>
          <div class="float-right"> 1-50/200
            <div class="btn-group">
              <button type="button" class="btn btn-default btn-sm"><i class="fa fa-chevron-left"></i></button>
              <button type="button" class="btn btn-default btn-sm"><i class="fa fa-chevron-right"></i></button>
            </div> <!-- /.btn-group -->
          </div> <!-- /.float-right -->
        </div>
        <div class="table-responsive mailbox-messages">
          <table class="table table-hover table-striped">
            <tbody>
              @forelse ($contacts as $contact)
              <tr>
                <td>
                  <div class="icheck-primary">
                    <input type="checkbox" value="" id="check1">
                    <label for="check1"></label>
                  </div>
                </td>
                <td class="mailbox-star">
                  <a href="#"><i class="fa fa-star text-warning"></i></a>
                </td>
                <td class="mailbox-name">
                  <a href="{{route('admin.contact.show', $contact)}}">{{$contact->name}}</a>
                </td>
                <td class="mailbox-subject">{{Str::words($contact->message, 12)}}</td>
                <td class="mailbox-date">
                  {{ $carbon->parse($contact->created_at)->diffForHumans()}}
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="6">
                  There is No Contact Messages!
                </td>
              </tr>
              @endforelse
            </tbody>
          </table> <!-- /.table -->
        </div> <!-- /.mail-box-messages -->
      </div>
      <!-- /.card-body -->
      <div class="card-footer p-0">
        <div class="mailbox-controls">
          <button type="button" class="btn btn-default btn-sm checkbox-toggle">
            <i class="fa fa-square-o"></i>
          </button>
          <div class="btn-group">
            <button type="button" class="btn btn-default btn-sm"><i class="fa fa-trash"></i></button>
            <button type="button" class="btn btn-default btn-sm"><i class="fa fa-repeat"></i></button>
            <button type="button" class="btn btn-default btn-sm"><i class="fa fa-share"></i></button>
          </div>
          <!-- /.btn-group -->
          <button type="button" class="btn btn-default btn-sm">
            <i class="fa fa-refresh"></i></button>
          <div class="float-right"> 1-50/200
            <div class="btn-group">
              <button type="button" class="btn btn-default btn-sm"><i class="fa fa-chevron-left"></i></button>
              <button type="button" class="btn btn-default btn-sm"><i class="fa fa-chevron-right"></i></button>
            </div> <!-- /.btn-group -->
          </div> <!-- /.float-right -->
        </div>
      </div>
    </div> <!-- /.card -->
  </div> <!-- /.col -->
</div> <!-- /.row -->

@endsection


@push('after-styles')

@endpush

@push('after-scripts')
<script>
  $(function () {
    //Enable check and uncheck all functionality
    $('.checkbox-toggle').click(function () {
      var clicks = $(this).data('clicks')
      if (clicks) {
        //Uncheck all checkboxes
        $('.mailbox-messages input[type=\'checkbox\']').prop('checked', false)
        $('.checkbox-toggle .far.fa-check-square').removeClass('fa-check-square').addClass('fa-square')
      } else {
        //Check all checkboxes
        $('.mailbox-messages input[type=\'checkbox\']').prop('checked', true)
        $('.checkbox-toggle .far.fa-square').removeClass('fa-square').addClass('fa-check-square')
      }
      $(this).data('clicks', !clicks)
    })

    //Handle starring for glyphicon and font awesome
    $('.mailbox-star').click(function (e) {
      e.preventDefault()
      //detect type
      var $this = $(this).find('a > i')
      var glyph = $this.hasClass('glyphicon')
      var fa    = $this.hasClass('fa')

      //Switch states
      if (glyph) {
        $this.toggleClass('glyphicon-star')
        $this.toggleClass('glyphicon-star-empty')
      }

      if (fa) {
        $this.toggleClass('fa-star')
        $this.toggleClass('fa-star-o')
      }
    })
  })
</script>
@endpush