@extends('frontend.layouts.app')

@section('title', 'Search')


@section('content')
<div class="bg_gray breadcrumb_section page-title-mini py-3">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-md-6">
        <div class="page-title">
          @php
          $type = request('type');
          $search = request('s');
          $image = isset($image) ? $image : '';
          @endphp
          <h1> Search Result @if($type == 'picture') <img src="{{asset($image)}}" style="max-width: 60px"> @endif</h1>
        </div>
      </div>
      <div class="col-md-6 d-none d-lg-block">
        <ol class="breadcrumb justify-content-md-end">
          <li class="breadcrumb-item"><a href="{{route('frontend.index')}}">Home</a></li>
          <li class="breadcrumb-item active">Search</li>
        </ol>
      </div>
    </div>
  </div>
</div>
@php
    $processData = [
      'route' => route('frontend.ajax.searchProducts'), 
      'search' => $search, 
      'type' => $type, 
      'pathTime' => request('time'), 
      'image' => $image,
    ];
@endphp
<span class="d-none searchParameters" data-page="search">@json($processData)</span>

<div class="main_content" style="padding-bottom: 50px">
  <div class="section">
    <div class="container">
      <div class="row">
        <div class="col-12">
          <div class="row" id="productContainer" style="min-height: 360px;">
            {{-- search product append here --}}
          </div> <!-- row -->
          <div class="row">
            <div class="col-12">
              <ul class="pagination mt-3 justify-content-center pagination_style1">
                <li class="page-item loadMoreBlock">
                  {{--loadmore btn append here--}}
                </li>
              </ul> <!-- pagination -->
            </div> <!-- col-12 -->
          </div>

        </div>
      </div>
    </div>
  </div> <!-- END SECTION SHOP -->


</div> <!-- END MAIN CONTENT -->

@endsection


