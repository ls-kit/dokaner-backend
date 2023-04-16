@extends('frontend.layouts.app')

@section('title', $category->name)


@section('content')

  <!-- START SECTION BREADCRUMB -->
  <div class="breadcrumb_section bg_gray page-title-mini py-3">
    <div class="container"><!-- STRART CONTAINER -->
      <div class="row align-items-center">
        <div class="col-md-6">
          <div class="page-title">
            <h1>{{$category->name}}</h1>
          </div>
        </div>
        <div class="col-md-6">
          <ol class="breadcrumb justify-content-md-end">
            <li class="breadcrumb-item"><a href="{{route('frontend.index')}}">Home</a></li>
            <li class="breadcrumb-item active">{{$category->name}}</li>
          </ol>
        </div>
      </div>
    </div><!-- END CONTAINER-->
  </div>
  <!-- END SECTION BREADCRUMB -->

  <!-- START MAIN CONTENT -->
  <div class="main_content">

    <!-- START SECTION SHOP -->
    <div class="section">
      <div class="container">
        <div class="row">
          <div class="col-12">
            <div class="row align-items-center mb-4 pb-1">
              <div class="col-12">
                <div class="product_header">
                  <div class="product_header_left">
                    <div class="custom_select">
                      <select class="form-control form-control-sm">
                        <option value="order">Default sorting</option>
                        <option value="popularity">Sort by popularity</option>
                        <option value="date">Sort by newness</option>
                        <option value="price">Sort by price: low to high</option>
                        <option value="price-desc">Sort by price: high to low</option>
                      </select>
                    </div>
                  </div>
                  <div class="product_header_right">
                    <div class="products_view">
                      <a href="javascript:Void(0);" class="shorting_icon grid active">
                        <i class="ti-view-grid"></i>
                      </a>
                      <a href="javascript:Void(0);" class="shorting_icon list ">
                        <i class="ti-layout-list-thumb"></i>
                      </a>
                    </div>
                    <div class="custom_select">
                      <select class="form-control form-control-sm">
                        <option value="">Showing</option>
                        <option value="9">9</option>
                        <option value="12">12</option>
                        <option value="18">18</option>
                      </select>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row shop_container  grid">
              @forelse($items as $item)
                <div class="col-lg-3 col-md-4 col-6">
                  <div class="product">
                    <div class="product_img">
                      <a href="{{route('frontend.details','125412',['key' => '542145'])}}">
                        <img src="assets/images/product_img1.jpg" alt="product_img1">
                      </a>
                      <div class="product_action_box">
                        <ul class="list_none pr_action_btn">
                          <li class="add-to-cart">
                            <a href="#">
                              <i class="icon-basket-loaded"></i>Add To Cart
                            </a>
                          </li>
                          <li>
                            <a href="shop-compare.html" class="popup-ajax">
                              <i class="icon-shuffle"></i></a>
                          </li>
                          <li>
                            <a href="shop-quick-view.html" class="popup-ajax">
                              <i class="icon-magnifier-add"></i></a>
                          </li>
                          <li>
                            <a href="#"><i class="icon-heart"></i></a>
                          </li>
                        </ul>
                      </div>
                    </div>
                    <div class="product_info">
                      <h6 class="product_title">
                        <a href="{{route('frontend.details','125412',['key' => '542145'])}}">
                          Blue Dress For Woman</a>
                      </h6>
                      <div class="product_price">
                        <span class="price">$45.00</span>
                        <del>$55.25</del>
                        <div class="on_sale">
                          <span>35% Off</span>
                        </div>
                      </div>
                      <div class="rating_wrap">
                        <div class="rating">
                          <div class="product_rate" style="width:80%"></div>
                        </div>
                        <span class="rating_num">(21)</span>
                      </div>
                      <div class="pr_desc">
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus
                          blandit massa enim. Nullam id varius nunc id varius nunc.</p>
                      </div>
                      <div class="pr_switch_wrap">
                        <div class="product_color_switch">
                          <span class="active" data-color="#87554B"></span>
                          <span data-color="#333333"></span>
                          <span data-color="#DA323F"></span>
                        </div>
                      </div>
                      <div class="list_product_action_box">
                        <ul class="list_none pr_action_btn">
                          <li class="add-to-cart"><a href="#"><i
                                  class="icon-basket-loaded"></i>
                              Add To Cart</a></li>
                          <li><a href="shop-compare.html" class="popup-ajax"><i
                                  class="icon-shuffle"></i></a></li>
                          <li><a href="shop-quick-view.html" class="popup-ajax"><i
                                  class="icon-magnifier-add"></i></a></li>
                          <li><a href="#"><i class="icon-heart"></i></a></li>
                        </ul>
                      </div>
                    </div>
                  </div>
                </div>
              @empty
                <div class="col-lg-3 col-md-4 col-6">
                  <div class="product">
                    <div class="product_img">
                      <a href="shop-product-detail.html">
                        <img src="assets/images/product_img1.jpg" alt="product_img1">
                      </a>
                      <div class="product_action_box">
                        <ul class="list_none pr_action_btn">
                          <li class="add-to-cart"><a href="#"><i
                                  class="icon-basket-loaded"></i>
                              Add To Cart</a></li>
                          <li><a href="shop-compare.html" class="popup-ajax"><i
                                  class="icon-shuffle"></i></a></li>
                          <li><a href="shop-quick-view.html" class="popup-ajax"><i
                                  class="icon-magnifier-add"></i></a></li>
                          <li><a href="#"><i class="icon-heart"></i></a></li>
                        </ul>
                      </div>
                    </div>
                    <div class="product_info">
                      <h6 class="product_title"><a href="shop-product-detail.html">Blue Dress For
                          Woman</a></h6>
                      <div class="product_price">
                        <span class="price">$45.00</span>
                        <del>$55.25</del>
                        <div class="on_sale">
                          <span>35% Off</span>
                        </div>
                      </div>
                      <div class="rating_wrap">
                        <div class="rating">
                          <div class="product_rate" style="width:80%"></div>
                        </div>
                        <span class="rating_num">(21)</span>
                      </div>
                      <div class="pr_desc">
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus
                          blandit massa enim. Nullam id varius nunc id varius nunc.</p>
                      </div>
                      <div class="pr_switch_wrap">
                        <div class="product_color_switch">
                          <span class="active" data-color="#87554B"></span>
                          <span data-color="#333333"></span>
                          <span data-color="#DA323F"></span>
                        </div>
                      </div>
                      <div class="list_product_action_box">
                        <ul class="list_none pr_action_btn">
                          <li class="add-to-cart"><a href="#"><i
                                  class="icon-basket-loaded"></i>
                              Add To Cart</a></li>
                          <li><a href="shop-compare.html" class="popup-ajax"><i
                                  class="icon-shuffle"></i></a></li>
                          <li><a href="shop-quick-view.html" class="popup-ajax"><i
                                  class="icon-magnifier-add"></i></a></li>
                          <li><a href="#"><i class="icon-heart"></i></a></li>
                        </ul>
                      </div>
                    </div>
                  </div>
                </div>
              @endforelse
            </div>
            <div class="row">
              <div class="col-12">
                <ul class="pagination mt-3 justify-content-center pagination_style1">
                  <li class="page-item active"><a class="page-link" href="#">1</a></li>
                  <li class="page-item"><a class="page-link" href="#">2</a></li>
                  <li class="page-item"><a class="page-link" href="#">3</a></li>
                  <li class="page-item"><a class="page-link" href="#"><i
                          class="linearicons-arrow-right"></i></a></li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- END SECTION SHOP -->


  </div>
  <!-- END MAIN CONTENT -->

@endsection
