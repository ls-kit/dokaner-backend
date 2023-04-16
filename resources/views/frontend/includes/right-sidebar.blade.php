@auth
  <nav class="navbar d-lg-none">
    <div class="collapse navbar-collapse mobile_side_menu" id="navbarSidetoggle">
      <div class="mobile_navbar_header">
        <button type="button" class="btn sidebar_close"><i class="fas fa-times"></i></button>
        <i class="fas fa-user-circle"></i><span class="ml-2">{{$logged_in_user->full_name}}</span>
      </div>
      <ul class="navbar-nav">
        @if($unreadNotice)
          <li class="dropdown">
            <a data-toggle="dropdown" class="nav-link dropdown-toggle active" href="#">Notification
              {{$unreadNotice}}</a>
            <div class="dropdown-menu">
              <ul>
                @forelse(auth()->user()->unreadNotifications as $notification)
                  @if($notification->type == 'App\Notifications\OrderAuthInformation')
                    @php
                      $invoice_id = isset($notification->data['invoice_id']) ? $notification->data['invoice_id'] : null ;
                      $notifyUrl = $invoice_id ? "admin/order/{$invoice_id}" : "admin/order";
                    @endphp
                    <li><a class="dropdown-item nav-link nav_item" href="{{url($notifyUrl)}}"
                           data-notice="{{$notification->id}}">Customer Placed a Order</a></li>

                  @elseif($notification->type == 'App\Notifications\OrderPending')
                    <li>
                      <a class="dropdown-item nav-link nav_item" href="{{route('frontend.user.dashboard')}}#orders"
                         data-notice="{{$notification->id}}">
                        Your Order #{{$notification->data['invoice_id']}} Placed. Order total
                        {{currency_icon().' '.$notification->data['amount']}}
                      </a>
                    </li>
                  @endif
                @empty
                  <li>
                    <a class="dropdown-item nav-link nav_item" href="#">You have no notification</a>
                  </li>
                @endforelse
              </ul>
            </div>
          </li>
        @endif

        @can('view backend')
          <li><a class="nav-link nav_item" href="{{route('admin.dashboard')}}">{{__('Administration')}}</a></li>
        @endcan
        <li><a class="nav-link nav_item" href="{{route('frontend.user.dashboard')}}">{{__('Dashboard')}}</a></li>
        <li><a class="nav-link nav_item"
               href="{{route('frontend.user.dashboard', ['tab' => 'orders'])}}">{{__('My Orders')}}</a></li>
        <li><a class="nav-link nav_item" href="{{route('frontend.user.account')}}">{{__('My Account')}}</a></li>
        <li><a class="nav-link nav_item" href="{{route('frontend.contact')}}">Contact Us</a></li>
        <li><a class="nav-link nav_item" href="{{ route('frontend.auth.logout') }}">@lang('navs.general.logout')</a>
        </li>
      </ul>
    </div>
  </nav><!-- navbar -->
@endauth