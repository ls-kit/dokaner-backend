
@forelse($items as $item)
    @php
    $Id = key_exists('Id',$item) ? $item['Id'] : 0;
    $Title = key_exists('Title',$item) ? strip_tags($item['Title']) : '';
    $Pictures = key_exists('Pictures',$item) ? $item['Pictures'] : [];
    $thumb = key_exists('MainPictureUrl',$item) ? $item['MainPictureUrl'] :null;
    $Price = key_exists('Price',$item) ? $item['Price'] : [];
    $PromotionPrice = key_exists('PromotionPrice',$item) ? $item['PromotionPrice'] : [];
    $Price = !empty($PromotionPrice) ? $PromotionPrice : $Price ;
    $OriginalPrice = key_exists('OriginalPrice',$Price) ? $Price['OriginalPrice'] : 0;
    if(!empty($Pictures)){
      $pic = collect($Pictures)->where('IsMain', true)->first();
      if(key_exists('Medium', $pic)){
        $thumb = $pic['Medium']['Url'] ?? $thumb;
      }
    }
    $url = route('frontend.product', $Id);
    @endphp
    @if($OriginalPrice)
<div class="col-md-2 col-4">
  <div class="item">
    <a href="{{$url}}">
      <div class="product">
        <div class="product_img text-center">
          <img class="b2bLoading product-thumbnail" data-src="{{asset($thumb)}}" src="{{asset($productLoader)}}">
        </div>
        <div class="product_info">
          <h5 class="product_title" data-toggle="tooltip" title="{!! $Title !!}"> {!! $Title !!}</h5>
          <div class="product_price">
            <span class="price">{{$currency}} </span> <span class="price">{{convertedPrice($OriginalPrice)}}</span>
          </div>
        </div>
      </div>
    </a>
  </div>
</div>

@endif

@empty

<div class="col-12">
  <div class="text-center">
    <a href="{{url()->current()}}"> Reload the Page</a>
    <a href="{{url('/')}}"> Go to Home Page</a>
  </div>
</div>

@endforelse