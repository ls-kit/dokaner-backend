<?php
$sellerInformation = isset($sellerInformation) ? $sellerInformation : [];
$Name = key_exists('Name',$sellerInformation ) ? $sellerInformation['Name'] : 'N/A';
$ShopName = key_exists('ShopName',$sellerInformation ) ? $sellerInformation['ShopName'] : 'N/A';
$Location = key_exists('Location',$sellerInformation ) ? $sellerInformation['Location'] : [];
$Credit = key_exists('Credit',$sellerInformation ) ? $sellerInformation['Credit'] : [];
$Scores = key_exists('Scores',$sellerInformation ) ? $sellerInformation['Scores'] : [];
$FeaturedValues = key_exists('FeaturedValues',$sellerInformation ) ? $sellerInformation['FeaturedValues'] : [];
$FeaturedValues = array_column($FeaturedValues, 'Value', 'Name');
$years = key_exists('years', $FeaturedValues) ? $FeaturedValues['years'] : '-';
$Level = key_exists('Level', $Credit) ? $Credit['Level'] : '-';

$City = key_exists('City', $Location) ? $Location['City'] : '';
$State = key_exists('State', $Location) ? $Location['State'] : '';

$DeliveryScore = key_exists('DeliveryScore', $Scores) ? $Scores['DeliveryScore'] : 0 ;
$ItemScore = key_exists('ItemScore', $Scores) ? $Scores['ItemScore'] : 0 ;
$ServiceScore = key_exists('ItemScore', $Scores) ? $Scores['ServiceScore'] : 0 ;

$grade = 'D';
if($ServiceScore >= 4 && $ServiceScore <= 5){
  $grade = 'A';
}elseif($ServiceScore >= 3 && $ServiceScore < 4){
  $grade = 'B';
}elseif($ServiceScore >= 2 && $ServiceScore < 3){
  $grade = 'C';
}


?>



<table class="table table-bordered table-sm">
  <thead>
    <tr>
      <th style="background: #54b151;color: #fff;font-weight: normal;padding: 7px 10px;" colspan="2">
        SELLER INFORMATION
      </th>
    </tr>
  </thead>
  <tbody>
    <tr><td style="width: 50%">Name:</td> <td>{{$Name}}</td> </tr>
    <tr><td style="width: 50%">Shop Name:</td> <td>{{$ShopName ? $ShopName : 'Unknown'}}</td> </tr>
    <tr><td style="width: 50%">Location:</td> <td>{{$State ? $State.',' : 'Unknown'}} {{$City ? $City : ''}}</td> </tr>
    <tr><td style="width: 50%">Shop Grade:</td> <td>{{$grade}}</td> </tr>
    <tr><td style="width: 50%">Delivery score:</td> <td>{{$DeliveryScore}}</td> </tr>
    <tr><td style="width: 50%">Item score:</td> <td>{{$ItemScore}}</td> </tr>
    <tr><td style="width: 50%">Rating:</td> <td>{{$Level}}</td> </tr>
    <tr><td style="width: 50%">Years on Market:</td> <td>{{$years}}</td> </tr>
  </tbody>
</table> <!-- end table -->