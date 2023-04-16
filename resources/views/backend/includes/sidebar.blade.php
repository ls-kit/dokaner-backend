<aside class="main-sidebar sidebar-light-lightblue elevation-0">
  <a href="{{route('admin.dashboard')}}" class="brand-link">
    @if(get_setting('admin_logo_sm'))
      <img src="{{asset(get_setting('admin_logo_sm'))}}" alt="{{app_name()}}" class="brand-image elevation-0"
           style="opacity: .8">
    @else
      <img src="{{asset("images/no-logo.jpg")}}" alt="{{app_name()}}" class="brand-image elevation-0"
           style="opacity: .8">
    @endif
    <span class="brand-text font-weight-bold">{{str_replace(' ','', ucfirst(app_name()))}}</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent nav-compact" data-widget="treeview" role="menu"
          data-accordion="false">

        <li class="nav-item">
          <a href="{{ route('admin.dashboard') }}" class="nav-link {{ active_class(Route::is('admin.dashboard')) }}">
            <i class="nav-icon fa fa-tachometer"></i>
            <p class="text">@lang('menus.backend.sidebar.dashboard')</p>
          </a>
        </li>


        <li class="nav-item has-treeview {{ active_class(active_class(Route::is('admin.order.*') || Route::is('admin.invoice.*')), 'menu-open') }}">
          <a href="#" class="nav-link {{ active_class(Route::is('admin.order.*') || Route::is('admin.invoice.*')) }}">
            <i class="fa fa-shopping-cart nav-icon"></i>
            <p> Manage Orders
              <i class="fa fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('admin.order.index') }}"
                 class="nav-link {{ active_class(Route::is('admin.order.index') || Route::is('admin.order.show'))  }}">
                <i class="nav-icon fa fa-circle-o"></i>
                <p class="text">Recent Orders</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin.order.wallet') }}"
                 class="nav-link {{ active_class(Route::is('admin.order.wallet')) }}">
                <i class="nav-icon fa fa-circle-o"></i>
                <p class="text">Wallet</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin.invoice.index') }}"
                 class="nav-link {{ active_class(Route::is('admin.invoice.*')) }}">
                <i class="nav-icon fa fa-circle-o"></i>
                <p class="text">Manage Invoice</p>
              </a>
            </li>
          </ul>
        </li> <!-- has-treeview -->

        <li class="nav-item">
          <a href="{{ route('admin.product.index') }}"
             class="nav-link {{ active_class(Route::is('admin.product.*')) }}">
            <i class="nav-icon fa fa-list-alt"></i>
            <p class="text">Product</p>
          </a>
        </li>

        <li class="nav-item has-treeview {{ active_class(active_class(Route::is('admin.coupon.*')), 'menu-open') }}">
          <a href="#" class="nav-link {{ active_class(Route::is('admin.coupon.*')) }}">
            <i class="fa fa-angellist nav-icon"></i>
            <p> Manage Coupons
              <i class="fa fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('admin.coupon.log') }}"
                 class="nav-link {{ active_class(Route::is('admin.coupon.log')) }}">
                <i class="nav-icon fa fa-circle-o"></i>
                <p class="text">Coupon Logs</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin.coupon.index') }}"
                 class="nav-link {{ active_class(Route::is('admin.coupon.*') && !Route::is('admin.coupon.log')) }}">
                <i class="nav-icon fa fa-circle-o"></i>
                <p class="text">Coupons</p>
              </a>
            </li>
          </ul>
        </li> <!-- has-treeview -->

        <li class="nav-item">
          <a href="{{ route('admin.customer.index') }}"
             class="nav-link {{ active_class(Route::is('admin.customer.*')) }}">
            <i class="nav-icon fa fa-list-alt"></i>
            <p class="text">Customer</p>
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link {{ active_class(Route::is('admin.menu.*')) }}"
             href="{{ route('admin.menu.index') }}">
            <i class="nav-icon fa fa-magic"></i>
            <p class="text">Menus</p>
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link {{ active_class(Route::is('admin.taxonomy.*')) }}"
             href="{{ route('admin.taxonomy.index') }}">
            <i class="nav-icon fa fa-microchip"></i>
            <p class="text">Categories</p>
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link {{ active_class(Route::is('admin.contact.*')) }}"
             href="{{ route('admin.contact.index') }}">
            <i class="nav-icon fa fa-envelope-o"></i>
            <p class="text">Contact Message</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="{{ route('admin.page.index') }}" class="nav-link {{ active_class(Route::is('admin.page*')) }}">
            <i class="nav-icon fa fa-file"></i>
            <p class="text">Pages</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="{{ route('admin.faq.index') }}" class="nav-link {{ active_class(Route::is('admin.faq*')) }}">
            <i class="nav-icon fa fa-question-circle"></i>
            <p class="text">Faq</p>
          </a>
        </li>

        @php
          $frontendActive = (Route::is('admin.front-setting.*') || Route::is('admin.announcement.*') ||
          Route::is('admin.banner.*'));
        @endphp

        <li class="nav-item has-treeview {{ active_class($frontendActive, 'menu-open') }}">
          <a href="#" class="nav-link {{ active_class($frontendActive) }}">
            <i class="nav-icon fa fa-gears"></i>
            <p> Frontend Settings
              <i class="fa fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">


            <li class="nav-item">
              <a href="{{ route('admin.front-setting.topNotice.create') }}"
                 class="nav-link {{ active_class(Route::is('admin.front-setting.topNotice.*')) }}">
                <i class="nav-icon fa fa-circle-o"></i>
                <p class="text">Top Notice</p>
              </a>
            </li>

            <li class="nav-item">
              <a href="{{ route('admin.announcement.index') }}"
                 class="nav-link {{ active_class(Route::is('admin.announcement.*')) }}">
                <i class="nav-icon fa fa-bullhorn"></i>
                <p class="text">Announcements</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin.banner.index') }}"
                 class="nav-link {{ active_class(Route::is('admin.banner.*')) }}">
                <i class="nav-icon fa fa-desktop"></i>
                <p class="text">Manage Banner</p>
              </a>
            </li>

            <li class="nav-item">
              <a href="{{ route('admin.front-setting.manage.sections') }}"
                 class="nav-link {{ active_class(Route::is('admin.front-setting.manage.sections')) }}">
                <i class="nav-icon fa fa-desktop"></i>
                <p class="text">Manage Sections</p>
              </a>
            </li>

            <li class="nav-item">
              <a href="{{ route('admin.front-setting.banner.right') }}"
                 class="nav-link {{ active_class(Route::is('admin.front-setting.banner.right')) }}">
                <i class="nav-icon fa fa-desktop"></i>
                <p class="text">Banner Right</p>
              </a>
            </li>

            <li class="nav-item">
              <a href="{{ route('admin.front-setting.image.loading.create') }}"
                 class="nav-link {{ active_class(Route::is('admin.front-setting.image.loading.create')) }}">
                <i class="nav-icon fa fa-desktop"></i>
                <p class="text">Image Loader</p>
              </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('admin.front-setting.manage.homepage-cards') }}"
                   class="nav-link {{ active_class(Route::is('admin.front-setting.manage.homepage-cards')) }}">
                  <i class="nav-icon fa fa-desktop"></i>
                  <p class="text">Homepage Cards</p>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('admin.front-setting.manage.featured-categories') }}"
                   class="nav-link {{ active_class(Route::is('admin.front-setting.manage.featured-categories')) }}">
                  <i class="nav-icon fa fa-desktop"></i>
                  <p class="text">Featured Categories</p>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('admin.front-setting.footer-brand-settings') }}"
                   class="nav-link {{ active_class(Route::is('admin.front-setting.footer-brand-settings')) }}">
                  <i class="nav-icon fa fa-desktop"></i>
                  <p class="text">Pop-up Banner</p>
                </a>
              </li>

            <li class="nav-item">
              <a href="{{ route('admin.front-setting.manage.product-page-cards') }}"
                 class="nav-link {{ active_class(Route::is('admin.front-setting.manage.product-page-cards')) }}">
                <i class="nav-icon fa fa-desktop"></i>
                <p class="text">Product Page Cards</p>
              </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('admin.front-setting.checkout-discounts') }}"
                   class="nav-link {{ active_class(Route::is('admin.front-setting.checkout-discounts')) }}">
                  <i class="nav-icon fa fa-desktop"></i>
                  <p class="text">Checkout Discounts</p>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('admin.front-setting.payment-qr-codes') }}"
                   class="nav-link {{ active_class(Route::is('admin.front-setting.payment-qr-codes')) }}">
                  <i class="nav-icon fa fa-desktop"></i>
                  <p class="text">Payment Options</p>
                </a>
            </li>

          </ul>
        </li>


        <li class="nav-item has-treeview {{ active_class(Route::is('admin.setting.*'), 'menu-open') }}">
          <a href="#" class="nav-link {{ active_class(Route::is('admin.setting.*')) }}">
            <i class="nav-icon fa fa-gears"></i>
            <p> Settings
              @if ($pending_approval > 0)
                <span class="badge badge-info right">{{ $pending_approval }}</span>
              @endif
              <i class="fa fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('admin.setting.general') }}"
                 class="nav-link {{ active_class(Route::is('admin.setting.general*')) }}">
                <i class="fa fa-circle-o nav-icon"></i>
                <p> General Settings </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin.setting.price') }}"
                 class="nav-link {{ active_class(Route::is('admin.setting.price*')) }}">
                <i class="fa fa-circle nav-icon"></i>
                <p> Price Settings </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin.setting.limit') }}"
                 class="nav-link {{ active_class(Route::is('admin.setting.limit*')) }}">
                <i class="fa fa-circle nav-icon"></i>
                <p> Order Limitation </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin.setting.message') }}"
                 class="nav-link {{ active_class(Route::is('admin.setting.message*')) }}">
                <i class="fa fa-circle nav-icon"></i>
                <p> Message Settings </p>
              </a>
            </li>

            <li class="nav-item">
              <a href="{{ route('admin.setting.cache.control') }}"
                 class="nav-link {{ active_class(Route::is('admin.setting.cache.control*')) }}">
                <i class="fa fa-circle-o nav-icon"></i>
                <p> Cache Control </p>
              </a>
            </li>


          </ul>
        </li>


        @if ($logged_in_user->isAdmin())
          <li class="nav-header text-uppercase">
            @lang('menus.backend.sidebar.system')
          </li>
          <li class="nav-item has-treeview {{ active_class(Route::is('admin.auth*'), 'menu-open') }}">
            <a href="#" class="nav-link {{ active_class(Route::is('admin.auth*')) }}">
              <i class="nav-icon fa fa-user"></i>
              <p>
                @lang('menus.backend.access.title')
                @if ($pending_approval > 0)
                  <span class="badge badge-info right">{{ $pending_approval }}</span>
                @endif
                <i class="fa fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route('admin.auth.user.index') }}"
                   class="nav-link {{ active_class(Route::is('admin.auth.user*')) }}">
                  <i class="fa fa-circle nav-icon"></i>
                  <p>
                    @lang('labels.backend.access.users.management')
                    @if ($pending_approval > 0)
                      <span class="badge badge-danger right">{{ $pending_approval }}</span>
                    @endif
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('admin.auth.role.index') }}"
                   class="nav-link {{ active_class(Route::is('admin.auth.role*')) }}">
                  <i class="fa fa-circle nav-icon"></i>
                  <p>@lang('labels.backend.access.roles.management')</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item has-treeview {{ active_class(request()->is('admin/log-viewer*'), 'menu-open') }}">
            <a href="#" class="nav-link {{ active_class(request()->is('admin/log-viewer*')) }}">
              <i class="nav-icon fa fa-list"></i>
              <p>
                @lang('menus.backend.log-viewer.main')
                <i class="fa fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route('log-viewer::dashboard') }}"
                   class="nav-link {{ active_class(request()->is('admin/log-viewer')) }}">
                  <i class="fa fa-circle nav-icon"></i>
                  <p> @lang('menus.backend.log-viewer.dashboard') </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('log-viewer::logs.list') }}"
                   class="nav-link {{ active_class(request()->is('admin/log-viewer/logs*')) }}">
                  <i class="fa fa-circle nav-icon"></i>
                  <p>@lang('menus.backend.log-viewer.logs')</p>
                </a>
              </li>
            </ul>
          </li>
        @endif

      </ul>
    </nav> <!-- /.sidebar-menu -->
  </div> <!-- /.sidebar -->
</aside>
