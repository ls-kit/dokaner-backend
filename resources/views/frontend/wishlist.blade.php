@extends('frontend.layouts.app')

@section('title', 'Your Wishlist')


@section('content')
<!-- START SECTION BREADCRUMB -->
<div class="breadcrumb_section bg_gray page-title-mini py-3">
  <div class="container">
    <!-- STRART CONTAINER -->
    <div class="row align-items-center">
      <div class="col-md-6">
        <div class="page-title">
          <h1>Wishlist</h1>
        </div>
      </div>
      <div class="col-md-6">
        <ol class="breadcrumb justify-content-md-end">
          <li class="breadcrumb-item"><a href="{{route('frontend.index')}}">Home</a></li>
          <li class="breadcrumb-item active">Wishlist</li>
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
          <div class="table-responsive wishlist_table">
            <table class="table">
              <thead>
                <tr>
                  <th class="product-thumbnail">&nbsp;</th>
                  <th class="product-name">Product</th>
                  <th class="product-remove">Price</th>
                  <th class="product-remove">Remove</th>
                </tr>
              </thead>
              <tbody>
                @forelse ($wishlist as $item)
                <tr>
                  <td class="product-thumbnail">
                    <a href="{{url('product/'.$item->ItemId)}}">
                      @if (url_exists($item->MainPictureUrl))
                      <img src="{{asset($item->MainPictureUrl)}}">
                      @else
                      @php
                      $picture = collect(json_decode($item->Pictures))->skip(1)->take(1)->first();
                      @endphp
                      @if (url_exists($picture->Url))
                      <img src="{{asset($picture->Url)}}">
                      @endif
                      @endif
                    </a>
                  </td>
                  <td class="product-name" data-title="Product">
                    <a href="{{url('product/'.$item->ItemId)}}">{{$item->Title}}</a>
                  </td>
                  <td class="product-remove" data-title="price">
                    {{$currency.convertedPrice(json_decode($item->Price)->OriginalPrice ?? 0)}}</td>
                  <td class="product-remove" data-title="Remove">
                    <a class="btn btn-light" href="{{route('frontend.user.wishlist.remove',$item->ItemId)}}">
                      Remove
                    </a>
                  </td>
                </tr>

                @empty
                <tr>
                  <td class="text-center" colspan="3">Wishlist Empty</td>
                </tr>
                @endforelse

              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- END SECTION SHOP -->

  <!-- START SECTION SUBSCRIBE NEWSLETTER -->
  <div class="section bg_default small_pt small_pb">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-md-6">
          <div class="heading_s1 mb-md-0 heading_light">
            <h3>Subscribe Our Newsletter</h3>
          </div>
        </div>
        <div class="col-md-6">
          <div class="newsletter_form">
            <form>
              <input type="text" required="" class="form-control rounded-0" placeholder="Enter Email Address">
              <button type="submit" class="btn btn-dark rounded-0" name="submit" value="Submit">Subscribe</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- START SECTION SUBSCRIBE NEWSLETTER -->

</div>
<!-- END MAIN CONTENT -->
@endsection