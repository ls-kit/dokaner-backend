@extends('frontend.layouts.app')

@section('title', 'Products Buying On Process')

@section('content')
<div class="breadcrumb_section bg_gray page-title-mini  py-3">
  <div class="container">
    <!-- STRART CONTAINER -->
    <div class="row align-items-center">
      <div class="col-md-6">
        <div class="page-title">
          <h1>@lang('Products Buying On Process')</h1>
        </div>
      </div>
      <div class="col-md-6">
        <ol class="breadcrumb justify-content-md-end">
          <li class="breadcrumb-item"><a href="{{route('frontend.index')}}">Home</a></li>
          <li class="breadcrumb-item active">@lang('Products Buying On Process')</li>
        </ol>
      </div>
    </div>
  </div> <!-- END CONTAINER-->
</div>

<div class="main_content">
  <div class="section" style="padding-bottom: 50px">
    <div class="container">
      <div class="row">
        @forelse($buyingProcess as $item)
        <div class="col-sm-2 col-4">
          <div class="item">
            @php
            $url = route('frontend.product',$item->ItemId);
            $price = json_decode($item->Price);
            $OriginalPrice = $price->OriginalPrice ?? 0;
            @endphp
            <a href="{{$url}}">
              <div class="product">
                <div class="product_img text-center">
                  <img class="b2bLoading product-thumbnail" data-src="{{asset($item->MainPictureUrl)}}"
                    src="{{asset($productLoader)}}">
                </div>
                <div class="product_info">
                  <h5 class="product_title" data-toggle="tooltip" title="{!! strip_tags($item->Title) !!}"> {!!
                    strip_tags($item->Title) !!}</h5>
                  <div class="product_price">
                    <span class="price">{{$currency}} </span> <span
                      class="price">{{convertedPrice($OriginalPrice)}}</span>
                  </div>
                </div>
              </div>
            </a>
          </div>
        </div>
        @empty
        @endforelse

      </div> <!-- row -->

      <div class="row">
        <div class="col-12">
          {{$buyingProcess->onEachSide(1)->render()}}
        </div>
      </div> <!-- row -->

    </div>
  </div> <!-- END SECTION SHOP -->

</div> <!-- END MAIN CONTENT -->

@endsection