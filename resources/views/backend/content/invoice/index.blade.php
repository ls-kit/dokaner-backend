@extends('backend.layouts.app')

@section('title', ' Manage Order Invoice')

@section('content')
  <div class="card">
    <div class="card-header">
      <div class="row">
        <div class="col-sm-5">
          <h4 class="my-1"> Manage Order Invoice </h4>
        </div> <!-- col-->
        <div class="col-sm-7 pull-right">
          @include('backend.content.invoice.includes.header-buttons')
        </div> <!-- col-->
      </div> <!-- row-->
    </div>
    <div class="card-body p-0">
      @livewire('invoice-table')
    </div> <!-- card-body-->
  </div> <!-- card-->


@endsection


@push('after-styles')
  @livewireStyles
@endpush

@push('after-scripts')
  @livewireScripts

  <script>
     const popupCenter = ({url, title, w, h}) => {
        // Fixes dual-screen position Most browsers      Firefox
        const dualScreenLeft = window.screenLeft !== undefined ? window.screenLeft : window.screenX;
        const dualScreenTop = window.screenTop !== undefined ? window.screenTop : window.screenY;
        const width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
        const height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;
        const systemZoom = width / window.screen.availWidth;
        const left = (width - w) / 2 / systemZoom + dualScreenLeft
        const top = (height - h) / 2 / systemZoom + dualScreenTop
        const newWindow = window.open(url, title, `scrollbars=yes, width=${w / systemZoom}, height=${h / systemZoom}, top=${top},left=${left}`)
        if (window.focus) newWindow.focus();
     }


     $(function () {

        $(document).on('click', '.printOrder', function (event) {
           event.preventDefault();
           var href = $(this).attr('href');
           popupCenter({url: href, title: 'Print Order', w: 800, h: 700});
        });

        $(document).on('click', '.show_details', function (event) {
           event.preventDefault();
           var href = $(this).attr('href');
           popupCenter({url: href, title: 'Show Details', w: 800, h: 700});
        });

        $(document).on('click', '.confirm_received', function (event) {
           event.preventDefault();
           var href = $(this).attr('href');
           Swal.fire({
              icon: 'info',
              title: 'Do you want to proceed?',
              showCancelButton: true,
              confirmButtonText: `Confirm`,
           }).then((result) => {
              if (result.value) {
                 window.location.href = href;
              }
           });
        });


     });
  </script>


@endpush