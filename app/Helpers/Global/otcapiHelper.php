<?php

use GuzzleHttp\Client;

if (!function_exists('load_otc_api')) {
    function load_otc_api()
    {
        return get_setting('mybd_api_url') . '/service-json/';
    }
}

if (!function_exists('setOtcParams')) {
    function setOtcParams()
    {
        return get_setting('mybd_api_token');
    }
}

if (!function_exists('getSiteUrl')) {
    function getSiteUrl()
    {
        return get_setting('site_url');
    }
}

if (!function_exists('getArrayKeyData')) {
    function getArrayKeyData(array $array, string $key, $default = null)
    {
        if (is_array($array)) {
            return array_key_exists($key, $array) ? $array[$key] : $default;
        }
        return $default;
    }
}

if (!function_exists('GetThreeLevelRootCategoryInfoList')) {
    function GetThreeLevelRootCategoryInfoList()
    {
        $query = [
            'instanceKey' => setOtcParams(),
            'language' => 'en'
        ];

        $client = new Client();
        $response = $client->request('GET', load_otc_api() . 'GetThreeLevelRootCategoryInfoList', ['query' => $query]);

        $statusCode = $response->getStatusCode();
        if ($statusCode == 200) {
            $content = json_decode($response->getBody(), true);
            if (is_array($content)) {
                $CategoryInfoList = array_key_exists('CategoryInfoList', $content) ? $content['CategoryInfoList'] : [];
                if (is_array($CategoryInfoList)) {
                    return array_key_exists('Content', $CategoryInfoList) ? $CategoryInfoList['Content'] : [];
                }
            }
        }
        return [];
    }
}

if (!function_exists('otc_category_items')) {
    function otc_category_items($cat_id, $offset = 0, $limit = 50)
    {
        $query = [
            'instanceKey' => setOtcParams(),
            'language' => 'en',
            'categoryId' => $cat_id,
            'framePosition' => $offset,
            'frameSize' => $limit
        ];

        $client = new Client();
        $response = $client->request('GET', load_otc_api() . 'GetCategoryItemInfoListFrame', ['query' => $query]);

        $statusCode = $response->getStatusCode();
        if ($statusCode == 200) {
            $content = json_decode($response->getBody(), true);
            if (is_array($content)) {
                return array_key_exists('OtapiItemInfoSubList', $content) ? $content['OtapiItemInfoSubList'] : [];
            }
        }
        return ['Content' => [], 'TotalCount' => 0];
    }
}

if (!function_exists('otc_search_items')) {
    function otc_search_items($search, $type, $offset = 1, $limit = 24, $min = null, $max = null, $orderBy = null, $offer = false)
    {
        // otc_search_items('bag', 'text', 0, 5)
        parse_str(parse_url($search, PHP_URL_QUERY), $search_array);
        $data_id = key_exists('id', $search_array) ? $search_array['id'] : null;
        $search = $data_id ? "https://item.taobao.com/item.htm?id={$data_id}" : $search;

        if ($min != null || $max != null) {
            $query = [
                'instanceKey' => setOtcParams(),
                'language' => 'en',
                'xmlParameters' => '<SearchItemsParameters><ItemTitle>' . $search . '</ItemTitle><MinPrice>' . $min . '</MinPrice><MaxPrice>' . $max . '</MaxPrice><SearchMethod>' . $type . '</SearchMethod></SearchItemsParameters>',
                'framePosition' => $offset,
                'frameSize' => $limit
            ];
        } elseif ($orderBy != 'Price:null') {
            $query = [
                'instanceKey' => setOtcParams(),
                'language' => 'en',
                'xmlParameters' => '<SearchItemsParameters><ItemTitle>' . $search . '</ItemTitle><OrderBy>' . $orderBy . '</OrderBy><MinPrice>0.02</MinPrice><SearchMethod>' . $type . '</SearchMethod></SearchItemsParameters>',
                'framePosition' => $offset,
                'frameSize' => $limit
            ];
        } elseif ($offer == true) {
            $query = [
                'instanceKey' => setOtcParams(),
                'language' => 'en',
                'xmlParameters' => '<SearchItemsParameters><ItemTitle>' . $search . '</ItemTitle><Features><Feature Name="Discount">true</Feature></Features><MinPrice>0.02</MinPrice><SearchMethod>' . $type . '</SearchMethod></SearchItemsParameters>',
                'framePosition' => $offset,
                'frameSize' => $limit
            ];
        } else {
            $query = [
                'instanceKey' => setOtcParams(),
                'language' => 'en',
                'xmlParameters' => '<SearchItemsParameters><ItemTitle>' . $search . '</ItemTitle><OrderBy>Price:Asc</OrderBy><MinPrice>0.02</MinPrice><SearchMethod>' . $type . '</SearchMethod></SearchItemsParameters>',
                'framePosition' => $offset,
                'frameSize' => $limit
            ];
        }

        $client = new Client();
        $response = $client->request('GET', load_otc_api() . 'SearchItemsFrame', ['query' => $query]);

        $statusCode = $response->getStatusCode();
        if ($statusCode == 200) {
            $body = json_decode($response->getBody(), true);
            $result = getArrayKeyData($body, 'Result', []);
            $Items = getArrayKeyData($result, 'Items', []);
            $Content = getArrayKeyData($Items, 'Content', []);
            $TotalCount = getArrayKeyData($Items, 'TotalCount', 0);
            return ['Content' => $Content, 'TotalCount' => $TotalCount];
        }
        return ['Content' => [], 'TotalCount' => 0];
    }
}

if (!function_exists('otc_items_full_info')) {
    function otc_items_full_info($item_id)
    {
        //otc_items_full_info('520672721526')

        $query = [
            'instanceKey' => setOtcParams(),
            'language' => 'en',
            'itemId' => $item_id
        ];

        $client = new Client();
        $response = $client->request('GET', load_otc_api() . 'GetItemFullInfo', ['query' => $query]);

        if ($response->getStatusCode() == 200) {
            $body = json_decode($response->getBody(), true);
            if (is_array($body)) {
                return array_key_exists('OtapiItemFullInfo', $body) ? $body['OtapiItemFullInfo'] : [];
            }
        }
        return [];
    }
}

if (!function_exists('GetItemFullInfoWithDeliveryCosts')) {
    function GetItemFullInfoWithDeliveryCosts($item_id)
    {
        $query = [
            'instanceKey' => setOtcParams(),
            'language' => 'en',
            'itemId' => $item_id
        ];

        $client = new Client();
        $response = $client->request('GET', load_otc_api() . 'GetItemFullInfoWithDeliveryCosts', ['query' => $query]);

        if ($response->getStatusCode() == 200) {
            $body = json_decode($response->getBody(), true);
            if (is_array($body)) {
                return key_exists('OtapiItemFullInfo', $body) ? $body['OtapiItemFullInfo'] : [];
            }
        }
        return [];
    }
}

if (!function_exists('getDescription')) {
    function getDescription($item_id)
    {
        $query = [
            'instanceKey' => setOtcParams(),
            'language' => 'en',
            'itemId' => $item_id
        ];

        $client = new Client();
        $response = $client->request('GET', load_otc_api() . 'GetItemDescription', ['query' => $query]);

        if ($response->getStatusCode() == 200) {
            $content = json_decode($response->getBody(), true);
            if (is_array($content)) {
                return getArrayKeyData(
                    getArrayKeyData($content, 'OtapiItemDescription', []),
                    'ItemDescription',
                    []
                );
            }
        }
        return [];
    }
}

if (!function_exists('getSellerInformation')) {
    function getSellerInformation($VendorId)
    {
        $query = [
            'instanceKey' => setOtcParams(),
            'language' => 'en',
            'vendorId' => $VendorId
        ];

        $client = new Client();
        $response = $client->request('GET', load_otc_api() . 'GetVendorInfo', ['query' => $query]);

        if ($response->getStatusCode() == 200) {
            $content = json_decode($response->getBody(), true);
            if (is_array($content)) {
                return getArrayKeyData($content, 'VendorInfo', []);
            }
        }
        return [];
    }
}

if (!function_exists('products_from_same_vendor')) {
    function products_from_same_vendor($vendorId, $offset = 1, $limit = 24, $rate, $min = null, $max = null, $orderBy = null, $offer = false)
    {
        if ($min != null || $max != null) {
            $query = [
                'instanceKey' => setOtcParams(),
                'language' => 'en',
                'xmlParameters' => '<SearchItemsParameters><VendorId>' . $vendorId . '</VendorId><MinPrice>' . $min . '</MinPrice><MaxPrice>' . $max . '</MaxPrice></SearchItemsParameters>',
                'framePosition' => $offset,
                'frameSize' => $limit,
                'blockList' => '',
            ];
        } elseif ($orderBy != 'Price:null') {
            $query = [
                'instanceKey' => setOtcParams(),
                'language' => 'en',
                'xmlParameters' => '<SearchItemsParameters><VendorId>' . $vendorId . '</VendorId><OrderBy>' . $orderBy . '</OrderBy><MinPrice>0.02</MinPrice></SearchItemsParameters>',
                'framePosition' => $offset,
                'frameSize' => $limit,
                'blockList' => '',
            ];
        } elseif ($offer == true) {
            $query = [
                'instanceKey' => setOtcParams(),
                'language' => 'en',
                'xmlParameters' => '<SearchItemsParameters><VendorId>' . $vendorId . '</VendorId><Features><Feature Name="Discount">true</Feature></Features><MinPrice>0.02</MinPrice></SearchItemsParameters>',
                'framePosition' => $offset,
                'frameSize' => $limit,
                'blockList' => '',
            ];
        } else {
            $query = [
                'instanceKey' => setOtcParams(),
                'language' => 'en',
                'xmlParameters' => '<SearchItemsParameters><VendorId>' . $vendorId . '</VendorId><OrderBy>Price:Asc</OrderBy><MinPrice>0.02</MinPrice></SearchItemsParameters>',
                'framePosition' => $offset,
                'frameSize' => $limit,
                'blockList' => '',
            ];
        }

        $client = new Client();
        $response = $client->request('GET', load_otc_api() . 'BatchSearchItemsFrame', ['query' => $query]);

        $statusCode = $response->getStatusCode();
        if ($statusCode == 200) {
            $body = json_decode($response->getBody(), true);
            $result = getArrayKeyData($body, 'Result', []);
            $items = getArrayKeyData($result, 'Items', []);
            $items = getArrayKeyData($items, 'Items', []);
            $contents = getArrayKeyData($items, 'Content', []);

            $data = [];
            foreach ($contents as $content) {
                $img = getArrayKeyData($content, 'MainPictureUrl', []);
                $name = getArrayKeyData($content, 'Title', []);
                $product_code = getArrayKeyData($content, 'Id', []);
                $stock = getArrayKeyData($content, 'MasterQuantity', []);

                $Price = getArrayKeyData($content, 'Price', []);
                $price = getArrayKeyData($Price, 'MarginPrice', []);
                $regular_price = $price * $rate;
                $sale_price = $price * $rate;

                // $regular_price = get_product_regular_price($content, $rate);
                // $sale_price = get_product_sale_price($content, $rate);

                $rating = "";
                $total_sold = "";
                $featured_values = getArrayKeyData($content, 'FeaturedValues', []);
                foreach ($featured_values as $featured_value) {
                    if ($featured_value['Name'] == 'rating') {
                        $rating = $featured_value['Value'];
                    }

                    if ($featured_value['Name'] == 'TotalSales') {
                        $total_sold = $featured_value['Value'];
                    }
                }

                $content_data = [
                    'img' => $img,
                    'name' => $name,
                    'product_code' => $product_code,
                    'rating' => $rating,
                    'regular_price' => $regular_price,
                    'sale_price' => $sale_price,
                    'stock' => $stock,
                    'total_sold' => $total_sold
                ];
                array_push($data, $content_data);
            }

            $TotalCount = getArrayKeyData($items, 'TotalCount', 0);
            return [
                'TotalCount' => $TotalCount,
                'Content' => $data
            ];
        }

        return [
            'TotalCount' => 0,
            'Content' => []
        ];
    }
}

if (!function_exists('product_bulk_prices')) {
    function product_bulk_prices($itemId, $rate)
    {
        $query = [
            'instanceKey' => setOtcParams(),
            'language' => 'en',
            'itemId' => $itemId,
            'xmlRequest' => '',
            'blockList' => '',
        ];

        $client = new Client();
        $response = $client->request('GET', load_otc_api() . 'BatchGetSimplifiedItemConfigurationInfo', ['query' => $query]);

        $statusCode = $response->getStatusCode();
        if ($statusCode == 200) {
            $body = json_decode($response->getBody(), true);
            $result = getArrayKeyData($body, 'Result', []);

            $data = [];

            if (isset($result['Configuration']['QuantityRanges'])) {
                foreach ($result['Configuration']['QuantityRanges'] as $item) {
                    $item['Price']['Base'] *= $rate;
                    array_push($data, $item);
                }
            }
            // $result['Configuration']['QuantityRanges'] = $data;

            return $data;
        }
    }
}

if (!function_exists('otc_image_search_items')) {
    function otc_image_search_items($search, $offset = 0, $limit = 36, $rate)
    {
        // otc_search_items('bag', 'text', 0, 5)
        $query = [
            'instanceKey' => setOtcParams(),
            'language' => 'en',
            'xmlParameters' => '<SearchItemsParameters><ImageUrl>' . $search . '</ImageUrl></SearchItemsParameters>',
            'framePosition' => $offset,
            'frameSize' => $limit
        ];

        $client = new Client();
        $response = $client->request('GET', load_otc_api() . 'SearchItemsFrame', ['query' => $query]);

        $statusCode = $response->getStatusCode();
        if ($statusCode == 200) {
            $body = json_decode($response->getBody(), true);
            $result = getArrayKeyData($body, 'Result', []);
            $Items = getArrayKeyData($result, 'Items', []);
            $Content = getArrayKeyData($Items, 'Content', []);
            $TotalCount = getArrayKeyData($Items, 'TotalCount', 0);

            $data = [];
            foreach ($Content as $content) {
                $img = getArrayKeyData($content, 'MainPictureUrl', []);
                $name = getArrayKeyData($content, 'Title', []);
                $product_code = getArrayKeyData($content, 'Id', []);
                $stock = getArrayKeyData($content, 'MasterQuantity', []);

                $Price = getArrayKeyData($content, 'Price', []);
                $price = getArrayKeyData($Price, 'MarginPrice', []);
                $regular_price = $price * $rate;
                $sale_price = $price * $rate;

                $rating = "";
                $total_sold = "";
                $featured_values = getArrayKeyData($content, 'FeaturedValues', []);
                foreach ($featured_values as $featured_value) {
                    if ($featured_value['Name'] == 'rating') {
                        $rating = $featured_value['Value'];
                    }

                    if ($featured_value['Name'] == 'TotalSales') {
                        $total_sold = $featured_value['Value'];
                    }
                }

                $content_data = [
                    'img' => $img,
                    'name' => $name,
                    'product_code' => $product_code,
                    'rating' => $rating,
                    'regular_price' => $regular_price,
                    'sale_price' => $sale_price,
                    'stock' => $stock,
                    'total_sold' => $total_sold
                ];
                array_push($data, $content_data);
            }

            return [
                'Content' => $data,
                'TotalCount' => $TotalCount
            ];
        }
        return [
            'Content' => [],
            'TotalCount' => 0
        ];
    }
}

if (!function_exists('getSaleOfferProducts')) {
    function getSaleOfferProducts($item_id, $rate)
    {
        $query = [
            'instanceKey' => setOtcParams(),
            'language' => 'en',
            'itemId' => $item_id
        ];

        $client = new Client();
        $response = $client->request('GET', load_otc_api() . 'GetItemFullInfo', ['query' => $query]);

        $body = json_decode($response->getBody(), true);
        if (is_array($body)) {

            $OtapiItemFullInfo = array_key_exists('OtapiItemFullInfo', $body) ? $body['OtapiItemFullInfo'] : [];
            $MainPictureUrl = array_key_exists('MainPictureUrl', $OtapiItemFullInfo) ? $OtapiItemFullInfo['MainPictureUrl'] : [];

            $Price = array_key_exists('Price', $OtapiItemFullInfo) ? $OtapiItemFullInfo['Price'] : [];
            $MarginPrice = array_key_exists('MarginPrice', $Price) ? $Price['MarginPrice'] : [];
            $MarginPrice = $MarginPrice * $rate;

            return [
                'image' => $MainPictureUrl,
                'price' => $MarginPrice
            ];
        }

        return [];
    }
}

if (!function_exists('getSuperDealProducts')) {
    function getSuperDealProducts($search, $offset = 0, $limit = 6, $rate = 0)
    {
        $query = [
            'instanceKey' => setOtcParams(),
            'language' => 'en',
            'xmlParameters' => '<SearchItemsParameters>
                                <ItemTitle>' . $search . '</ItemTitle>
                                <Features>
                                <Feature Name="Discount">true</Feature>
                                </Features>
                                </SearchItemsParameters>',
            'framePosition' => $offset,
            'frameSize' => $limit,
            'blockList' => '',
        ];

        $client = new Client();
        $response = $client->request('GET', load_otc_api() . 'BatchSearchItemsFrame', ['query' => $query]);

        if ($response->getStatusCode() == 200) {
            $content = json_decode($response->getBody(), true);
            if (is_array($content)) {
                $Result = getArrayKeyData($content, 'Result', []);
                $Items = getArrayKeyData($Result, 'Items', []);
                $Items = getArrayKeyData($Items, 'Items', []);
                $Content = getArrayKeyData($Items, 'Content', []);

                $data = [];
                foreach ($Content as $content) {
                    $product_code = getArrayKeyData($content, 'Id', []);
                    $img = getArrayKeyData($content, 'MainPictureUrl', []);

                    $total_sold = "";
                    $featured_values = getArrayKeyData($content, 'FeaturedValues', []);
                    foreach ($featured_values as $featured_value) {
                        if ($featured_value['Name'] == 'TotalSales') {
                            $total_sold = $featured_value['Value'];
                        }
                    }

                    $Price = getArrayKeyData($content, 'Price', []);
                    $OMarginPrice = getArrayKeyData($Price, 'MarginPrice', []);
                    $original_price = $OMarginPrice * $rate;

                    $PromotionPrice = getArrayKeyData($content, 'PromotionPrice', []);
                    $MarginPrice = getArrayKeyData($PromotionPrice, 'MarginPrice', []);
                    $discount_price = $MarginPrice * $rate;

                    $PromotionPricePercent = getArrayKeyData($content, 'PromotionPricePercent', []);
                    $discount_percentage = getArrayKeyData($PromotionPricePercent[0], 'Percent', []);

                    $content_data = [
                        'product_code' => $product_code,
                        'img' => $img,
                        'original_price' => $original_price,
                        'discount_price' => $discount_price,
                        'discount_percentage' => $discount_percentage,
                        'total_sold' => $total_sold
                    ];

                    array_push($data, $content_data);
                }
            }

            return $data;
        }

        return [];
    }
}
