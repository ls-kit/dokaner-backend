@php
  $Attributes = isset($Attributes) ? collect($Attributes)->where('IsConfigurator', true)->filter(function ($colour){
                      return false !== stristr($colour['PropertyName'], 'color') || false !== stristr($colour['PropertyName'], 'colour') || array_key_exists('ImageUrl', $colour);
                      //return array_key_exists('ImageUrl', $colour);
                 }) : collect([]);
@endphp

@if(count($Attributes))
  <div class="pr_switch_wrap">
    <p class="float-none mb-2 switch_lable text-capitalize">
      @lang('Colour') : <span class="ColorName"></span>
    </p>
    <p class="product_size_switch">
      @foreach($Attributes as $Attribute)
        @php
          $PropertyName = $Attribute['PropertyName'];
          $PropertyValue = $Attribute['Value'];
          $ImageUrl = array_key_exists('ImageUrl', $Attribute) ? $Attribute['ImageUrl'] : '';
          $MiniImageUrl = array_key_exists('MiniImageUrl', $Attribute) ? $Attribute['MiniImageUrl'] : '';
          $imageKey = md5($PropertyValue);
        @endphp

        <span class="@if($loop->first) active @endif" title="{{$PropertyValue}}"
              data-filter="{{$imageKey}}" data-color-name="{{$PropertyValue}}"
              data-fullimageurl="{{$ImageUrl}}">
        @if ($MiniImageUrl)
            <img src="{{$MiniImageUrl}}" style="height: 44px">
          @elseif($ImageUrl)
            <img src="{{$ImageUrl}}" style="height: 44px">
          @else
            {{$PropertyValue}}
          @endif
      </span>
        @php
          $newPropertyName = $PropertyName;
        @endphp
      @endforeach
    </p>
  </div>
@endif