@extends('frontend.layouts.app')

@section('title', $subcategory ? $subcategory->name : $category->name)

@section('content')
<div class="breadcrumb_section bg_gray page-title-mini py-3">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-md-6">
        <div class="page-title">
          <h1>{{$subcategory ? $subcategory->name : $category->name}}</h1>
        </div>
      </div>
      <div class="col-md-6">
        <ol class="breadcrumb justify-content-md-end">
          <li class="breadcrumb-item"><a href="{{route('frontend.index')}}">Home</a></li>
          <li class="breadcrumb-item"><a href="{{url($category->slug)}}">{{$category->name}}</a></li>
          @if($subcategory)
          <li class="breadcrumb-item active">{{$subcategory->name}}</li>
          @endif
        </ol>
      </div>
    </div>
  </div> <!-- END CONTAINER-->
</div> <!-- END SECTION BREADCRUMB -->


@php
    $processData = [
      'route' => route('frontend.ajax.categoryProducts'), 
      'cat_id' => $category ? $category->id : '', 
      'subcat_id' => $subcategory ? $subcategory->id : ''
    ];
@endphp
<span class="d-none searchParameters" data-page="category">@json($processData)</span>


<div class="main_content" style="padding-bottom: 50px">
  <div class="section">
    <div class="container">
      <div class="row">
        <div class="col-12">
          <div class="row" id="productContainer" style="min-height: 360px;">
            {{-- category data load here --}}
          </div>
        </div>
        <div class="col-12">
          <ul class="pagination mt-3 justify-content-center pagination_style1">
            <li class="page-item loadMoreBlock">
              {{-- loadmore btn append here --}}
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div> <!-- END SECTION SHOP -->


</div> <!-- END MAIN CONTENT -->

@endsection
