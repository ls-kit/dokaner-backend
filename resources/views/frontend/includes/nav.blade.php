@if(get_setting('top_notice_text_active'))
  <div class="toHeaderNotice" style=" background: #fcf8ff">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-md-12">
          <div class="marquerNotice py-1 position-relative overflow-hidden">
            <div data-speed="50" data-gap="40" data-direction="left" data-duplicated="true" class='marquee'>
              <p class="m-0" style="color: #1f1f1f; font-weight: 400; font-size: 16px;">
                {{get_setting('top_notice_text')}}
              </p>
            </div>
          </div> <!-- col-md-12 -->
        </div>
      </div>
    </div> <!-- container -->
  </div> <!-- toHeaderNotice -->
@endif

<header class="header_wrap fixed-top header_with_topbar">
  <div class="top-header light_skin py-1 d-none d-md-block" style="background: #00b050">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-6 col-md-6">
          <div class="header_topbar_info">
            <div class="border-0 header_offer">
              <ul class="contact_detail text-center text-lg-left">
                @php
                  $phone = get_setting('office_phone',"01678077023")
                @endphp
                <li class="mr-2">
                  Hotline :
                  <a href="tel:{{$phone}}" class="text-white"><span>{{$phone}}</span></a>
                </li>
              </ul>
            </div>
          </div>
        </div> <!-- col-md-2 -->

        <div class="col-lg-6 col-md-6">
          <div class="text-center text-md-right">
            <ul class="header_list">

              {{-- @if(config('locale.status') && count(config('locale.languages')) > 1)
                <li class="dropdown">
                  <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true"
                     aria-expanded="false">
                  <span class="d-md-down-none">@lang('menus.language-picker.language')
                    ({{ strtoupper(app()->getLocale()) }})</span>
                  </a>
                  @include('includes.partials.lang')
                </li>
              @endif --}}
              @auth
                @php
                  $unreadNotice = auth()->user()->unreadNotifications->count()
                @endphp
                @if($unreadNotice)
                  <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true"
                       aria-expanded="false">
                      <i class="linearicons-alarm"></i>Notifications<span class="wishlist_count ml-1">{{$unreadNotice}}</span> <span
                          class="caret"></span>
                    </a>
                    <div class=" dropdown-menu dropdown-menu-right">
                      @forelse(auth()->user()->unreadNotifications as $notification)

                        @if($notification->type == 'App\Notifications\OrderAuthInformation')
                          @php
                            $invoice_id = isset($notification->data['invoice_id']) ? $notification->data['invoice_id'] : null ;
                            $notifyUrl = $invoice_id ? "admin/order/{$invoice_id}" : "admin/order";
                          @endphp
                          <a href="{{url($notifyUrl)}}" data-notice="{{$notification->id}}"
                             class="dropdown-item text-dark noticeButton">
                            Customer Placed a Order
                          </a>
                        @elseif($notification->type == 'App\Notifications\OrderPending')
                          <a href="{{route('frontend.user.dashboard')}}#orders" data-notice="{{$notification->id}}"
                             class="dropdown-item text-dark noticeButton">
                            Your Order #{{$notification->data['invoice_id']}} Placed. Order total
                            {{currency_icon().' '.$notification->data['amount']}}
                          </a>
                        @endif
                      @empty
                        <a href="#" class="dropdown-item text-dark">You have no
                          notification</a>
                      @endforelse
                    </div>
                  </li>
                @endif
              @endauth

              @guest
                <li>
                  <a href="{{route('frontend.auth.login')}}">
                    <i class="icon-user"></i><span>{{__('Login')}}</span>
                  </a>
                </li>
              @else
                <li class="dropdown">
                  <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true"
                     aria-expanded="false">
                    <i class="icon-user"></i><span>{{$logged_in_user->full_name}}</span>
                  </a>
                  <div class="dropdown-menu dropdown-menu-right">
                    @can('view backend')
                      <a href="{{route('admin.dashboard')}}" class="dropdown-item text-dark">{{__('Administration')}}</a>
                    @endcan
                    <a href="{{route('frontend.user.dashboard')}}" class="dropdown-item text-dark">{{__('Dashboard')}}</a>
                    <a class="dropdown-item text-dark"
                       href="{{route('frontend.user.dashboard', ['tab' => 'orders'])}}">{{__('My Orders')}}</a>
                    <a href="{{route('frontend.user.account')}}" class="dropdown-item text-dark">{{__('My Account')}}</a>
                    <a href="{{ route('frontend.auth.logout') }}"
                       class="dropdown-item text-dark">@lang('navs.general.logout')</a>
                  </div>
                </li>
              @endif
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="middle-header dark_skin">
    <div class="container">
      <div class="nav_block">

        <div class="d-block d-md-none">
          @include('frontend.includes.navCatContent')
        </div>
        @php
          $site_name = get_setting('site_name',"Avanteca Limited");
          $logoMenu = get_setting('frontend_logo_menu',"images/300x86.png");
        @endphp

        <a class="navbar-brand" href="{{route('frontend.index')}}">
          <img class="logo_demo" src="{{asset($logoMenu)}}" alt="{{$site_name}}">
        </a>

        <div class="product_search_form radius_input search_form_btn d-lg-block d-none">

          <form action="{{route('frontend.pictureSearch')}}" id="pictureSearchForm" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="file" name="picture" id="pictureSearch" class="d-none" accept="image/*">
          </form>

          <form action="{{route('frontend.search')}}" class="w-100" method="GET">
            <div class="input-group">
              <input name="s" class="form-control searchField" value="{{request('s', '')}}"
                     placeholder="Search AliExpress products by keywords or image" type="text" autocomplete="off">
              <div class="input-group-append">
                <button type="button" class="btn btn-success pictureSearchBtn" data-toggle="tooltip" title="Picture Search">
                  <i class="fas fa-camera"></i>
                </button>
                <button type="submit" class="btn btn-success searchActionBtn px-3">
                  <i class="fas fa-search"></i>
                </button>
              </div>
            </div> <!-- input-group -->
          </form>
          <p class="m-0 searchSuggestText">Please press enter for data </p>
        </div> <!-- product_search_form -->

        <ul class="navbar-nav attr-nav align-items-center">
          <li>
            <a href="{{route('frontend.user.wishlist.index')}}" class="nav-link">
              <i class="far fa-heart"></i>
              <span class="wishlist_count wishlistCount">0</span>
            </a>
          </li>
          <li class="cart_trigger">
            <a href="{{route('frontend.shoppingCart')}}" class="nav-link">
              <i class="fas fa-shopping-bag"></i>
              <span class="cart_count">0</span>
            </a>
          </li>

          @guest
            <li class="d-block d-md-none">
              <a href="{{route('frontend.auth.login')}}" class="nav-link">
                <i class="fas fa-user-alt"></i>
              </a>
            </li>
          @else
            <li class="d-block d-md-none">
              <a href="#" class="nav-link side_navbar_toggler" data-toggle="collapse" data-target="#navbarSidetoggle"
                 aria-expanded="false">
                <i class="fas fa-user-alt"></i>
              </a>
            </li>
          @endguest
        </ul>

      </div>
    </div>
  </div>

  <div class="container pb-1 d-block d-lg-none">
    <form action="{{route('frontend.search')}}" method="GET">
      <div class="input-group">
        <input name="s" class="form-control searchField" value="{{request('s', '')}}"
               placeholder="Search AliExpress products by keywords or image" type="text" autocomplete="off">
        <div class="input-group-append">
          <button type="button" class="btn btn-success pictureSearchBtn" data-toggle="tooltip" title="Picture Search">
            <i class="fas fa-camera"></i>
          </button>
          <button type="submit" class="btn btn-success searchActionBtn px-3">
            <i class="fas fa-search"></i>
          </button>
        </div>
      </div> <!-- input-group -->
      <ul class="search_bar_bottom_nav">
        <li><a href="{{route('frontend.shopNow')}}">Shop Now</a></li>
        <li><a href="{{route('frontend.shopNow')}}">Recent viewed</a></li>
        <li><a href="{{url('special-offer')}}">Special Offer</a></li>
        <li><a href="{{route('frontend.faqs')}}">FAQ</a></li>
      </ul>
    </form>
  </div>

  <div class="border-top bottom_header d-lg-block d-none shadow-sm">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-3 col-md-4 col-sm-6 col-3">
          @include('frontend.includes.navCatContent')
        </div>
        <div class="col-lg-9 col-md-8 col-sm-6 col-9">
          <nav class="navbar navbar-expand-lg">
            <button class="navbar-toggler side_navbar_toggler" type="button" data-toggle="collapse" data-target="#navbarSidetoggle"
                    aria-expanded="false">
              <span class="ion-android-menu"></span>
            </button>
            <div class="collapse navbar-collapse mobile_side_menu" id="navbarSidetoggle">
              <ul class="navbar-nav">
                <li><a class="nav-link nav_item" href="{{route('frontend.shopNow')}}">Shop Now</a></li>
                <li><a class="nav-link nav_item" href="{{route('frontend.shopNow')}}">Recent viewed</a></li>
                <li><a class="nav-link nav_item" href="http://chinabazarb2b.com" target="_blank">Buy Wholesale</a></li>
                <li><a class="nav-link nav_item" href="{{url('special-offer')}}">Special Offer</a></li>
                <li><a class="nav-link nav_item" href="{{route('frontend.faqs')}}">FAQ</a></li>
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
            </div> <!-- end widget -->
          </nav>
        </div>
      </div>
    </div>
  </div>

  @include('frontend.includes.right-sidebar')


</header>







@if (config('boilerplate.frontend_breadcrumbs'))
  @include('frontend.includes.partials.breadcrumbs')
@endif