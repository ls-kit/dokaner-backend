<!DOCTYPE html>
@langrtl
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
@else
  <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  @endlangrtl

  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', app_name()) - {{app_name()}}</title>
    <meta name="description" content="@yield('meta_description', 'avanteca.com.bd')">
    <meta name="author" content="@yield('meta_author', 'Avanteca Web Apps Ltd.')">

    <link rel="shortcut icon" href="{{asset('img/brand/favicon.ico')}}" type="image/x-icon">
    <link rel="apple-touch-icon" sizes="180x180" href="{{asset('img/brand/apple-touch-icon.png')}}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{asset('img/brand/favicon192.png')}}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{asset('img/brand/favicon32.png')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('img/brand/favicon16.png')}}">
    <link rel="manifest" href="{{asset('img/brand/site.webmanifest')}}">

    @yield('meta')

    @stack('before-styles')

    {{ style('https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700') }}
    {{ style('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css') }}
    {{ style('backend/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}
    {{ style('backend/dist/css/adminlte.min.css') }}

    @stack('middle-styles')
    {{ style(mix('css/backend.css')) }}

    @stack('after-styles')

    @yield('styles')

  </head>

  <body class="layout-fixed sidebar-mini text-sm sidebar-collapse">
  <!-- Site wrapper -->
  <div class="wrapper">

  @include('backend.includes.header')

  @include('backend.includes.sidebar')


  <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <div class="container-fluid">
          @include('includes.partials.read-only')
          @include('includes.partials.logged-in-as')
          <div class="row mb-2">
            <div class="col-sm-12">
              {!! Breadcrumbs::render() !!}
            </div>
          </div>
        </div> <!-- /.container-fluid -->
      </section>

      <!-- Main content -->
      <section class="content">
        @include('includes.partials.messages-backend')
        @yield('content')
      </section> <!-- /.content -->
    </div> <!-- /.content-wrapper -->

    @include('backend.includes.footer')
    @include('backend.includes.aside')

  </div> <!-- ./wrapper -->


  <!-- Details loading Modal -->
  <div class="modal fade" id="details_loading" tabindex="-1" role="dialog" aria-labelledby="details_loading_title" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="details_loading_title">Modal title</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          {{--     details loading here by ajax     --}}
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>


  <!-- Scripts -->
  @stack('before-scripts')
  {!! script(mix('js/manifest.js')) !!}
  {!! script(mix('js/vendor.js')) !!}
  {!! script(mix('js/backend.js')) !!}
  {!! script(asset('backend/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')) !!}
  @stack('middle-scripts')
  {!! script(asset('backend/dist/js/adminlte.min.js')) !!}
  {!! script(asset('backend/dist/js/demo.js')) !!}
  @stack('after-scripts')


  </body>

  </html>
