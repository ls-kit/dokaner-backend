<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\SManagerPaymentController;
use App\Models\Content\Coupon;
use App\Models\Content\CouponUser;
use App\Models\Content\Frontend\CustomerCart;
use App\Models\Content\Frontend\Wishlist;
use App\Models\Content\Invoice;
use App\Models\Content\Order;
use App\Models\Content\OrderItem;
use App\Models\Content\OrderItemVariation;
use App\Models\Content\Product;
use App\Traits\ApiResponser;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class WishlistController extends Controller
{
  use ApiResponser;

  public function AddToWishList()
  {
    $product = request('product');

    if (request('shopAsCustomer') == true) {
        $auth_id = request('id');
    } else {
        $auth_id = auth()->id();
    }

    $wishlists = [];
    if ($product && $auth_id) {
      $product = json_decode($product, true);
      $rate = get_setting('increase_rate', 20);

      $img = getArrayKeyData($product, 'img');
      $name = getArrayKeyData($product, 'name');
      $product_code = getArrayKeyData($product, 'product_code');
      $rating = getArrayKeyData($product, 'rating');
      $regular_price = getArrayKeyData($product, 'regular_price');
      $sale_price = getArrayKeyData($product, 'sale_price');
      $stock = getArrayKeyData($product, 'stock');
      $total_sold = getArrayKeyData($product, 'total_sold');

      $NewPrice = get_product_regular_price($product, $rate);
      $SalePrice = get_product_sale_price($product, $rate);
      $NewTotalSold = get_features_value($product, 'TotalSales');
      $ItemId = $product_code ? $product_code : ($product['Id'] ?? '');
      $wishlist = Wishlist::UpdateOrCreate(
        ['user_id' => $auth_id, 'ItemId' => $ItemId],
        [
          'img' => $img ? $img : get_product_picture($product),
          'name' => $name ? $name : ($product['Title'] ?? ''),
          'rating' => $rating ? $rating : ($product['rating'] ?? ''),
          'regular_price' => $regular_price ? $regular_price : $NewPrice,
          'sale_price' => $sale_price ? $sale_price : $SalePrice,
          'stock' => $product['MasterQuantity'] ?? $stock,
          'total_sold' => $total_sold ? $total_sold : $NewTotalSold,
        ]
      );
      $wishlists = Wishlist::where('user_id', $auth_id)->get();
    }
    return $this->success([
      'wishlists' => $wishlists
    ]);
  }

  public function getCustomerWishList()
  {
    if (request('shopAsCustomer') == true) {
        $auth_id = request('id');
    } else {
        $auth_id = auth()->id();
    }

    $wishlists = [];
    if ($auth_id) {
      $wishlists = Wishlist::where('user_id', $auth_id)->get();
    }
    return $this->success([
      'auth_id' => $auth_id,
      'wishlists' => $wishlists
    ]);
  }

  public function removeCustomerWishList()
  {
    if (request('shopAsCustomer') == true) {
        $auth_id = request('id');
    } else {
        $auth_id = auth()->id();
    }

    $item_id = request('item_id');
    $wishlists = [];
    if ($auth_id && $item_id) {
      Wishlist::where('ItemId', $item_id)
        ->where('user_id', $auth_id)
        ->update(['deleted_at' => now()]);
      $wishlists = Wishlist::where('user_id', $auth_id)->get();
    }
    return $this->success([
      'wishlists' => $wishlists
    ]);
  }
}
