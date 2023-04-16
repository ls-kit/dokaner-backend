@php
  $productLoader = get_setting('product_image_loader');
  $Title = array_key_exists('Title', $item) ? $item['Title'] : '';
  $MainPictureUrl = array_key_exists('MainPictureUrl', $item) ? $item['MainPictureUrl'] : '';
@endphp

@extends('frontend.layouts.app')

@section('title', strip_tags($Title))

@section('meta_title', strip_tags($Title))
@section('meta_image', asset($MainPictureUrl))


@php
  $title = strip_tags($Title);
@endphp


@section('content')
  <span class="d-none" id="product_id">{{$item['ItemId'] ?? $item['Id']}}</span>

  <div class="main_content" data-page="product"
       data-getItemDescription="{{route('frontend.ajax.getItemDescription')}}"
       data-getItemSellerInformation="{{route('frontend.ajax.getItemSellerInformation')}}">
    <div class="section">
      <div class="container">
        <div class="row">
          <div class="col-sm-12 d-none d-md-block">
            <h4 class="product_title" style="font-size: 20px">{!! $title !!}</h4>
            <div class="small_divider clearfix"></div>
          </div> <!-- col-sm-12 -->
          <div class="col-lg-6 col-md-6 mb-md-4 mb-0">
            <div class="product-image">
              @php
                $first = true;
              @endphp
              @foreach($Pictures as $picture)

                @if($first)
                  @if(url_exists($picture['Url']))
                    @php $first = false; @endphp
                    <div class="product_img_box">
                      <img id="product_img" src='{{$picture['Url']}}' data-zoom-image="{{$picture['Url']}}"/>
                    </div>
                    <div id="pr_item_gallery" class="product_gallery_item slick_slider" data-slides-to-show="4"
                         data-slides-to-scroll="1" data-infinite="false">
                      @endif
                      @endif
                      @if(url_exists($picture['Url']))
                        <div class="item">
                          <a href="#" class="product_gallery_item" data-image="{{$picture['Large']['Url']}}"
                             data-zoom-image="{{$picture['Large']['Url']}}">
                            <img src="{{$picture['Small']['Url']}}" alt="{{ strip_tags($Title)}}"/>
                          </a>
                        </div>
                      @endif
                      @if($loop->last)
                    </div>
                  @endif

                  @endforeach
            </div> <!-- product-image -->
            <hr>
            <div class=" d-md-none">
              <h4 class="product_title" style="font-size: 20px">{!! $title !!}</h4>
              <div class="small_divider clearfix"></div>
            </div> <!--  -->
          </div> <!-- col-lg-6 -->
          <div class="col-lg-6 col-md-6">
            <div class="pr_detail">
              <div class="product_description">
                <div class="loadAdditionalInformation">
                  {{-- Product additional information load here --}}
                </div> <!-- loadAdditionalInformation -->
                <div class="pr_desc table-responsive">
                  <table class="table table-bordered table-sm m-0" id="fromChinatoBd">
                    <thead>
                    <tr>
                      <th style="background: #54b151;color: #fff;font-weight: normal;padding: 7px 10px;" colspan="2">
                        FROM CHINA TO BANGLADESH
                      </th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                      <td>Total Quantity:</td>
                      <td><span id="totalQuantity">0</span></td>
                    </tr>
                    <tr>
                      <td>Approx. Weight:</td>
                      <td>
                        <span class="d-none" id="hiddenPerUnitApproxWeight" data-hasWeight="{{$hasWeight}}">{{$ApproxWeight}}</span>
                        <span id="approxWeight">{{$ApproxWeight}}</span> KG
                        <button type="button" class="btn m-0 p-0 text-danger" data-trigger="hover" data-container="body"
                                data-toggle="popover" data-placement="top"
                                data-content="{{get_setting('approx_weight_message')}}">
                          &nbsp; <i class="icon-info"></i>
                        </button>
                      </td>
                    </tr>
                    <tr>
                      <td class="align-middle">Weight Charges:</td>
                      @php
                        $message = get_setting('china_local_delivery_message');
                        $deliveryCharge = $currency.' '.get_setting('china_local_delivery_charge_limit');
                        $message = str_replace('[china_delivery_charge]', $deliveryCharge, $message);
                        $charges = get_setting('air_shipping_charges');
                        if($charges){
                        $charges = json_decode($charges, true);
                        $charges = collect($charges)->sortByDesc('rate')->first();
                        $rate= 0;
                        if($charges){
                        $rate = key_exists('rate',$charges ) ? $charges['rate'] : 0;
                        }
                        }
                      @endphp
                      <td>
                        Per KG Rate - <span id="airShippingCharge" data-shippingCharge="{{$rate}}">{{$currency.' '.$rate}} </span>
                        <button type="button" class="btn m-0 p-0 text-danger" data-trigger="hover" data-container="body"
                                data-toggle="popover" data-placement="top" data-content="{{$message}}">
                          &nbsp; <i class="icon-info"></i>
                        </button>
                      </td>
                    </tr>
                    <tr>
                      <td class="align-middle">Estimate Delivery:</td>
                      <td>
                        <span id="airDuration">By Air 15-25 Days</span>
                      </td>
                    </tr>
                    <tr>
                      <td>Products Price:</td>
                      <td><span id="productPrice">{{$currency}} 0.00</span></td>
                    </tr>
                    <tr id="chinaExpressFeeRow" style="display:none">
                      <td>China Express Fees:</td>
                      <td>
                        {{$currency}} <span id="chinaLocalDelivery">0.00</span>
                      </td>
                    </tr>
                    <tr>
                      <td>Total Products Price:</td>
                      <td><span id="totalPrice">{{$currency}} 0.00</span></td>
                    </tr>
                    <tr>
                      <td colspan="2">
                        <span class="text-danger">{{get_setting('china_to_bd_bottom_message')}}</span>
                      </td>
                    </tr>
                    </tbody>
                  </table>
                </div>
              </div> <!-- product_description -->
              <hr/>
              <div class="cart_extra">
                <div class="cart_btn">
                  <button class="btn btn-fill-out btn-addToCart" type="button">
                    Add to cart
                  </button>
                  <a class="btn btn-success" id="buyNow" href="{{route('frontend.shoppingCart')}}" style="display: none">
                    @lang('Buy Now')
                  </a>
                  <a class="btn  {{$exit_wishList ? 'disabled' : ''}} btn-secondary add_wishlist"
                     href="{{route('frontend.user.wishlist.store')}}" data-status="{{ $exit_wishList }}"
                     data-auth="{{ auth()->check() }}" data-id="{{$item['ItemId'] ?? $item['Id'] ?? ''}}">Wishlist</a>
                  <a class="btn btn-facebook text-white"
                     href="https://www.facebook.com/share.php?u={{url()->current()}}&title={{$title}}" target="blank">
                    <i class="fab fa-facebook-f"></i>
                  </a>
                  <a class="btn btn-primary" href="fb-messenger://share/?link={{url()->current()}}"
                     data-action="share/messenger/share" target="blank">
                    <i class="fab fa-facebook-messenger"></i>
                  </a>
                  <a class="btn btn-success" href="whatsapp://send?text={{url()->current()}}"
                     data-action="share/whatsapp/share" target="blank">
                    <i class="fab fa-whatsapp"></i>
                  </a>
                </div>
              </div> <!-- cart_extra -->
            </div>
            <hr>

          </div>

        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="tab-style3">
              <ul class="nav nav-tabs justify-content-center" role="tablist">
                <li class="nav-item">
                  <a class="nav-link active" id="Additional-info-tab" data-toggle="tab" href="#Additional-info" role="tab"
                     aria-controls="Additional-info" aria-selected="true">Additional info</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link " id="loadSellerInfo-tab" data-toggle="tab" href="#loadSellerInfo" role="tab"
                     aria-controls="loadSellerInfo" aria-selected="false">Seller Info</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link " id="Description-tab" data-toggle="tab" href="#Description" role="tab"
                     aria-controls="Description" aria-selected="false">Description</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="Shipping-tab" data-toggle="tab" href="#Shipping" role="tab"
                     aria-controls="Shipping" aria-selected="false">Shipping & Delivery</a>
                </li>
              </ul>
              <div class="tab-content shop_info_tab">
                <div class="tab-pane fade show active" id="Additional-info" role="tabpanel"
                     aria-labelledby="Additional-info-tab">
                  {{-- additional information attributes data append here --}}
                </div>
                <div class="tab-pane fade" id="loadSellerInfo" role="tabpanel" aria-labelledby="loadSellerInfo-tab">
                  <div class="loadVender_information_data text-center">
                    <button type="button" class="loadSellerInformation btn btn-success">Load Seller Info</button>
                  </div>
                </div>
                <div class="tab-pane fade" id="Description" role="tabpanel" aria-labelledby="Description-tab">
                  <p class="text-center">
                    <a href="#" class="btn btn-fill-out" id="loadDescription">Show Full Description</a>
                  </p>
                </div>
                <div class="tab-pane fade" id="Shipping" role="tabpanel" aria-labelledby="Shipping-tab">
                  {!! isset($page->post_content) ? $page->post_content : '' !!}
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-12">
            <div class="large_divider clearfix"></div>
          </div>
        </div>
        <div class="row">
          <div class="col-12">
            <div class="small_divider"></div>
            <div class="divider"></div>
            <div class="medium_divider"></div>
          </div>
        </div> <!-- row -->

        <div class="row">
          <div class="col-12">
            <div class="heading_s1">
              <h3>Releted Products</h3>
            </div>
            <div class="releted_product_slider carousel_slider owl-carousel owl-theme" data-margin="20"
                 data-responsive='{"0":{"items": "3"}, "481":{"items": "4"}, "768":{"items": "5"}, "991":{"items": "6"}}'>
              @foreach ($relatedProducts as $relatedProduct)
                <div class="item">
                  <div class="product_wrap">
                    <a href="{{url('product/'.$relatedProduct->ItemId)}}">
                      <div class="product_img">
                        @php
                          $Pictures = isset($relatedProduct->Pictures) ? json_decode($relatedProduct->Pictures) : null;
                          $Pictures = $Pictures ? collect($Pictures)->where('IsMain', true)->first() : null;
                          $thumb = isset($Pictures->Medium) ? $Pictures->Medium->Url : $relatedProduct->MainPictureUrl;
                        @endphp
                        <img class="b2bLoading product-thumbnail" data-src="{{asset($thumb)}}"
                             src="{{asset($productLoader)}}">
                      </div>
                    </a>
                    <div class="product_info">
                      <h6 class="product_title">
                        <a href="{{url('product/'.$relatedProduct->ItemId)}}">
                          {!! strip_tags($relatedProduct->Title ?? '') !!}
                        </a>
                      </h6>
                      <div class="product_price">
                        @php
                          $price = json_decode($relatedProduct->Price);
                          $price = $price ? $price->OriginalPrice : 0;
                        @endphp
                        <span class="price">{{ currency_icon() }} {{convertedPrice($price)}}</span>
                      </div>
                    </div>
                  </div>
                </div>
              @endforeach
            </div>
          </div>
        </div>
      </div>
    </div> <!-- END SECTION SHOP -->


  </div> <!-- END MAIN CONTENT -->
@endsection


@push('before-styles')
  {!! style('https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css') !!}
  <link rel="stylesheet" href="{{asset('assets/owlcarousel/css/owl.theme.css')}}">
  {!! style('https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css') !!}
@endpush

@push('after-scripts')
  {{script('https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js')}}
  {{script('assets/js/jquery.elevatezoom.js')}}
  {{script('assets/js/extend_plugins.js')}}


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
                 navText: ['<i class="ion-ios-arrow-left"></i>', '<i class="ion-ios-arrow-right"></i>'],
                 autoplay: $carousel.data("autoplay"),
                 animateIn: $carousel.data("animate-in"),
                 animateOut: $carousel.data("animate-out"),
                 autoplayTimeout: $carousel.data("autoplay-timeout"),
                 smartSpeed: $carousel.data("smart-speed"),
                 responsive: $carousel.data("responsive")
              });
           });
        }


        $(document).on("ready", function () {
           carousel_slider();
        });

     })(jQuery);
  </script>



@endpush