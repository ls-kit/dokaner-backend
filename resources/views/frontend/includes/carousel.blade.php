<div class="banner_section slide_medium shop_banner_slider staggered-animation-wrap">
  <div id="carouselExampleControls" class="carousel slide carousel-fade light_arrow" data-ride="carousel">
    <div class="carousel-inner">

      @forelse ($banners as $banner)
        <div class="carousel-item background_bg @if($loop->first) active @endif" data-img-src="{{asset($banner->post_thumb)}}">
          <div class="banner_slide_content banner_content_inner">
            <div class="container">
              <div class="row">
                <div class="col-lg-7 col">
                  <div class="banner_content overflow-hidden">
                    <h2 class="staggered-animation" data-animation="slideInLeft" data-animation-delay="0.5s">{{$banner->post_title}}</h2>
                    <div class="staggered-animation mb-1 mb-md-4 product-price" data-animation="slideInLeft"
                         data-animation-delay="1s">
                      {!! $banner->post_content !!}
                    </div>
                    <a class="btn btn-fill-out btn-radius staggered-animation text-uppercase"
                       href="{{route('frontend.shopNow')}}" data-animation="slideInLeft"
                       data-animation-delay="1.5s">Shop Now</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      @empty
      @endforelse
    </div>
    <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev"><i class="ion-chevron-left"></i></a>
    <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next"><i class="ion-chevron-right"></i></a>
  </div>
</div>













