@extends('frontend.layouts.app')

@section('title', $category->name)

@php
  $productLoader = get_setting('sub_category_image_loader');
@endphp

@section('content')
  <div class="breadcrumb_section bg_gray page-title-mini py-3">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-md-6">
          <div class="page-title">
            <h1>{{$category->name}}</h1>
          </div>
        </div>
        <div class="col-md-6">
          <ol class="breadcrumb justify-content-md-end">
            <li class="breadcrumb-item"><a href="{{route('frontend.index')}}">Home</a></li>
            <li class="breadcrumb-item active">{{$category->name}}</li>
          </ol>
        </div>
      </div>
    </div>
  </div> <!-- END SECTION BREADCRUMB -->

  <!-- START MAIN CONTENT -->
  <div class="main_content">

    <!-- START SECTION SHOP -->
    <div class="section">
      <div class="container">
        <div class="row shop_container  grid">
          @forelse($childrens as $children)
            <div class="col-lg-3 col-md-4 col-6">
              <a href="{{url($category->slug, $children->slug)}}">
                <div class="product">
                  <div class="product_img text-center">
                    @php
                      $image = $children->picture ? $children->picture : $children->IconImageUrl;
                    @endphp
                    <img class="b2bLoading img-fluid w-75" data-src="{{asset($image)}}" src="{{asset($productLoader)}}"
                         style="max-height: 255px">
                  </div>
                  <div class="product_info text-center">
                    <h5 class="product_title"> {{$children->name}}</h5>
                  </div>
                </div>
              </a>
            </div>
          @empty
            <div class="col-lg-3 col-md-4 col-6">
              <a href="#">
                <div class="product">
                  <div class="product_img text-center">
                    <img src="{{asset('assets/images/product_img1.jpg')}}" class="img-fluid w-75" alt="product_img1">
                  </div>
                  <div class="product_info text-center">
                    <h5 class="product_title m-0"> Category name</h5>
                  </div>
                </div>
              </a>
            </div>
          @endforelse
        </div>
      </div>
    </div>
    <!-- END SECTION SHOP -->

  </div>
  <!-- END MAIN CONTENT -->

@endsection