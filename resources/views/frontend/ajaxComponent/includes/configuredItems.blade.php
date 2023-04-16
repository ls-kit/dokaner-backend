<?php

$Promotions = isset($Promotions) ? $Promotions : [];
$ConfiguredItems = key_exists('ConfiguredItems', $item) ? collect($item['ConfiguredItems']) : [];
$Attributes = key_exists('Attributes', $item) ? collect($item['Attributes']) : [];
$QuantityRanges = key_exists('QuantityRanges', $item) ? collect($item['QuantityRanges']) : [];
$itemPrice = isset($price) ? $price : [];
$ActualWeightInfo = key_exists('ActualWeightInfo', $item) ? collect($item['ActualWeightInfo']) : [];
$BatchLotQuantity = key_exists('BatchLotQuantity', $item) ? $item['BatchLotQuantity'] : 1;

$price = 0;

if(!empty($Promotions)){  
  $promoSinglePrice = collect(array_column($Promotions, 'Price'))->first();
  if(!empty($promoSinglePrice)){
    $price = $promoSinglePrice['OriginalPrice'];
  }
}

if (!$price) {
  $price = key_exists('OriginalPrice', $itemPrice) ? $itemPrice['OriginalPrice'] : $price;
}

$rate = convertedPrice($price);
$itemBody = '';
$attributeHead = '';

if (count($ConfiguredItems)) {
  foreach ($ConfiguredItems as $parentIndex => $configItem) {
    $config_id = key_exists('Id', $configItem) ? $configItem['Id'] : '';
    $configPrice = key_exists('Price', $configItem) ? $configItem['Price'] : [];
    $bodyTdItem = '';
    $filterClass = '';

    foreach ($configItem['Configurators'] as $index => $attrConfig) {
      $Pid = $attrConfig['Pid'];
      $Vid = $attrConfig['Vid'];
      $isAttribute = $Attributes->where('Vid', $Vid)->first();
      if ($isAttribute) {
        $PropertyName = $isAttribute['PropertyName'];
        $Value = $isAttribute['Value'];
        $IsConfigurator = $isAttribute['IsConfigurator'];
        $filterClass .= ' ' . md5($Value);

        if ($IsConfigurator == false) {
          if ($parentIndex === 0) {
            $attributeHead .= '<th class="text-capitalize">' . $PropertyName . '</th>';
          }
          $ImageUrl = isset($isAttribute['ImageUrl']) ? $isAttribute['ImageUrl'] : null;
          $MiniImageUrl = isset($isAttribute['MiniImageUrl']) ? $isAttribute['MiniImageUrl'] : null;
          if ($ImageUrl && $MiniImageUrl) {
            $bodyTdItem .= '<td class="align-middle" data-image="' . $ImageUrl . '"><img src="' . $MiniImageUrl . '" style="width:60px"></td>';
          } else {
            $bodyTdItem .= '<td class="align-middle text-break" style="max-width:100px">' . $Value . '</td>';
          }
        } else {
          if (!stristr($PropertyName, 'color') && !stristr($PropertyName, 'colour')) {
            if (!key_exists('ImageUrl', $isAttribute)) {
              if ($parentIndex === 0) {
                $attributeHead = '<th class="text-capitalize">' . $PropertyName . '</th>';
              }
              $bodyTdItem = '<td class="align-middle text-break" style="max-width:100px">' . $Value . '</td>';
            }
          }
        }
      }
    }

    $promoPrice = calculatePromotionalPrice($config_id, $Promotions);

    $configPrice = !empty($promoPrice) ? $promoPrice : $configPrice;
    $configPrice = !empty($configPrice) ? $configPrice : $itemPrice;
    $OriginalPrice = key_exists('OriginalPrice', $configPrice) ? $configPrice['OriginalPrice'] : $rate;
    $price = convertedPrice($OriginalPrice);

    $disabledInput = '';
    $configQuantity = $configItem['Quantity'];
    $Quantity = $configQuantity;
    $itemBody .= '<tr class="' . $filterClass . '">';
    $itemBody .= $bodyTdItem;
    $itemBody .= '<td class="align-middle"><span class="priceRate">'.$currency.' '.$price . '</span></td>';
    $itemBody .= '<td class="align-middle text-center">';
    $itemBody .= configure_qty_input($configQuantity, $BatchLotQuantity, $configItem['Id']);
    $itemBody .= '</td>';
    $itemBody .= '</tr>';

  }
} else {
  $item_Id = $item['Id'];
  $itemBody .= '<tr class="">';
  $itemBody .= '<td class="align-middle"><span class="priceRate">'.$currency.' '.$rate . '</span></td>';
  $itemBody .= '<td class="align-middle">';

  $itemBody .= configure_qty_input($item['MasterQuantity'], $BatchLotQuantity, $item_Id);

  $itemBody .= '</td>';
  $itemBody .= '</tr>';
}

function configure_qty_input($MasterQuantity, $BatchLotQuantity, $item_id)
{
  $qty_box = '';
  if ($MasterQuantity) {
    $qty_box .= '<small>Stock: ' . $MasterQuantity . '</small><br>';
    $qty_box .= '<div class="input-group input-group-sm">';
    $qty_box .= '<div class="input-group-prepend">';
    $qty_box .= '<button type="button" class="btn btn-primary minus"><i class="fas fa-minus"></i></button>';
    $qty_box .= '</div>';
    $qty_box .= '<input type="text" name="quantity" class="qty form-control text-center" step="' . $BatchLotQuantity . '"  title="Qty" value="0" id="' . $item_id . '" max="' . $MasterQuantity . '" size="8">';
    $qty_box .= '<div class="input-group-append">';
    $qty_box .= '<button type="button" class="btn btn-primary plus"><i class="fas fa-plus"></i></button>';
    $qty_box .= '</div>';
    $qty_box .= '</div>';
  }else{
    $qty_box .= '<span class="text-danger font-weight-bold">Out of Stock</span>';
  }
  return $qty_box;
}

?>


<table class="table table-bordered table-sm text-center mb-3" id="itemCalculationTable" style="table-layout: fixed;">
  <thead>
    <tr>
      {!! $attributeHead !!}
      <th>Price</th>
      <th>Quantity</th>
    </tr>
  </thead>
  <tbody>
    {!! $itemBody !!}
  </tbody>
</table>