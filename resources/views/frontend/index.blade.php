@extends('frontend.layouts.app')

@section('title', get_setting('meta_title'))
@section('meta_title', get_setting('meta_title'))
@section('meta_description', get_setting('meta_description'))
@section('meta_image', asset(get_setting('meta_image')))

@php
$recentOrder = isset($recentOrder) ? $recentOrder : null;
$wishlistProduct = isset($wishlistProduct) ? $wishlistProduct : null;
$recentProducts = isset($recentProducts) ? $recentProducts : null;
$someoneBuying = isset($someoneBuying) ? $someoneBuying : null;
$catLoader = get_setting('category_image_loader');
@endphp

@section('content')

@isset($announcement)
@if($announcement)
<div class="modal fade subscribe_popup" id="onload-popup" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document" style="max-width: 605px;">
    <div class="modal-content">
      <div class="modal-body">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true"><i class="ion-ios-close-empty"></i></span>
        </button>
        <div class="row no-gutters">
          <div class="col-sm-12">
            @if($announcement->thumb_status)
            <img class="img-fluid w-100" src="{{asset($announcement->post_thumb)}}">
            @else
            <div class="card ">
              <div class="card-body">
                {!! $announcement->post_content !!}
              </div> <!-- card-body -->
            </div> <!-- card -->
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endif
@endisset


@include('frontend.includes.carousel', ['banners' => $banners, 'categories' => $categories])

<div class="main_content">

  <div class="section">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <div class="heading_tab_header">
            <div class="heading_s2">
              <h3>@lang('Products Best Selling')</h3>
            </div>
            <div class="browse-div">
              <a href="{{route('frontend.productsBestSelling')}}" class="btn btn-link p-0">@lang('Browse all')</a>
            </div>
          </div>
        </div>
      </div> <!-- row -->
      <div class="row">
        <div class="col-12">
          <div class="product_slider carousel_slider owl-carousel owl-theme dot_style1 nav_style5" data-loop="true"
            data-dots="true" data-nav="true" data-margin="8"
            data-responsive='{"0":{"items": "3"}, "481":{"items": "4"}, "768":{"items": "5"}, "991":{"items": "6"}}'>

            @foreach ($recentOrders as $recentOrder)
            @php
            $orderProduct = isset($recentOrder->product) ? $recentOrder->product : null;
            @endphp
            @if($orderProduct)
            <div class="item">
              <div class="product_wrap">
                <a href="{{url('product/'.$orderProduct->ItemId)}}">
                  <div class="product_img">
                    @php
                    $Pictures = isset($orderProduct->Pictures) ? json_decode($orderProduct->Pictures) : null;
                    $Pictures = $Pictures ? collect($Pictures)->where('IsMain', true)->first() : null;
                    $thumb = isset($Pictures->Medium) ? $Pictures->Medium->Url : $orderProduct->MainPictureUrl;
                    @endphp
                    <img class="b2bLoading product-thumbnail" data-src="{{asset($thumb)}}"
                      src="{{asset($productLoader)}}">
                  </div>
                </a>
                <div class="product_info">
                  <h6 class="product_title">
                    <a href="{{url('product/'.$orderProduct->ItemId)}}">
                      {!! strip_tags($orderProduct->Title) !!}
                    </a>
                  </h6>
                  <div class="product_price">
                    @php
                    $price = json_decode($orderProduct->Price);
                    $price = $price ? $price->OriginalPrice : 0;
                    @endphp
                    <span class="price">{{ currency_icon() }} {{convertedPrice($price)}}</span>
                  </div>
                </div>
              </div>
            </div>
            @endif
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </div> <!-- Just ordered -->

  <div class="section">
    <div class="container">

      <div class="row">
        <div class="col-md-12">
          <div class="heading_tab_header">
            <div class="heading_s2">
              <h3>@lang('Products Customer Loving')</h3>
            </div>
            <div class="browse-div">
              <a href="{{route('frontend.productsCustomerLoving')}}" class="btn btn-link p-0">@lang('Browse all')</a>
            </div>
          </div>
        </div>
      </div> <!-- row -->

      <div class="row">
        <div class="col-12">
          <div class="product_slider carousel_slider owl-carousel owl-theme dot_style1 nav_style5" data-loop="true"
            data-dots="true" data-nav="true" data-margin="8"
            data-responsive='{"0":{"items": "3"}, "481":{"items": "4"}, "768":{"items": "5"}, "991":{"items": "6"}}'>
            @foreach ($wishlistProducts as $wishlistProduct)
            @php
            $wishProduct = isset($wishlistProduct->product) ? $wishlistProduct->product : null;
            @endphp
            @if($wishProduct)
            <div class="item">
              <div class="product_wrap">
                <a href="{{url('product/'.$wishProduct->ItemId)}}">
                  <div class="product_img">
                    @php
                    $Pictures = isset($wishProduct->Pictures) ? json_decode($wishProduct->Pictures) : null;
                    $Pictures = $Pictures ? collect($Pictures)->where('IsMain', true)->first() : null;
                    $thumb = isset($Pictures->Medium) ? $Pictures->Medium->Url : $wishProduct->MainPictureUrl;
                    @endphp
                    <img class="b2bLoading product-thumbnail" data-src="{{asset($thumb)}}"
                      src="{{asset($productLoader)}}">
                  </div>
                </a>
                <div class="product_info">
                  <h6 class="product_title"><a href="{{url('product/'.$wishProduct->ItemId)}}">{!!
                      strip_tags($wishProduct->Title)
                      !!}</a></h6>
                  <div class="product_price">
                    @php
                    $price = json_decode($wishProduct->Price);
                    $price = $price ? $price->OriginalPrice : 0;
                    @endphp
                    <span class="price">{{ currency_icon() }} {{convertedPrice($price)}}</span>
                  </div>
                </div>
              </div>
            </div>
            @endif
            @endforeach

          </div>
        </div>
      </div>
    </div>
  </div> <!-- Someone loved it -->

  <div class="section">
    <div class="container">

      <div class="row">
        <div class="col-md-12">
          <div class="heading_tab_header">
            <div class="heading_s2">
              <h3>@lang('Products Buying on Process')</h3>
            </div>
            <div class="browse-div">
              <a href="{{route('frontend.productsBuyingOnProcess')}}" class="btn btn-link p-0">@lang('Browse all')</a>
            </div>
          </div>
        </div>
      </div> <!-- row -->

      <div class="row">
        <div class="col-12">
          <div class="product_slider carousel_slider owl-carousel owl-theme dot_style1 nav_style5" data-loop="true"
            data-dots="true" data-nav="true" data-margin="8"
            data-responsive='{"0":{"items": "3"}, "481":{"items": "4"}, "768":{"items": "5"}, "991":{"items": "6"}}'>
            @foreach ($someoneBuying as $cusBuyingProduct)
            @php
            $buyingProduct = isset($cusBuyingProduct->product) ? $cusBuyingProduct->product : null;
            @endphp
            @if($buyingProduct)
            <div class="item">
              <div class="product_wrap">
                <a href="{{url('product/'.$buyingProduct->ItemId)}}">
                  <div class="product_img">
                    @php
                    $Pictures = isset($buyingProduct->Pictures) ? json_decode($buyingProduct->Pictures) : null;
                    $Pictures = $Pictures ? collect($Pictures)->where('IsMain', true)->first() : null;
                    $thumb = isset($Pictures->Medium) ? $Pictures->Medium->Url : $buyingProduct->MainPictureUrl;
                    @endphp
                    <img class="b2bLoading product-thumbnail" data-src="{{asset($thumb)}}"
                      src="{{asset($productLoader)}}">
                  </div>
                </a>
                <div class="product_info">
                  <h6 class="product_title">
                    <a href="{{url('product/'.$buyingProduct->ItemId)}}">
                      {!! strip_tags($buyingProduct->Title) !!}
                    </a>
                  </h6>
                  <div class="product_price">
                    @php
                    $price = json_decode($buyingProduct->Price);
                    $price = $price ? $price->OriginalPrice : 0;
                    @endphp
                    <span class="price">{{ currency_icon() }} {{convertedPrice($price)}}</span>
                  </div>
                </div>
              </div>
            </div>
            @endif
            @endforeach

          </div>
        </div>
      </div>
    </div>
  </div> <!-- Someone buying it -->


  <div class="section">
    <div class="container">

      <div class="row">
        <div class="col-md-12">
          <div class="heading_tab_header">
            <div class="heading_s2">
              <h3>@lang('Products Recent Viewed')</h3>
            </div>
            <div class="browse-div">
              <a href="{{route('frontend.shopNow')}}" class="btn btn-link p-0">@lang('Browse all')</a>
            </div>
          </div>
        </div>
      </div> <!-- row -->

      <div class="row">
        @foreach ($recentProducts as $recent)
        <div class="col-sm-2 col-4 px-2">
          <div class="item">
            <div class="product_wrap">
              <a href="{{url('product/'.$recent->ItemId)}}">
                <div class="product_img text-center">
                  @php
                  $Pictures = isset($recent->Pictures) ? json_decode($recent->Pictures) : null;
                  $Pictures = $Pictures ? collect($Pictures)->where('IsMain', true)->first() : null;
                  $thumb = isset($Pictures->Medium) ? $Pictures->Medium->Url : $recent->MainPictureUrl;
                  @endphp
                  <img class="b2bLoading product-thumbnail" data-src="{{asset($thumb)}}"
                    src="{{asset($productLoader)}}">
                </div>
              </a>
              <div class="product_info">
                <h6 class="product_title" data-toggle="tooltip" title="{!! strip_tags($recent->Title) !!}">
                  <a href="{{url('product/'.$recent->ItemId)}}">
                    {!! strip_tags($recent->Title) !!}
                  </a>
                </h6>
                <div class="product_price">
                  @php
                  $price = json_decode($recent->Price);
                  $price = $price ? $price->OriginalPrice : 0;
                  @endphp
                  <span class="price">{{ currency_icon() }} {{convertedPrice($price)}}</span>
                </div>
              </div>
            </div>
          </div>
        </div>
        @endforeach
      </div>
    </div>
  </div> <!-- New arrive -->


</div> <!-- END MAIN CONTENT -->

@endsection

@push('before-styles')
{!! style('https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css') !!}
{!! style('https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css') !!}
@endpush

@push('after-scripts')
{{script('https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js')}}

<script>
  (function ($) {
        function carousel_slider() {
           $('.carousel_slider').each(function () {
              var $carousel = $(this);
              $carousel.owlCarousel({
                 dots: $carousel.data("dots"),
                 loop: $carousel.data("loop"),
                 items: $carousel.data("items"),
                 margin: $carousel.data("margin"),
                 mouseDrag: $carousel.data("mouse-drag"),
                 touchDrag: $carousel.data("touch-drag"),
                 autoHeight: $carousel.data("autoheight"),
                 center: $carousel.data("center"),
                 nav: $carousel.data("nav"),
                 rewind: $carousel.data("rewind"),
                 navText: ['<i class="ion-ios-arrow-left"></i>',
                    '<i class="ion-ios-arrow-right"></i>'
                 ],
                 autoplay: $carousel.data("autoplay"),
                 animateIn: $carousel.data("animate-in"),
                 animateOut: $carousel.data("animate-out"),
                 autoplayTimeout: $carousel.data("autoplay-timeout"),
                 smartSpeed: $carousel.data("smart-speed"),
                 responsive: $carousel.data("responsive")
              });
              $carousel.on('changed.owl.carousel', function (event) {
                 $("img.b2bLoading").Lazy();
              });
           });
        }


        function slick_slider() {
           $('.slick_slider').each(function () {
              var $slick_carousel = $(this);
              $slick_carousel.slick({
                 arrows: $slick_carousel.data("arrows"),
                 dots: $slick_carousel.data("dots"),
                 infinite: $slick_carousel.data("infinite"),
                 centerMode: $slick_carousel.data("center-mode"),
                 vertical: $slick_carousel.data("vertical"),
                 fade: $slick_carousel.data("fade"),
                 cssEase: $slick_carousel.data("css-ease"),
                 autoplay: $slick_carousel.data("autoplay"),
                 verticalSwiping: $slick_carousel.data("vertical-swiping"),
                 autoplaySpeed: $slick_carousel.data("autoplay-speed"),
                 speed: $slick_carousel.data("speed"),
                 pauseOnHover: $slick_carousel.data("pause-on-hover"),
                 draggable: $slick_carousel.data("draggable"),
                 slidesToShow: $slick_carousel.data("slides-to-show"),
                 slidesToScroll: $slick_carousel.data("slides-to-scroll"),
                 asNavFor: $slick_carousel.data("as-nav-for"),
                 focusOnSelect: $slick_carousel.data("focus-on-select"),
                 responsive: $slick_carousel.data("responsive")
              });
           });
        }

        $(document).on("ready", function () {
           carousel_slider();
           slick_slider();
        });

        $('.portfolio_filter').on('change', function () {
           $grid_selectors.isotope({
              filter: this.value
           });
        });

        $('.link_container').each(function () {
           $(this).magnificPopup({
              delegate: '.image_popup',
              type: 'image',
              mainClass: 'mfp-zoom-in',
              removalDelay: 500,
              gallery: {
                 enabled: true
              }
           });
        });
     })(jQuery);

</script>

@endpush