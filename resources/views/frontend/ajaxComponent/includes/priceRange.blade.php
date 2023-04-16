<?php

$Price = key_exists('Price', $item) ? $item['Price'] : [];
$Id = key_exists('Id', $item) ? $item['Id'] : '';
$Title = key_exists('Title', $item) ? $item['Title'] : '';
$ProviderType = key_exists('ProviderType', $item) ? $item['ProviderType'] : '';
$MainPictureUrl = key_exists('MainPictureUrl', $item) ? $item['MainPictureUrl'] : '';
$price = isset($price) ? $price : [];
$Attributes = isset($Attributes) ? $Attributes : [];
$MasterQuantity = key_exists('MasterQuantity', $item) ? $item['MasterQuantity'] : 1;
$FirstLotQuantity = key_exists('FirstLotQuantity', $item) ? $item['FirstLotQuantity'] : 1;
$NextLotQuantity = key_exists('NextLotQuantity', $item) ? $item['NextLotQuantity'] : 1;
$BatchLotQuantity = key_exists('BatchLotQuantity', $item) ? $item['BatchLotQuantity'] : 1;
$ConfiguredItems = key_exists('ConfiguredItems', $item) ? $item['ConfiguredItems'] : [];
$Promotions = key_exists('Promotions', $item) ? $item['Promotions'] : [];
$QuantityRanges = key_exists('QuantityRanges', $item) ? $item['QuantityRanges'] : [];
$ActualWeightInfo = key_exists('ActualWeightInfo', $item) ? $item['ActualWeightInfo'] : [];
$DeliveryCosts = key_exists('DeliveryCosts', $item) ? $item['DeliveryCosts'] : [];
$PhysicalParameters = key_exists('PhysicalParameters', $item) ? $item['PhysicalParameters'] : [];
$VendorId = key_exists('VendorId', $item) ? $item['VendorId'] : '';
$VendorName = key_exists('VendorName', $item) ? $item['VendorName'] : '';
$VendorScore = key_exists('VendorScore', $item) ? $item['VendorScore'] : '';

// dump($item);

$passData = [
  'Id' => $Id,
  'Title' => $Title,
  'ProviderType' => $ProviderType,
  'MainPictureUrl' => $MainPictureUrl,
  'Price' => $price,
  'Attributes' => $Attributes,
  'MasterQuantity' => $MasterQuantity,
  'FirstLotQuantity' => $FirstLotQuantity,
  'NextLotQuantity' => $NextLotQuantity,
  'BatchLotQuantity' => $BatchLotQuantity,
  'ConfiguredItems' => $ConfiguredItems,
  'Promotions' => $Promotions,
  'QuantityRanges' => $QuantityRanges,
  'ActualWeightInfo' => $ActualWeightInfo,
  'DeliveryCosts' => $DeliveryCosts,
  'PhysicalParameters' => $PhysicalParameters,
  'VendorId' => $VendorId,
  'VendorName' => $VendorName,
  'VendorScore' => $VendorScore,
];


$min_price = 0;
$max_price = 0;
if(!empty($Promotions)){
  $ConfiguredItems = collect(array_column($Promotions, 'ConfiguredItems'))->first();
  $promoSinglePrice = collect(array_column($Promotions, 'Price'))->first();

  if(key_exists('ConfiguredItems', $Promotions )){
      $ConfiguredItems = $Promotions['ConfiguredItems'];
      $promo_Price = !empty($ConfiguredItems) ? collect(array_column($ConfiguredItems, 'Price')) : [];
      if(!empty($promo_Price)){
          $min_price = $promo_Price->sortBy('OriginalPrice')->first();
          $min_price = !empty($min_price) ? $min_price['OriginalPrice'] : 0;
          $max_price = $promo_Price->sortByDesc('OriginalPrice')->first();
          $max_price = !empty($max_price) ? $max_price['OriginalPrice'] : 0;
      }else{
        $min_price = !empty($promoSinglePrice) ? $promoSinglePrice['OriginalPrice'] : 0;
      }
  }elseif(!empty($promoSinglePrice)){
      $min_price = key_exists('OriginalPrice',$promoSinglePrice) ? $promoSinglePrice['OriginalPrice'] : 0;
  }

}else{
  if(!empty($ConfiguredItems)){
      $Config_Price = collect(array_column($ConfiguredItems, 'Price'));
      $min_price = $Config_Price->sortBy('OriginalPrice')->first();
      $min_price = !empty($min_price) ? $min_price['OriginalPrice'] : 0;
      $max_price = $Config_Price->sortByDesc('OriginalPrice')->first();
      $max_price = !empty($max_price) ? $max_price['OriginalPrice'] : 0;
  }else{
    if(!empty($price)){
      $min_price = key_exists('OriginalPrice', $price) ? $price['OriginalPrice'] : 0;
    }
  }

}



?>


<span class="d-none" id="itemDetails">@json($passData)</span>



<span class="price">
  @if ($min_price)
  {{$currency.' '.convertedPrice($min_price)}}
  @endif
  @if ($min_price && $max_price && ($min_price !== $max_price)) - @endif
  @if ($max_price && ($min_price !== $max_price))
  {{$currency.' '.convertedPrice($max_price)}}
  @endif
</span>