@php
$item = isset($item) ? $item : collect([]);

$Attributes = key_exists('Attributes', $item) ? $item['Attributes'] : [];
$price = key_exists('Price', $item) ? $item['Price'] : [];
$Promotions = key_exists('Promotions', $item) ? $item['Promotions'] : [];

@endphp


<div class="product_price mainPrice table-responsive">
  @include('frontend.ajaxComponent.includes.priceRange',['item' => $item, 'price' => $price, 'Attributes' => $Attributes])
</div> <!-- price -->

<div class="attributesLoader">
  @include('frontend.ajaxComponent.includes.attributes',['Attributes' => $Attributes])
</div> <!-- attributes -->

<div class="pr_desc table-responsive itemCalculationTable" style="margin-bottom: 10px; max-height: 250px;">
  @include('frontend.ajaxComponent.includes.configuredItems',['item' => $item, 'price' => $price, 'Promotions' => $Promotions])
</div> <!-- pr_desc -->