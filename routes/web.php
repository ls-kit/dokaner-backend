<?php

use App\Http\Controllers\LanguageController;
use App\Http\Controllers\Frontend\HomeController;
use GuzzleHttp\Client;
use App\Models\Auth\User;
use App\Models\Content\OrderItem;
use App\Models\Content\SubApiOrder;

Route::get('clear-all', function () {
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    Artisan::call('route:clear');
    echo "<h1 style='color: green;'>Cache Cleaned!</h1>";
});

Route::get('/dump', function () {
    // $query = [
    //     'instanceKey' => '7367999f-de6f-4e88-9d36-1642cff1746b',
    //     'language' => 'en',
    //     'itemId' => 'abb-38617121088'
        // 'vendorId' => 'abb-b2b-1833723532'
        // 'xmlParameters' => '<SearchItemsParameters>
        //                     <ItemTitle>Water Bottle</ItemTitle>
        //                     <Features>
        //                     <Feature Name="Discount">true</Feature>
        //                     </Features>
        //                     </SearchItemsParameters>',
        // 'framePosition' => 0,
        // 'frameSize' => 10,
        // 'blockList' => '',
    // ];

    // $client = new Client();
    // $response = $client->request('GET', load_otc_api() . 'GetItemFullInfoWithDeliveryCosts', ['query' => $query]);

    // if ($response->getStatusCode() == 200) {
    //     $content = json_decode($response->getBody(), true);
    //     if (is_array($content)) {
    //         $Result = getArrayKeyData($content, 'Result', []);
    //         $Items = getArrayKeyData($Result, 'Items', []);
    //         $Items = getArrayKeyData($Items, 'Items', []);
    //         $Content = getArrayKeyData($Items, 'Content', []);

    //         $data = [];
    //         $rate = get_setting('increase_rate', 20);
    //         foreach ($Content as $content) {
    //             $product_code = getArrayKeyData($content, 'Id', []);
    //             $img = getArrayKeyData($content, 'MainPictureUrl', []);

    //             $total_sold = "";
    //             $featured_values = getArrayKeyData($content, 'FeaturedValues', []);
    //             foreach ($featured_values as $featured_value) {
    //                 if ($featured_value['Name'] == 'TotalSales') {
    //                     $total_sold = $featured_value['Value'];
    //                 }
    //             }

    //             $PromotionPrice = getArrayKeyData($content, 'PromotionPrice', []);
    //             $MarginPrice = getArrayKeyData($PromotionPrice, 'MarginPrice', []);
    //             $discount_price = $MarginPrice * $rate;

    //             $PromotionPricePercent = getArrayKeyData($content, 'PromotionPricePercent', []);
    //             $discount_percentage = getArrayKeyData($PromotionPricePercent[0], 'Percent', []);

    //             $content_data = [
    //                 'product_code' => $product_code,
    //                 'img' => $img,
    //                 'discount_price' => $discount_price,
    //                 'discount_percentage' => $discount_percentage,
    //                 'total_sold' => $total_sold
    //             ];
    //             array_push($data, $content_data);
    //         }
    //         return $Content;
    //     }

        // return [
        //   'image' => $content['OtapiItemFullInfo']['MainPictureUrl'],
        //   'price' => $content['OtapiItemFullInfo']['Price']['MarginPrice']
        // ];
    // }
    // return [];
    // return $response;

    // update_order_tracker();
    SubApiOrder::updateOrCreate(
        [
            'domain' => 'asd'
        ],
        [
            'total_invoices' => 2,
            'total_orders' => 3
        ]
    );
    // echo "done!";
});

/*
 * Global Routes
 * Routes that are used between both frontend and backend.
 */

// Switch between the included languages
Route::get('lang/{lang}', [LanguageController::class, 'swap']);

/*
 * Frontend Routes
 * Namespaces indicate folder structure
 */
Route::group(['namespace' => 'Frontend', 'as' => 'frontend.'], function () {
    include_route_files(__DIR__ . '/frontend/');
});

/*
 * Backend Routes
 * Namespaces indicate folder structure
 */
Route::group(['namespace' => 'Backend', 'prefix' => 'admin', 'as' => 'admin.', 'middleware' => 'admin'], function () {
    /*
     * These routes need view-backend permission
     * (good if you want to allow more than one group in the backend,
     * then limit the backend features by different roles or permissions)
     *
     * Note: Administrator has all permissions so you do not have to specify the administrator role everywhere.
     * These routes can not be hit if the password is expired
     */
    include_route_files(__DIR__ . '/backend/');
});
