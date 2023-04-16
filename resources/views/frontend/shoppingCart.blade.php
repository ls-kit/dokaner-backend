@extends('frontend.layouts.app')

@section('title', 'Your Shopping Cart')


@section('content')
  <!-- START MAIN CONTENT -->
  <div class="main_content" data-page="shopCart">

    <div class="breadcrumb_section tittle_breaddrumb p-4 bg_gray page-title-mini">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-md-12">
            <ol class="breadcrumb justify-content-md-start">
              <li class="breadcrumb-item"><a href="{{route('frontend.index')}}">Home</a></li>
              <li class="breadcrumb-item active">Checkout</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <!-- START SECTION SHOP -->
    <div class="section mb-5">
      <div class="container">
        <div class="row">
          <div class="col-sm-8">
            <div class="table-responsive ">
              <table class="table table-bordered table-sm" id="shoppingCartTable">
                <thead>
                <tr>
                  <th class="text-center">
                    <input type="checkbox" value="all" id="checkbox_all">
                  </th>
                  <th class="text-center">&nbsp</th>
                  <th class="text-center">Details</th>
                  <th class="product-price text-center">Quantity</th>
                  <th class="text-center">Total</th>
                  <th class="text-center">Remove</th>
                </tr>
                </thead>
                <tbody id="product_list_item">
                <!-- cart item append this block -->
                </tbody>
              </table>
            </div>
          </div> <!-- col-sm-8 -->
          <div class="col-sm-4">
            <div class="border p-3 p-md-4">
              <div class="d-flex heading_s1 justify-content-between mb-3">
                <h6>Shipping Address</h6>
                <button type="button" class="btn btn-link p-0" data-toggle="modal"
                        data-target="#shippingAddressModal">Manage
                </button>
              </div>
              <div class="mb-4 shippingAddress">
                <div class="card" style="border: 1px solid #ff324d">
                  <div class="card-body defaultAddressCardBody p-1 px-3">
                    {{-- default address append here --}}
                  </div>
                </div>
              </div>

              <div class="heading_s1 mb-3">
                <h6>Apply Coupon </h6>
              </div>
              <form class="mb-3" action="{{url('ajax/coupon-code-validation')}}" method="post" id="couponApplyForm">
                @csrf
                <input type="hidden" name="coupon_cartTotal" id="coupon_cartTotal">
                <div class="input-group">
                  <input type="text" class="form-control" name="coupon_code" id="coupon_code" minlength="4" maxlength="16"
                         placeholder="Enter Coupon Code" required="required">
                  <div class="input-group-append">
                    <button type="submit" class="btn btn-primary applyCoupon">Apply</button>
                  </div>
                </div>
              </form>

              <div class="heading_s1 mb-3">
                <h6>Order Summary</h6>
              </div>
              <div class="table-responsive">
                <table class="table">
                  <tbody>
                  <tr>
                    <td class="cart_total_label">Products Price</td>
                    <td class="cart_total_amount text-right"><span id="productTotalPrice">0.00</span></td>
                  </tr>

                  <tr id="couponRow">
                    <td class="cart_total_label">Coupon</td>
                    <td class="cart_total_amount text-right"><span id="couponVictory">0.00</span></td>
                  </tr>

                  <tr>
                    <td class="cart_total_label">Need to Pay 50%</td>
                    <td class="cart_total_amount text-right"><span id="needToPay">0.00</span></td>
                  </tr>
                  <tr>
                    <td class="cart_total_label">Due (Only for products)</td>
                    <td class="cart_total_amount text-right"><strong id="dueForProducts">0.00</strong></td>
                  </tr>
                  <tr>
                    <td colspan="2" class="text-center text-danger">
                      {{get_setting('order_summary_bottom_message')}}
                    </td>
                  </tr>
                  </tbody>
                </table>
              </div>
              <a href="{{route('frontend.payment')}}" class="btn btn-fill-out w-100" id="proceedButton">Proceed</a>
            </div>

          </div> <!-- col-sm-4 -->
        </div> <!-- row -->
      </div>
    </div>
    <!-- END SECTION SHOP -->


    <div class="modal fade" id="shippingAddressModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
         aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-scrollable ">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="staticBackdropLabel">
              Shipping Address
              <button type="button" class="btn btn-link p-0 ml-3" id="addNewAddress">Add New</button>
              <button type="button" class="btn btn-link p-0 ml-3" id="showAllAddress" style="display: none">All
                Address
              </button>
            </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div> <!-- modal-header -->
          <div class="modal-body" id="modalAddressBody"
               data-addressStore="{{route('frontend.ajax.customer.address.store')}}"
               data-addressDelete="{{route('frontend.ajax.customer.address.delete')}}"
               data-addressSetDefault="{{route('frontend.ajax.customer.address.store.default')}}">

          </div> <!-- modal-body -->
          <div class="modal-footer justify-content-center">
            {{-- <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>--}}
          </div>
        </div>
      </div>
    </div>


  </div>
  <!-- END MAIN CONTENT -->
@endsection

@push('after-scripts')
  <script>


  </script>
@endpush