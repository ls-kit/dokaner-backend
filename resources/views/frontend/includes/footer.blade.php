<!-- START FOOTER -->
<footer class="bg_gray" style="border-top: 3px solid #5dc273;">
  <div class="footer_top small_pt pb_20">
    <div class="container">
      <div class="row">
        <div class="col-lg-4 col-md-12 col-12">
          <div class="widget">
            @php
              $officePhone = get_setting('office_phone',"01678077023");
              $officeEmail = get_setting('office_email',"sumon4skf@gmail.com");
              $officeAddress = get_setting('office_address',"H:44, R:06, Nikunjha-01, Dhaka-1229");
              $logoFooter = get_setting('frontend_logo_footer');
            @endphp
            <div class="footer_logo">
              <a href="{{route('frontend.index')}}">
                @if($logoFooter)
                  <img src="{{asset($logoFooter)}}" class="img-fluid" alt="footer-logo">
                @else
                  <img src="{{asset('images/300x86.png')}}" class="img-fluid" alt="footer-logo">
                @endif
              </a>
            </div>
            <ul class="contact_info">
              <li>
                <i class="ti-location-pin"></i>
                <p>{!! $officeAddress !!}</p>
              </li>
              <li>
                <i class="ti-email"></i>
                <a href="mailto:{{$officeEmail}}">{{$officeEmail}}</a>
              </li>
              <li>
                <i class="ti-mobile"></i>
                <p>
                  <a href="tel:{{$officePhone}}" style="color: inherit;">{{$officePhone}}</a>
                </p>
              </li>
            </ul>
          </div>
          <div class="widget">
            <ul class="social_icons">
              @if(get_setting('facebook'))
                <li>
                  <a href="{{get_setting('facebook')}}" class="sc_facebook" target="_blank">
                    <i class="ion-social-facebook"></i>
                  </a>
                </li>
              @endif
              @if(get_setting('instagram'))
                <li>
                  <a href="{{get_setting('instagram')}}" class="sc_instagram" target="_blank">
                    <i class="ion-social-instagram-outline"></i>
                  </a>
                </li>
              @endif
              @if(get_setting('youtube'))
                <li>
                  <a href="{{get_setting('youtube')}}" class="sc_youtube" target="_blank">
                    <i class="ion-social-youtube-outline"></i>
                  </a>
                </li>
              @endif
            </ul>
          </div>
        </div>
        <div class="col-md col-12">
          <div class="widget">
            <h6 class="widget_title">Known Us</h6>
            <ul class="widget_links">
              <li><a href="{{route('frontend.aboutUs')}}">About Us</a></li>
              <li><a href="{{route('frontend.contact')}}">Contact Us</a></li>
              <li><a href="{{url('privacy-policy')}}">Privacy Policy</a></li>
              <li><a href="{{url('terms-conditions')}}">Terms & Conditions</a></li>
              <li><a href="{{url('return-and-refund-policy')}}">Return and Refund Policy</a></li>
              <li><a href="{{url('secured-payment')}}">Secured Payment</a></li>
              <li><a href="{{url('transparency')}}">Transparency</a></li>
            </ul>
          </div>
        </div>
        <div class="col-md col-12">
          <div class="widget">
            <h6 class="widget_title">Service Link</h6>
            <ul class="widget_links">
              <li><a href="{{url('how-to-buy')}}">How To Buy</a></li>
              <li><a href="{{url('shipping-and-delivery')}}">Shipping & Delivery</a></li>
              <li><a href="{{url('custom-and-shipping-charge')}}">Custom & Shipping Charge</a></li>
              <li><a href="{{url('delivery-charges')}}">Delivery Charges</a></li>
              <li><a href="{{url('minimum-order-quantity')}}">Minimum Order Quantity</a></li>
              <li><a href="{{url('prohibited-items')}}">Prohibited Items</a></li>
            </ul>
          </div>
        </div>
        <div class="col-md col-12">
          <div class="widget">
            <h6 class="widget_title">Customer</h6>
            <ul class="widget_links">
              <li><a href="{{route('frontend.user.account')}}">Account</a></li>
              <li><a href="{{url('special-offer')}}">Special Offer</a></li>
              <li><a href="{{route('frontend.user.wishlist.index')}}">Wish List</a></li>
              {{-- <li><a href="{{route('frontend.compare')}}">Compare</a></li> --}}
              <li><a href="{{route('frontend.shoppingCart')}}">Cart</a></li>
              {{-- <li><a href="{{route('frontend.payment')}}">Checkout</a></li> --}}
              <li><a href="{{route('frontend.faqs')}}">FAQ</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>


  <div class="bottom_footer border-top-tran  py-3" style="border-color: #ddd;">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-6 d-block d-lg-none">
          <img class="img-fluid" src="{{asset('img/frontend/ssl-commerz-pay-with-logo-payment.webp')}}"
               alt="sslcommerz">
        </div> <!-- col -->
        <div class="col-lg-6">
          <div class="text-center text-lg-left">
            <a href="https://otcommerce.com" target="_blank" title="Powered by OT Commerce" rel="author"
               class="logo mr-2">
              <img src="{{asset('img/frontend/brand/otcommerce.png')}}" class="otc_logo" alt="OT Commerce">
            </a>
            <a href="{{url('/')}}">{{app_name()}}</a> &nbsp; &copy; 2020 - {{date('Y')}} &nbsp; All rights reserved
          </div>
        </div> <!-- col -->
        <div class="col-lg-6 d-none d-lg-block">
          <img class="img-fluid" src="{{asset('img/frontend/ssl-commerz-pay-with-logo-payment.webp')}}"
               alt="sslcommerz">
        </div> <!-- col -->
      </div> <!-- row -->
    </div>
  </div>
</footer> <!-- END FOOTER -->

