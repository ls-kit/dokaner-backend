@extends('frontend.layouts.app')

@section('title', app_name() . ' | About Us')

@section('content')

  <!-- START SECTION BREADCRUMB -->
  <div class="breadcrumb_section bg_gray page-title-mini">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-md-6">
          <div class="page-title">
            <h1>{{$about->post_title}}</h1>
          </div>
        </div>
        <div class="col-md-6">
          <ol class="breadcrumb justify-content-md-end">
            <li class="breadcrumb-item"><a href="{{route('frontend.index')}}">Home</a></li>
            <li class="breadcrumb-item active">About</li>
          </ol>
        </div>
      </div>
    </div><!-- END CONTAINER-->
  </div>
  <!-- END SECTION BREADCRUMB -->

  <!-- START MAIN CONTENT -->
  <div class="main_content">

    <!-- STAT SECTION ABOUT -->
    <div class="section">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-lg-12">
            {!! $about->post_content !!}
          </div>
        </div>
      </div>
    </div>
    <!-- END SECTION ABOUT -->



  </div>
  <!-- END MAIN CONTENT -->


@endsection
