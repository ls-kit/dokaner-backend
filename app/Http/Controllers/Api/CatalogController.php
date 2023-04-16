<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Content\Frontend\Wishlist;
use App\Models\Content\Post;
use App\Models\Content\Product;
use App\Models\Content\SearchLog;
use App\Models\Content\Taxonomy;
use App\Traits\ApiResponser;
use Illuminate\Support\Str;
// use Validator;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class CatalogController extends Controller
{
    use ApiResponser;

    public function categories()
    {
        $categories = Taxonomy::whereNotNull('active')
            ->select('name', 'slug', 'description', 'ParentId', 'icon', 'picture', 'otc_id',  'IconImageUrl', 'ApproxWeight', 'is_top')
            ->withCount('children')
            ->get();

        return $this->success([
            'categories' => $categories
        ]);
    }

    public function banners()
    {
        $banners = Post::where('post_type', 'banner')
            ->where('post_status', 'publish')
            ->limit(5)
            ->latest()
            ->select('id', 'post_title', 'post_slug', 'post_content', 'post_excerpt', 'post_thumb', 'thumb_directory', 'thumb_status')
            ->get();

            $banners_mobile = Post::where('post_type', 'banner')
            ->where('post_status', 'publish_mobile')
            ->limit(5)
            ->latest()
            ->select('id', 'post_title', 'post_slug', 'post_content', 'post_excerpt', 'post_thumb', 'thumb_directory', 'thumb_status')
            ->get();

        return $this->success([
            'banners' => $banners,
            'mobileBanners' => $banners_mobile
        ]);
    }

    public function categoryProducts($cat_slug)
    {
        $offset = request('offset', 0);
        $limit = request('limit', 36);
        $rate = request('rate', get_setting('increase_rate', 20));
        $min = request('minPrice', null);
        $max = request('maxPrice', null);
        $orderBy = request('orderBy', null);

        $taxonomy = Taxonomy::where('slug', $cat_slug)->whereNotNull('active')->first();

        if (!$taxonomy) {
            return $this->error('Category not found!', 417);
        }

        if ($taxonomy->ProviderType === 'Taobao') {
            $otc_id = $taxonomy->otc_id;
            $products = get_category_browsing_items($otc_id, 'category',  $offset, $limit, $rate, $min, $max, $orderBy);
            // $keyword = $taxonomy->keyword ? $taxonomy->keyword : $taxonomy->name;
            // $products = get_category_browsing_items($keyword, 'text',  $offset, $limit);
        } else {
            $otc_id = $taxonomy->otc_id;
            $products = get_category_browsing_items($otc_id, 'category',  $offset, $limit, $rate, $min, $max, $orderBy);
            // $keyword = $taxonomy->keyword ? $taxonomy->keyword : $taxonomy->name;
            // $products = get_category_browsing_items($keyword, 'text',  $offset, $limit);
        }

        return $this->success([
            'products' => json_encode($products),
        ]);
    }

    public function searchProcess()
    {
        $text = request('search');
        if (!$text) {
            return $this->success([], 'Search text must not empty', 417);
        }
        $search_type = 'text';
        if (request()->hasFile('search')) {
            $search_type = 'picture';
        }
        $log = SearchLog::create([
            'search_id' => Str::random(30),
            'search_type' => $search_type,
            'query_data' => $text,
            'user_id' => auth()->check() ? auth()->id() : null
        ]);

        return $this->success([
            'search_id' => $log->search_id ?? ''
        ]);
    }

    public function getSearchResult($searchKey)
    {
        $offset = request('offset', 0);
        $limit = request('limit', 36);
        $min = request('minPrice', null);
        $max = request('maxPrice', null);
        $orderBy = request('orderBy', null);
        $rate = request('rate', get_setting('increase_rate', 20));
        $offer = request('offer', false);

        $products = get_category_browsing_items($searchKey, 'text',  $offset, $limit, $rate, $min, $max, $orderBy, $offer);

        return $this->success([
            'products' => json_encode($products)
        ]);
    }

    public function getPictureSearchResult($search_id)
    {
        $offset = request('offset', 0);
        $limit = request('limit', 36);
        $rate = request('rate', get_setting('increase_rate', 20));

        $SearchLog = SearchLog::where('search_id', $search_id)->where('search_type', 'picture')->first();
        if ($SearchLog) {
            // $products = get_category_browsing_items($SearchLog->query_data, 'picture',  $offset, $limit);
            $products = otc_image_search_items('https://admin.1688cart.com/' .  $SearchLog->query_data, $offset, $limit, $rate);

            return $this->success([
                'products' => json_encode($products),
                'picture' => 'https://admin.1688cart.com/' .  $SearchLog->query_data
            ]);
        }

        return $this->error('Picture search no more valid', 417);
    }

    public function searchPicture()
    {
        // $validator = Validator::make(request()->all(), [
        //   'picture' => 'required | max:10000 | mimes: jpeg, jpg, png, webp, gif',
        // ]);

        // if ($validator->fails()) {
        //   return $this->error('Validation fail', 422);
        // }

        $offset = request('offset', 0);
        $limit = request('limit', 36);

        $search = '';
        if (request()->hasFile('picture')) {
            $file = request()->file('picture');
            $name = $file->getClientOriginalName();
            $name = pathinfo($name, PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $newFilename = time() . '.' . $extension;
            $path = 'storage/search/' . date('Y-m');

            create_public_directory($path);
            $file->move($path, $newFilename);

            $location = $path . '/' . $newFilename;

            $search_id = Str::random(30);
            $log = SearchLog::create([
                'search_id' => $search_id,
                'search_type' => 'picture',
                'query_data' => $location,
                'user_id' => auth()->check() ? auth()->id() : null,
            ]);

            return $this->success([
                'search_id' => $search_id,
                'picture' => $location
            ]);
        }

        return $this->error('Picture upload fails! Try again', 417);
    }

    public function productDetails($item_id)
    {
        $rate = request('rate', get_setting('increase_rate', 20));

        $item = GetItemFullInfoWithDeliveryCosts($item_id);
        $bulkPrices = product_bulk_prices($item_id, $rate);

        $item['BulkPrices'] = $bulkPrices;

        if (!empty($item)) {
            $this->storeProductToDatabase($item, $item_id);
            return $this->success([
                'item' => $item
            ]);
        }
        return $this->error('Product not found', 417);
    }


    public function productDescription($item_id)
    {
        $description = getDescription($item_id);
        if (!empty($description)) {
            return $this->success([
                'description' => $description
            ]);
        }
        return $this->error('some error occurred', 417);
    }


    public function productSellerInfo($VendorId)
    {
        $VendorInfo = getSellerInformation($VendorId);
        if (!empty($VendorInfo)) {
            return $this->success([
                'VendorInfo' => $VendorInfo
            ]);
        }
        return $this->error('some error occurred', 417);
    }


    public function storeProductToDatabase($product, $item_id)
    {
        if (is_array($product)) {
            $product_id = key_exists('Id', $product) ? $product['Id'] : 0;
            $PhysicalParameters = key_exists('PhysicalParameters', $product) ? $product['PhysicalParameters'] : [];
            $Price = key_exists('Price', $product) ? $product['Price'] : [];
            $Promotions = key_exists('Promotions', $product) ? $product['Promotions'] : [];
            $Price = checkPromotionalPrice($Promotions, $Price);

            $Pictures = key_exists('Pictures', $product) ? $product['Pictures'] : [];
            $Features = key_exists('Features', $product) ? $product['Features'] : [];
            $VendorId = key_exists('VendorId', $product) ? $product['VendorId'] : '';
            $auth_id = \auth()->check() ? \auth()->id() : null;

            try {
                $test =  Product::updateOrInsert(
                    ['ItemId' => $item_id, 'VendorId' => $VendorId],
                    [
                        'active' => now(),
                        'ProviderType' => $product['ProviderType'] ?? '',
                        'Title' => $product['Title'] ?? '',
                        'CategoryId' => key_exists('CategoryId', $product) ? $product['CategoryId'] : '',
                        'ExternalCategoryId' => key_exists('ExternalCategoryId', $product) ? $product['ExternalCategoryId'] : '',
                        'VendorName' => key_exists('VendorName', $product) ? $product['VendorName'] : '',
                        'VendorScore' => key_exists('VendorScore', $product) ? $product['VendorScore'] : '',
                        'PhysicalParameters' => json_encode($PhysicalParameters),
                        'BrandId' => $product['BrandId'] ?? '',
                        'BrandName' => $product['BrandName'] ?? '',
                        'TaobaoItemUrl' => key_exists('TaobaoItemUrl', $product) ? $product['TaobaoItemUrl'] : '',
                        'ExternalItemUrl' => key_exists('ExternalItemUrl', $product) ? $product['ExternalItemUrl'] : '',
                        'MainPictureUrl' => key_exists('MainPictureUrl', $product) ? $product['MainPictureUrl'] : '',
                        'Price' => json_encode($Price ?? []),
                        'Pictures' => json_encode($Pictures ?? []),
                        'Features' => json_encode($Features ?? []),
                        'MasterQuantity' => key_exists('MasterQuantity', $product) ? $product['MasterQuantity'] : '',
                        'user_id' => $auth_id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            } catch (\Throwable $e) {
                return response(['status' => false, 'message' => $e]);
            }
        }
    }

    public function sameVendorProducts($VendorId)
    {
        $offset = request('offset', 0);
        $limit = request('limit', 36);
        $rate = request('rate', get_setting('increase_rate', 20));
        $min = request('minPrice', null);
        $max = request('maxPrice', null);
        $orderBy = request('orderBy', null);
        $offer = request('offer', false);

        $VendorProducts = products_from_same_vendor($VendorId, $offset, $limit, $rate, $min, $max, $orderBy, $offer);
        if (!empty($VendorProducts)) {
            return $this->success([
                'VendorProducts' => json_encode($VendorProducts)
            ]);
        }
        return $this->error('some error occurred', 417);
    }

    public function productBulkPrices($itemId)
    {
        $bulkPrices = product_bulk_prices($itemId, 0);
        if (!empty($bulkPrices)) {
            return $this->success([
                'bulkPrices' => $bulkPrices
            ]);
        }
        return $this->error('some error occurred', 417);
    }

    public function checkoutDiscounts()
    {
        $bkash_first_payment = get_setting('checkout_bkash_payment_first');
        $bkash_first_discount = get_setting('checkout_bkash_discount_first');
        $bkash_second_payment = get_setting('checkout_bkash_payment_second');
        $bkash_second_discount = get_setting('checkout_bkash_discount_second');
        $bkash_third_payment = get_setting('checkout_bkash_payment_third');
        $bkash_third_discount = get_setting('checkout_bkash_discount_third');

        $nagad_first_payment = get_setting('checkout_nagad_payment_first');
        $nagad_first_discount = get_setting('checkout_nagad_discount_first');
        $nagad_second_payment = get_setting('checkout_nagad_payment_second');
        $nagad_second_discount = get_setting('checkout_nagad_discount_second');
        $nagad_third_payment = get_setting('checkout_nagad_payment_third');
        $nagad_third_discount = get_setting('checkout_nagad_discount_third');

        $bank_first_payment = get_setting('checkout_bank_payment_first');
        $bank_first_discount = get_setting('checkout_bank_discount_first');
        $bank_second_payment = get_setting('checkout_bank_payment_second');
        $bank_second_discount = get_setting('checkout_bank_discount_second');
        $bank_third_payment = get_setting('checkout_bank_payment_third');
        $bank_third_discount = get_setting('checkout_bank_discount_third');

        return response()->json([
            'status' => 'Success',
            'data' => [
                'bkash_payment' => [
                    [
                        'payment_completion' => $bkash_first_payment,
                        'discount' => $bkash_first_discount
                    ],
                    [
                        'payment_completion' => $bkash_second_payment,
                        'discount' => $bkash_second_discount
                    ],
                    [
                        'payment_completion' => $bkash_third_payment,
                        'discount' => $bkash_third_discount
                    ]
                ],
                'nagad_payment' => [
                    [
                        'payment_completion' => $nagad_first_payment,
                        'discount' => $nagad_first_discount
                    ],
                    [
                        'payment_completion' => $nagad_second_payment,
                        'discount' => $nagad_second_discount
                    ],
                    [
                        'payment_completion' => $nagad_third_payment,
                        'discount' => $nagad_third_discount
                    ]
                ],
                'bank_payment' => [
                    [
                        'payment_completion' => $bank_first_payment,
                        'discount' => $bank_first_discount
                    ],
                    [
                        'payment_completion' => $bank_second_payment,
                        'discount' => $bank_second_discount
                    ],
                    [
                        'payment_completion' => $bank_third_payment,
                        'discount' => $bank_third_discount
                    ]
                ],
            ]
        ]);
    }

    public function image_search_items()
    {
        $image = request('image', null);
        $offset = request('offset', 0);
        $limit = request('limit', 36);
        $rate = request('rate', get_setting('increase_rate', 20));

        return otc_image_search_items($image, $offset, $limit, $rate);
    }
}
