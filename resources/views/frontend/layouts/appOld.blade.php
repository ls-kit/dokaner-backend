<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" @langrtl dir="rtl" @endlangrtl>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  @php
    $metaTitle = get_setting('meta_title',"taobao.com products selling website ");
    $metaDescription = get_setting('meta_description',"This app developed by avanteca");
  @endphp
  <title>@yield('title', $metaTitle)</title>
  <meta name="description" content="@yield('meta_description', $metaDescription)">
  <meta name="author" content="@yield('meta_author', 'Avanteca Ltd. Phone-01678077023')">

  @if(config('analytics.facebook-chat') === true)
    <meta property="fb:pages" content="{{env('FACEBOOK_PAGE_ID', '')}}"/>
  @endif
  <meta property="fb:app_id" content=""/>
  <meta property="og:local" content="{{ str_replace('_', '-', app()->getLocale()) }}">
  <meta property="og:type" content="article">
  <meta property="og:url" content="{{url()->full()}}">
  <meta property="og:title" content="@yield('meta_title')">
  <meta property="og:description" content="@yield('meta_description')">
  <meta property="og:image" content="@yield('meta_image')">

  <link rel="shortcut icon" href="{{asset('img/brand/favicon.ico')}}" type="image/x-icon">
  <link rel="apple-touch-icon" sizes="180x180" href="{{asset('img/brand/apple-touch-icon.png')}}">
  <link rel="icon" type="image/png" sizes="192x192" href="{{asset('img/brand/android-chrome-192x192.png')}}">
  <link rel="icon" type="image/png" sizes="32x32" href="{{asset('img/brand/favicon-32x32.png')}}">
  <link rel="icon" type="image/png" sizes="16x16" href="{{asset('img/brand/favicon-16x16.png')}}">
  <link rel="manifest" href="{{asset('img/brand/site.webmanifest')}}">


  @yield('meta')

  @stack('before-styles')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.0/animate.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.2/css/all.min.css">

  {!! style('https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.0/css/ionicons.min.css') !!}
  <link rel="stylesheet" href="{{asset('assets/css/themify-icons.css')}}">
  <link rel="stylesheet" href="{{asset('assets/css/linearicons.css')}}">
  <link rel="stylesheet" href="{{asset('assets/css/flaticon.css')}}">
  <link rel="stylesheet" href="{{asset('assets/css/simple-line-icons.css')}}">


  <link rel="stylesheet" href="{{asset('assets/css/magnific-popup.css')}}">
  <link rel="stylesheet" href="{{asset('assets/css/slick.css')}}">
  <link rel="stylesheet" href="{{asset('assets/css/slick-theme.css')}}">

  @stack('middle-styles')

  <link href="{{ mix('css/frontend.css') }}" rel="stylesheet">

  @stack('after-styles')

{{--  @include('includes.partials.ga')--}}

{{--  @include('includes.partials.fb-pixel')--}}

  <script>
     window.b2b = JSON.parse(@JSON(general_settings(true)));
     window.lodingEllipsis = `<div class="lds-ellipsis lds-ellipsis-custom"><span></span><span></span><span></span></div>`;
     window.isAuth = '{{auth()->check()}}';
  </script>

</head>

<body>
<div class="preloader">
  <div class="spinner-container">
    <div class="spinner-border" role="status">
      <span class="sr-only">Loading...</span>
    </div>
  </div>
</div> <!-- preloader -->

@include('includes.partials.fb-chat')

@include('includes.partials.read-only')
@include('includes.partials.logged-in-as')

@include('frontend.includes.nav')

@include('includes.partials.messages')

<main>
  @yield('content')
</main>

@include('frontend.includes.footer')

@include('includes.partials.profile-update-alert')

@stack('before-scripts')
<script src="{{ mix('js/manifest.js') }}"></script>
<script src="{{ mix('js/vendor.js') }}"></script>
<script src="{{ mix('js/frontend.js') }}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/waypoints/4.0.1/jquery.waypoints.min.js"></script>
{{script('assets/js/jquery.dd.min.js')}}
{{script('assets/js/slick.min.js')}}
{{script('assets/js/jquery.marquee.min.js')}}

{{script('assets/js/helper-scripts.js')}}

{{ script('assets/lazy/jquery.lazy.min.js') }}


@stack('after-scripts')

<script>
   $(function () {
      $("img.b2bLoading").Lazy();
      $('.marquee').marquee();
   });
</script>


</body>

</html>