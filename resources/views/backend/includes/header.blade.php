<nav class="main-header navbar navbar-expand navbar-white navbar-light">
  <!-- Left navbar links -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fa fa-bars"></i></a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
      <a href="{{ route('frontend.index') }}" class="nav-link" target="_blank">Frontend</a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
      <a class="nav-link" href="{{ route('admin.dashboard') }}">@lang('navs.frontend.dashboard')</a>
    </li>
  </ul>


  <!-- Right navbar links -->
  <ul class="navbar-nav ml-auto">
    <!-- Messages Dropdown Menu -->
    <li class="nav-item dropdown">
      <a class="nav-link" data-toggle="dropdown" href="#">
        <i class="fa fa-comments"></i>
        <span class="badge badge-danger navbar-badge">0</span>
      </a>
      <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <a href="#!" class="dropdown-item">
          <!-- Message Start -->
          <div class="media">
            <img src="{{asset('backend/dist/img/user1-128x128.jpg')}}" alt="User Avatar"
              class="img-size-50 mr-3 img-circle">
            <div class="media-body">
              <h3 class="dropdown-item-title">
                Brad Diesel
                <span class="float-right text-sm text-danger"><i class="fa fa-star"></i></span>
              </h3>
              <p class="text-sm">Call me whenever you can...</p>
              <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
            </div>
          </div>
          <!-- Message End -->
        </a>
        <div class="dropdown-divider"></div>
        <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
      </div>
    </li>
    <!-- Notifications Dropdown Menu -->
    @php
    $unreadNotice = auth()->user()->unreadNotifications->count()
    @endphp

    <li class="nav-item dropdown">
      <a class="nav-link" data-toggle="dropdown" href="#">
        <i class="fa fa-bell"></i>
        <span class="badge badge-warning navbar-badge">{{$unreadNotice}}</span>
      </a>
      <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <span class="dropdown-item dropdown-header">{{$unreadNotice}} Notifications</span>

        <div class="dropdown-divider"></div>
        @forelse(auth()->user()->unreadNotifications as $notification)

        @if($notification->type == 'App\Notifications\OrderAuthInformation')
        @php
        $invoice_id = isset($notification->data['invoice_id']) ? $notification->data['invoice_id'] : null ;
        $notifyUrl = $invoice_id ? "admin/order/{$invoice_id}" : "admin/order";
        @endphp

        <a href="{{url($notifyUrl)}}" data-notice="{{$notification->id}}" class="dropdown-item noticeButton">
          Customer Placed a Order
        </a>
        <div class="dropdown-divider"></div>

        @elseif($notification->type == 'App\Notifications\OrderPending')
        <a href="{{route('frontend.user.dashboard')}}#orders" data-notice="{{$notification->id}}"
          class="dropdown-item noticeButton">
          Your Order #{{$notification->data['invoice_id']}} Placed. Order total
          {{currency_icon().' '.$notification->data['amount']}}
        </a>
        <div class="dropdown-divider"></div>
        @endif

        @empty
        <a href="#" class="dropdown-item">
          You have no notification
        </a>
        <div class="dropdown-divider"></div>
        @endforelse

        <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
      </div>
    </li>
    <li class="nav-item dropdown">
      <a class="nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
        <img src="{{ $logged_in_user->picture }}" class="img-circle" style="width: 25px;"
          alt="{{ $logged_in_user->email }}">
        <span class="d-md-down-none">{{ $logged_in_user->full_name }}</span>
      </a>
      <div class="dropdown-menu dropdown-menu-right">
        <div class="dropdown-header text-center">
          <strong>Account</strong>
        </div>
        <a class="dropdown-item" href="{{ route('frontend.auth.logout') }}">
          <i class="fa fa-lock"></i> @lang('navs.general.logout')
        </a>
      </div>
    </li>

    <li class="nav-item">
      <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
        <i class="fa fa-th-large"></i>
      </a>
    </li>
  </ul>
</nav> <!-- /.navbar -->