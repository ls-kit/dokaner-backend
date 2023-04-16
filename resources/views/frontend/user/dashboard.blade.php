@extends('frontend.layouts.app')

@section('title', __('navs.frontend.dashboard') )

@section('content')
  <div class="main_content">
    <div class="section pb_70">
      <div class="container">
        <div class="row">
          <div class="col-lg-12 col-md-12">
            <div class="dashboard_menu">
              @php
                $tab = request('tab');
              @endphp
              <ul class="nav nav-tabs nav-pills mb-3" role="tablist">
                <li class="nav-item">
                  <a class="nav-link @if(!$tab || $tab == 'dashboard') active @endif" id="dashboard-tab" data-toggle="tab" href="#dashboard"
                     role="tab"
                     aria-controls="dashboard" aria-selected="false"><i class="ti-layout-grid2"></i>Dashboard</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link @if($tab == 'orders') active @endif" id="orders-tab" data-toggle="tab" href="#orders" role="tab"
                     aria-controls="orders"
                     aria-selected="false"><i class="ti-shopping-cart-full"></i>Orders</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link @if($tab == 'address') active @endif" id="address-tab" data-toggle="tab" href="#address" role="tab"
                       aria-controls="address" aria-selected="true"><i class="ti-location-pin"></i>Addresses</a>
                </li>
                <li class="nav-item @if($tab == 'account') active @endif">
                    <a class="nav-link" id="account-detail-tab" data-toggle="tab" href="#account" role="tab"
                       aria-controls="account-detail" aria-selected="true"><i class="ti-id-badge"></i>Account Details</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link @if($tab == 'your-payments') active @endif" id="your-payments-tab" data-toggle="tab"
                     href="#your-payments" role="tab"
                     aria-controls="your-payments"
                     aria-selected="false"><i class="ti-shopping-cart-full"></i>Your Payments</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link @if($tab == 'invoice') active @endif" id="invoice-tab" data-toggle="tab" href="#invoice" role="tab"
                     aria-controls="orders"
                     aria-selected="false"><i class="icon-bag"></i>@lang('My Invoice')</a>
                </li>
              </ul>
            </div> <!-- dashboard_menu -->
          </div>
          <div class="col-lg-12 col-md-12">
            <div class="tab-content dashboard_content">
              <div class="tab-pane fade @if(!$tab || $tab == 'dashboard') active show @endif " id="dashboard" role="tabpanel"
                   aria-labelledby="dashboard-tab">
                @include('frontend.user.includes.dashboard')
              </div>
              <div class="tab-pane fade @if($tab == 'orders') active show @endif" id="orders" role="tabpanel" aria-labelledby="orders-tab">
                @include('frontend.user.includes.orders')
              </div>
              <div class="tab-pane fade @if($tab == 'your-payments') active show @endif" id="your-payments" role="tabpanel"
                   aria-labelledby="your-payments-tab">
                @include('frontend.user.includes.YourPayments')
              </div>
              <div class="tab-pane fade @if($tab == 'invoice') active show @endif" id="invoice" role="tabpanel"
                   aria-labelledby="orders-tab">
                @include('frontend.user.includes.invoice')
              </div>
              <div class="tab-pane fade @if($tab == 'address') active show @endif" id="address" role="tabpanel"
                   aria-labelledby="address-tab">
                @include('frontend.user.includes.address', ['addresses' => $addresses])
              </div>
              <div class="tab-pane fade  @if($tab == 'account') active show @endif" id="account" role="tabpanel"
                   aria-labelledby="account-tab">
                @include('frontend.user.includes.account')
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div> <!-- END MAIN CONTENT -->
@endsection


@push('after-styles')
  @livewireStyles
@endpush

@push('after-scripts')

  @livewireScripts

  @if (request('payment') === 'successful')
    <script>
       window.localStorage.removeItem('TB0pQDcfjymepm6vmhwp66LASgLgSHaoDU2');
       window.localStorage.removeItem('summary');


    </script>
  @endif


  <script>


     $(function () {
        $(document).on('click', '.nav-tabs li a', function (event) {
           event.preventDefault();
           var href = $(this).attr('href');
           href = href.replace("#", '');
           window.history.replaceState({}, "", 'dashboard?tab=' + href);
        })
     })


  </script>

@endpush
