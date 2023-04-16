<?php

use App\Models\Content\SearchLog;
use App\Models\Content\Taxonomy;

if (!function_exists('generate_common_params')) {
  function generate_common_params($Contents, $rate = 0)
  {
    $generated = [];

    if ($rate == 0) {
        $rate = get_setting('increase_rate', 20);
    }

    foreach ($Contents as $product) {
      $item = [
        'img' => get_product_picture($product) ?? '',
        'name' => $product['Title'] ?? '',
        'product_code' => $product['Id'] ?? '',
        'rating' => $product['rating'] ?? '',
        'regular_price' => $product['Price']['MarginPrice'] * $rate,
        'sale_price' => $product['Price']['MarginPrice'] * $rate,
        'stock' => $product['MasterQuantity'] ?? 0,
        'total_sold' => get_features_value($product, 'TotalSales'),
      ];
      array_push($generated, $item);
    }

    return $generated;
  }
}

if (!function_exists('get_product_picture')) {
  function get_product_picture($product)
  {
    $Pictures = array_key_exists('Pictures', $product) ? $product['Pictures'] : [];
    if (!empty($Pictures)) {
      $Medium = array_key_exists('Medium', $Pictures[0]) ? $Pictures[0]['Medium'] : [];
      return array_key_exists('Url', $Medium) ? $Medium['Url'] : '';
    }
    return '';
  }
}

if (!function_exists('get_product_regular_price')) {
  function get_product_regular_price($product, $rate)
  {
    $Price = array_key_exists('Price', $product) ? $product['Price'] : [];
    if (!empty($Price)) {
      $OriginalPrice = array_key_exists('OriginalPrice', $Price) ? $Price['OriginalPrice'] : 0;
      if ($OriginalPrice) {
        return round($OriginalPrice * $rate);
      }
    }
    return 0;
  }
}

if (!function_exists('get_product_sale_price')) {
  function get_product_sale_price($product, $rate)
  {
    $Promotions = array_key_exists('Promotions', $product) ? $product['Promotions'] : [];
    if (!empty($Promotions)) {
      $PromoPrice = array_key_exists('Price', $Promotions[0]) ? $Promotions[0]['Price'] : [];
      $OriginalPrice = array_key_exists('OriginalPrice', $PromoPrice) ? $PromoPrice['OriginalPrice'] : 0;
      if ($OriginalPrice) {
        return round($OriginalPrice * $rate);
      }
    }

    $Price = array_key_exists('Price', $product) ? $product['Price'] : [];
    if (!empty($Price)) {
      $OriginalPrice = array_key_exists('OriginalPrice', $Price) ? $Price['OriginalPrice'] : 0;
      if ($OriginalPrice) {
        return round($OriginalPrice * $rate);
      }
    }

    return 0;
  }
}

if (!function_exists('get_features_value')) {
  function get_features_value($product, $key)
  {
    $FeaturedValues = array_key_exists('FeaturedValues', $product) ? $product['FeaturedValues'] : [];
    if (!empty($FeaturedValues)) {
      $FeaturedValues = collect($FeaturedValues)->where('Name', $key)->first();
      if ($FeaturedValues) {
        return $FeaturedValues['Value'] ?? '0';
      }
    }
    return 0;
  }
}

if (!function_exists('generate_browsing_key')) {
  function generate_browsing_key($key)
  {
    $slugKey = Str::slug($key);
    return $slugKey . '_' . md5($key);
  }
}

if (!function_exists('get_browsing_data')) {
  function get_browsing_data($key, $array = false, $fullPath = false)
  {
    $key = generate_browsing_key($key);
    $path = $fullPath ? $key : "browsing/{$key}.json";
    $existsFile = Storage::exists($path);

    if ($array) {
      if ($existsFile) {
        return json_decode(Storage::get($path), true) ?? [];
      }
      return [];
    }

    if ($existsFile) {
      return collect(json_decode(Storage::get($path), true));
    }

    return collect([]);
  }
}

if (!function_exists('store_browsing_data')) {
  function store_browsing_data($key, $data)
  {
    $path = "browsing/{$key}.json";
    Storage::put($path, json_encode($data));
  }
}

if (!function_exists('get_category_browsing_items')) {
  function get_category_browsing_items($keyword, $type,  $offset, $limit, $rate = 0, $min = null, $max = null, $orderBy = null, $offer = false)
  {
    if ($rate == 0) {
      $key = generate_browsing_key($keyword);
      $path = "browsing/{$key}.json";
      $existsFile = Storage::exists($path);
      $browsing = [];
      $browsingContents = [];
      if ($existsFile) {
        $browsing =  json_decode(Storage::get($path), true);
      }

      $browsing = is_array($browsing) ? $browsing : [];

      if (!empty($browsing) && is_array($browsing)) {
        $TotalCount = getArrayKeyData($browsing, 'TotalCount', 0);
        $browsingContents = getArrayKeyData($browsing, 'Content', []);
        $Contents = array_slice($browsingContents, $offset, $limit);
        if (!empty($Contents) && is_array($Contents)) {
          return [
            'TotalCount' => $TotalCount,
            'Content' => $Contents
          ];
        }
      }
    }

    if ($type == 'category') {
      $products = otc_category_items($keyword, $offset, $limit);
    } elseif ($type == 'text') {
      $products = otc_search_items($keyword, "text",  $offset, $limit, $min, $max, $orderBy, $offer);
    } elseif ($type == 'picture') {
      $products = otc_search_items($keyword, "picture",  $offset, $limit, $min, $max, $orderBy, $offer);
    }

    if (!empty($products) && is_array($products)) {
      $TotalCount = getArrayKeyData($products, 'TotalCount', 0);
      $Contents = getArrayKeyData($products, 'Content', []);
      if (!empty($Contents) && is_array($Contents)) {
        $Contents = generate_common_params($Contents, $rate);
        if (!empty($Contents) && is_array($Contents)) {
          if ($rate == 0) {
            $products['Content'] = array_merge($browsingContents, $Contents);
            store_browsing_data($key, $products);
          }

          return [
            'TotalCount' => $TotalCount,
            'Content' => $Contents
          ];
        }
      }
    }

    return ['Content' => [], 'TotalCount' => 0];
  }
}







if (!function_exists('sectionGetCategoryProducts')) {
  function sectionGetCategoryProducts($url, $limit = 50,  $offset = 0, $rate = 0)
  {
    $cat = explode('?', $url);
    $slug_name = str_replace('/', '', $cat[0]);
    $products = [];
    if (count($cat) > 0) {
      $offset = str_replace('page=', '', $cat[1]);
      $offset = $offset > 0 ? $offset * 32 : 0;
    }
    if ($slug_name) {
      $category = Taxonomy::where('slug', $slug_name)->first();
      if ($category) {
        if ($category->ProviderType == 'Taobao') {
          $products = get_category_browsing_items($category->otc_id, 'category',  $offset, $limit, $rate);
        } else {
          $keyword = $category->keyword ? $category->keyword : $category->name;
          $products = get_category_browsing_items($keyword, 'text',  $offset, $limit, $rate);
        }
      }
    }
    return $products;
  }
}

if (!function_exists('sectionGetSearchProducts')) {
  function sectionGetSearchProducts($url, $limit = 50, $offset = 0, $rate = 0)
  {
    $cat = explode('?', $url);
    $slug_name = str_replace('/', '', $cat[0]);
    $products = [];
    if (count($cat) > 0) {
      $page = str_replace('page=', '', $cat[1]);
      $offset = $offset > 0 ? $page * $limit : 0;
    }
    if ($slug_name) {
      $log = SearchLog::where('search_id', $slug_name)
        ->where('search_type', 'picture')
        ->first();
      $keyword = $slug_name;
      $type = 'text';
      if ($log) {
        $keyword = $log->query_data;
        $type = 'picture';
      }
      $products = get_category_browsing_items($keyword, $type, rand(1,21), $limit, $rate);
    }
    return $products;
  }
}
