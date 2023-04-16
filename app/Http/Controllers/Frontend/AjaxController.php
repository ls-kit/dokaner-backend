<?php

namespace App\Http\Controllers\Frontend;

use Carbon\Carbon;
use App\Models\Content\Order;
use App\Models\Content\Product;
use App\Models\Content\Taxonomy;
use App\Models\Content\OrderItem;
use App\Http\Controllers\Controller;
use App\Models\Content\Coupon;
use App\Models\Content\CouponUser;
use Illuminate\Support\Facades\Auth;
use App\Models\Content\OrderItemVariation;
use App\Models\Content\Frontend\CustomerCart;
use App\Models\Content\Frontend\EmailSubscriber;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Class HomeController.
 */
class AjaxController extends Controller
{
  public function categoryProducts()
  {
    $cat_id = request('cat_id');
    $subcat_id = request('subcat_id');
    $offset = request('offset');
    $limit = request('limit');

    $category = Taxonomy::where('id', $cat_id)->whereNotNull('active')->firstOrFail();
    $subcategory = Taxonomy::where('id', $subcat_id)->where('ParentId', $category->otc_id)->whereNotNull('active')->first();

    $search = $category->keyword ? $category->keyword : $category->name;

    if ($subcategory) {
      $search = $subcategory->keyword ? $subcategory->keyword : $subcategory->name;
    }
    $items = $this->browsing_cache($search, '', $offset, $limit);

    $renderData = '';
    $status = false;
    if (!empty($items)) {
      $status = true;
      $renderData = view('frontend.ajaxComponent.singleProduct', compact('items', 'category', 'subcategory'))->render();
    }

    return response(['status' => $status, 'data' => $renderData]);
  }

  public function searchProducts()
  {
    $search = request('search');
    $type = request('type');
    if ($type == 'picture') {
      $image = request('image');
      $search = asset($image);
      //  dd($search);
    }
    $offset = request('offset');
    $limit = request('limit', 400);
    $items = $this->browsing_cache($search, $type, $offset, $limit);

    $renderData = '';
    $redirect = null;
    $status = false;
    if (!empty($items)) {
      $status = true;
      if (count($items) == 1) {
        $redirectItem = $items[0] ?? [];
        $product_id = key_exists('Id', $redirectItem) ? $redirectItem['Id'] : 0;
        $redirect = '/product/' . $product_id;
      } else {
        $renderData = view('frontend.ajaxComponent.singleProduct', compact('items'))->render();
      }
    }

    return response(['status' => $status, 'data' => $renderData, 'redirect' => $redirect]);
  }

  public function browsing_cache($search, $type, $offset, $limit)
  {
    $key = Str::slug($search) . '_' . md5($search);
    $browsing = get_browsing_data($key, true);
    $newLoad = false;
    $items = null;
    // dd($browsing);
    $countBrowsing = count($browsing);

    if ($countBrowsing) {
      if ($countBrowsing > $offset) {
        $items = array_slice($browsing, $offset, $limit);
        if (!empty($items)) {
          return $items;
        }
        $newLoad = true;
      } else {
        $newLoad = true;
      }
    } else {
      $newLoad = true;
    }

    if ($newLoad) {
      $items = otc_search_items($search, $type, $offset, 200);

      if (is_array($items)) {
        if (!empty($items)) {
          if (!empty($browsing)) {
            $mergeItems = array_merge($browsing, $items);
            store_browsing_data($key, $mergeItems);
            $items = array_slice($mergeItems, $offset, $limit);
          } else {
            store_browsing_data($key, $items);
            $items = array_slice($items, $offset, $limit);
          }
        }
      }
    }
    return $items;
  }

  public function getAdditionalInformation()
  {
    $item_id = request('item_id');
    $item = $this->getFullInfo($item_id);
    $status = true;
    if (empty($item)) {
      $status = false;
    }

    $main = $status ? view('frontend.ajaxComponent.additionalInformation', compact('item'))->render() : [];
    $additional = $status ? view('frontend.ajaxComponent.additionalInfo', compact('item'))->render() : [];

    return response([
      'status' => $status,
      'main' => $main,
      'additional' => $additional
    ]);
  }

  public function getItemDescription()
  {
    $item_id = request('item_id');
    $description = getDescription($item_id);

    $status = false;
    $data = '';
    if ($description) {
      $status = true;
      $data = view('frontend.ajaxComponent.ItemDescription', compact('description'))->render();
    }
    return response(['status' => $status, 'data' => $data]);
  }

  public function getItemSellerInformation()
  {
    $vendorId = request('vendor_id');
    $sellerInformation = getSellerInformation($vendorId);
    $status = false;
    $data = '';
    if ($sellerInformation) {
      $status = true;
      $data = view('frontend.ajaxComponent.sellerInformation', compact('sellerInformation'))->render();
    }
    return response(['status' => $status, 'data' => $data]);
  }

  public function getFullInfo($item_id)
  {
    // $fullInfo = get_browsing_data('fullInfo', true);

    // if (is_array($fullInfo)) {
    //   $item = key_exists($item_id, $fullInfo) ? $fullInfo[$item_id] : [];
    //   if (!empty($item)) {
    //     return $item;
    //   }
    // }

    $itemData = GetItemFullInfoWithDeliveryCosts($item_id);

    if (!empty($itemData)) {
      //   $fullInfo[$item_id] = $itemData;
      //   store_browsing_data('fullInfo', $fullInfo);
      $this->updateOrInsertToProduct($itemData);
    }

    return $itemData;
  }

  public function getPhysicalParameters()
  {
    $item_id = request('item_id');
    $product = Product::where('ItemId', $item_id)->first();
    $PhysicalParameters = [];
    if ($product) {
      $PhysicalParameters = $product->PhysicalParameters ? json_decode($product->PhysicalParameters) : [];
    }
    if (empty($PhysicalParameters)) {
      $PhysicalParameters = otc_get_physicalParameters($item_id);
      if ($product) {
        $params = empty($PhysicalParameters) ? [] : json_encode($PhysicalParameters);
        $product->update([
          'PhysicalParameters' => $params,
        ]);
      }
    }

    $status = !empty($PhysicalParameters) ? true : false;

    return response(['status' => $status, 'PhysicalParameters' => $PhysicalParameters]);
  }

  public function orderConfirm()
  {
    $transaction_id = trim(request('order_id'));
    $products = json_decode(request('OrderItem', []), true);
    $summary = collect(json_decode(request('summary', []), true));
    $address = collect(json_decode(request('address', []), true));
    //dd($products);

    $user = \auth()->user();
    $auth_id = \auth()->id();

    $orderStore = null;

    $orderStore = $this->orderStore($transaction_id, $summary, $address);
    $order_id = $orderStore->id ?? null;
    $coupon_victory = $orderStore->coupon_victory ? $orderStore->coupon_victory : null;
    $order_amount = $orderStore->amount ?? null;
    foreach ($products as $key => $items) {
      $product_id = $key;
      $orderItem = null;
      $product_item_id = null;
      $localDelivery = 0;
      $itemTotalQuantity = 0;
      $approxWeight = 0;
      $itemTotalPrice = 0;

      foreach ($items as $key => $item) {
        $title = key_exists('name', $item) ? $item['name'] : '';
        $mainImage = key_exists('mainImage', $item) ? $item['mainImage'] : '';
        $approxWeight = key_exists('approxWeight', $item) ? floatval($item['approxWeight']) : 0;
        $localDelivery = key_exists('localDelivery', $item) ? $item['localDelivery'] : '';
        $shipped_by = key_exists('shipped_by', $item) ? $item['shipped_by'] : 'air';
        $shippingRate = key_exists('shippingRate', $item) ? $item['shippingRate'] : '';
        $QuantityRanges = key_exists('QuantityRanges', $item) ? $item['QuantityRanges'] : '';

        $attributes = key_exists('attributes', $item) ? $item['attributes'] : [];
        $itemCode = key_exists('itemCode', $item) ? $item['itemCode'] : [];
        $price = key_exists('price', $item) ? $item['price'] : 0;
        $quantity = key_exists('quantity', $item) ? $item['quantity'] : 0;
        $subTotal = $quantity * $price;
        $itemTotalPrice += $subTotal;
        $itemTotalQuantity += $quantity;

        if ($key == 0) {
          $product = Product::where('ItemId', $product_id)->first();
          if (!$product) {
            $product = $this->updateOrInsertToProduct($product_id);

            if (!$product) {
              return response([
                "status" => false
              ]);
            }
          }
          $product_item_id = $product->id ?? null;
          $orderItem = OrderItem::create([
            'order_id' => $order_id,
            'product_id' => $product_item_id,
            'image' => $mainImage,
            'name' => $title,
            'link' => "/product/{$product_id}",
            'quantityRanges' => json_encode($QuantityRanges) ?? '',
            'shipped_by' => $shipped_by,
            'shipping_rate' => $shippingRate,
            'chinaLocalDelivery' => $localDelivery,
            'status' => 'Waiting for Payment',
            'user_id' => $auth_id
          ]);
        } // end condition

        if ($orderItem) {
          $itemImage = check_attribute_image($attributes, $mainImage);
          OrderItemVariation::create([
            'itemCode' => $itemCode,
            'order_item_id' => $orderItem->id,
            'product_id' => $product_item_id,
            'attributes' => is_array($attributes) ? json_encode($attributes) : json_encode([]),
            'image' => $itemImage,
            'price' => $price,
            'quantity' => $quantity,
            'subTotal' => $subTotal,
            'user_id' => $auth_id,
          ]);
        } // end condition

      } // end product loop

      if ($orderItem) {
        $order_item_number = generate_order_number($orderItem->id);
        $itemTotal = $itemTotalPrice + $localDelivery;
        $contribution = coupon_contribution($order_amount, $itemTotal, $coupon_victory);
        $half_payment = ($itemTotal - $contribution) * 0.50;
        $orderItem->update([
          'order_item_number' => $order_item_number,
          'quantity' => $itemTotalQuantity,
          'product_value' => $itemTotalPrice,
          'first_payment' => $half_payment,
          'due_payment' => $half_payment,
          'approxWeight' => floating($approxWeight, 3),
          'coupon_contribution' => $contribution,
        ]);
      } // end condition

    }

    CustomerCart::where('user_id', $auth_id)->whereNull('buy_status')->update([
      'buy_status' => Carbon::now()->toDateTimeString()
    ]);


    return response([
      'status' => true,
      'tran_id' => $transaction_id,
      'message' => 'Data save successfully',
      'data' => $orderStore
    ]);
  }

  public function orderStore($transaction_id, $summary, $address)
  {
    //order_number
    $user = auth()->user();
    $couponCode = $summary['couponCode'] ?? null;
    $couponDiscount = $summary['couponDiscount'] ?? null;
    $productTotal = $summary['productTotal'] ?? null;
    $needToPay = $summary['needToPay'] ?? null;
    $dueForProducts = $summary['dueForProducts'] ?? null;

    $order = Order::create([
      'name' => $user->full_name ?? $user->name ?? $user->first_name ?? 'No Name',
      'user_id' => $user->id,
      'email' => $user->email,
      'phone' => $user->phone ?? '',
      'amount' => $productTotal,
      'coupon_code' => $couponCode,
      'coupon_victory' => $couponDiscount,
      'needToPay' => $needToPay,
      'dueForProducts' => $dueForProducts,
      'status' => 'Waiting for Payment',
      'address' => json_encode($address),
      'transaction_id' => $transaction_id,
      'currency' => 'BDT',
    ]);
    $order_number = generate_order_number($order->id);
    $order->update(['order_number' => $order_number]);

    if ($couponCode) {
      $findCoupon = Coupon::where('coupon_code', $couponCode)->first();
      CouponUser::create([
        'coupon_id' => $findCoupon->id,
        'coupon_code' => $couponCode,
        'coupon_details' => '',
        'win_amount' => $couponDiscount,
        'order_id' => $order->id,
        'user_id' => $user->id,
      ]);
    }

    return $order;
  }


  public function updateOrInsertToProduct($product = [])
  {
    if (!is_array($product)) {
      $product = GetItemFullInfoWithDeliveryCosts($product);
    }
    $product_id = key_exists('Id', $product) ? $product['Id'] : 0;

    $storeProduct = Product::where('ItemId', $product_id)->first();

    $PhysicalParameters = [];
    if ($storeProduct) {
      $PhysicalParameters = $storeProduct->PhysicalParameters ? json_decode($storeProduct->PhysicalParameters) : [];
    }

    $PhysicalParameters = empty($PhysicalParameters) ? otc_get_physicalParameters($product_id) : [];
    $Price = key_exists('Price', $product) ? $product['Price'] : [];
    $Promotions = key_exists('Promotions', $product) ? $product['Promotions'] : [];
    $Price = checkPromotionalPrice($Promotions, $Price);

    if ($storeProduct) {
      $storeProduct->update([
        'PhysicalParameters' => json_encode($PhysicalParameters),
        'Price' => json_encode($Price),
      ]);
    } else {
      $Pictures = key_exists('Pictures', $product) ? $product['Pictures'] : [];
      $Features = key_exists('Features', $product) ? $product['Features'] : [];
      $storeProduct = $this->create_product($PhysicalParameters, $Price, $product_id, $product, $Pictures, $Features);
    }

    return $storeProduct;
  }


  public function create_product($PhysicalParameters, $Price, $product_id, $product, $Pictures, $Features)
  {
    $VendorId = key_exists('VendorId', $product) ? $product['VendorId'] : '';
    $auth_id = \auth()->check() ? \auth()->id() : null;
    return Product::updateOrCreate(
      ['ItemId' => $product_id, 'VendorId' => $VendorId],
      [
        'active' => now(),
        'ItemId' => $product_id,
        'ProviderType' => $product['ProviderType'] ?? '',
        'Title' => $product['Title'] ?? '',
        'VendorId' => $VendorId ?? '',
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
  }


  public function subscribeEmail()
  {
    $validate = request()->validate([
      'email' => 'string|email|max:155',
    ]);
    $email = request('email');

    $subscriber = EmailSubscriber::where('email', $email)->first();

    if (!$subscriber) {
      EmailSubscriber::create($validate);
    }

    return response()->json(['status' => true]);
  }

  public function updateCustomerCheckout()
  {
    $cart = json_decode(request('cart'), true);
    $finalCart = request('finalCart');
    $status = $this->updateCustomerCart($cart, true, $finalCart);
    return response(['status' => true]);
  }

  public function updateCustomerCart($cart, $remove = null, $finalCart = false)
  {
    $status = false;
    if (auth()->check()) {
      $user_id = \auth()->id();
      if (!empty($cart)) {
        foreach ($cart as $key => $items) {
          $item_details = [];
          $ItemData = [];
          $ItemId = '';
          $QuantityRanges = [];
          $minQuantity = 0;
          $localDelivery = 0;
          $shipped_by = '';
          $shippingRate = 0;
          $approxWeight = 0;
          foreach ($items as $item) {
            $ItemId = $item['itemCode'] ?? '';
            $QuantityRanges = $item['QuantityRanges'] ?? [];
            $minQuantity = $item['FirstLotQuantity'] ?? 0;
            $localDelivery = $item['localDelivery'] ?? 0;
            $shipped_by = $item['shipped_by'] ?? 0;
            $shippingRate = $item['shippingRate'] ?? 0;
            $approxWeight = $item['approxWeight'] ?? 0;

            array_push($item_details, [
              "title" => $item['name'] ?? '',
              "mainImage" => $item['mainImage'] ?? '',
              "BatchLotQuantity" => $item['BatchLotQuantity'] ?? 0
            ]);

            array_push($ItemData, [
              "itemCode" => $item['itemCode'] ?? '',
              "max" => $item['max'] ?? 0,
              "quantity" => $item['quantity'] ?? 0,
              "rate" => $item['price'] ?? 0,
              "subTotal" => ($item['price'] * $item['quantity']) ?? 0,
              "attributes" => $item['attributes'] ?? [],
            ]);
          }
          $data = [
            'QuantityRanges' => json_encode($QuantityRanges),
            'Item' => json_encode($item_details),
            'ItemData' => json_encode($ItemData),
            'minQuantity' => $minQuantity,
            'localDelivery' => $localDelivery,
            'shipped_by' => $shipped_by,
            'shippingRate' => $shippingRate,
            'approxWeight' => $approxWeight,
            'created_at' => now(),
            'updated_at' => now(),
          ];
          CustomerCart::updateOrInsert(
            ['ItemId' => $ItemId, 'user_id' => $user_id, 'buy_status' => null],
            $data
          );
        }
        $status = true;
      }
    }
    return $status;
  }

  public function LoadCustomerCart()
  {
    $status = false;
    $newCart = [];
    if (auth()->check()) {
      $user_id = auth()->id();
      $CustomerCart = CustomerCart::where('user_id', $user_id)->whereNull('buy_status')->whereNotNull('Item')->get();

      if (count($CustomerCart)) {
        foreach ($CustomerCart as $cuCart) {
          $item = json_decode($cuCart->Item, true);
          $arrData = [
            'product_id' => $cuCart->ItemId,
            'isCart' => true,
            'shipped_by' => $cuCart->shipped_by,
            'FirstLotQuantity' => $cuCart->minQuantity,
            'BatchLotQuantity' => array_key_exists('BatchLotQuantity', $item) ? $item['BatchLotQuantity'] : 1,
            'title' => key_exists('title', $item) ? $item['title'] : '',
            'mainImage' => key_exists('mainImage', $item) ? $item['mainImage'] : '',
            'QuantityRanges' => json_decode($cuCart->QuantityRanges),
            'configItems' => json_decode($cuCart->ItemData),
            'approxWeight' => $cuCart->approxWeight,
            'localDelivery' => $cuCart->localDelivery,
            'shippingRate' => $cuCart->shippingRate,
          ];
          array_push($newCart, $arrData);
        }
      }

      if (!empty($newCart)) {
        $status = true;
      }
    }
    return response(['status' => $status, 'cart' => $newCart]);
  }

  public function noticeMarkUnread()
  {
    $notice = request('notice');
    $notification = Auth::user()->notifications()->find($notice);

    if ($notification) {
      $notification->markAsRead();
    }

    return response()->json(['status' => true]);
  }

  public function couponCodeValidate()
  {
    $coupon_code = request('coupon_code');
    $cartTotal = request('coupon_cartTotal');
    $today = Carbon::now()->toDateTimeString();
    $coupon = Coupon::whereNotNull('active')
      ->where('coupon_code', $coupon_code)
      ->whereDate('expiry_date', '>=', $today)
      ->first();

    $return['status'] = false;
    if ($coupon) {
      $minimum_spend = $coupon->minimum_spend;
      $maximum_spend = $coupon->maximum_spend;
      $amount = 0;
      if ($minimum_spend && $maximum_spend) {
        $amount = ($cartTotal >= $minimum_spend && $cartTotal <= $maximum_spend) ? $coupon->coupon_amount : 0;
      } else if ($minimum_spend) {
        $amount = $cartTotal >= $minimum_spend ? $coupon->coupon_amount : 0;
      } else if ($maximum_spend) {
        $amount = $cartTotal <= $maximum_spend ? $coupon->coupon_amount : 0;
      } else {
        $amount = $coupon->coupon_amount;
      }

      $isEnable = false;
      if ($amount) {
        $isEnable = true;
        $limit_per_coupon = $coupon->limit_per_coupon;
        $limit_per_user = $coupon->limit_per_user;
        if ($limit_per_coupon) {
          $countCoupon = CouponUser::where('coupon_id', $coupon->id)->count();
          $isEnable = $countCoupon <= $limit_per_coupon ? true : false;
        }
        if ($limit_per_user) {
          $user_id = auth()->id ?? 0;
          $countUser = CouponUser::where('coupon_id', $coupon->id)->where('user_id', $user_id)->count();
          $isEnable = $countUser <= $limit_per_user ? true : false;
        }
      }
      if ($coupon->coupon_type == 'flat_cart_discount' && $isEnable) {
        $return['status'] = true;
        $return['amount'] = $coupon->coupon_amount;
      } else if ($coupon->coupon_type == 'perchantage_discount' && $isEnable) {
        $return['status'] = true;
        $return['amount'] = ($cartTotal * $coupon->coupon_amount) / 100;
      } else if ($coupon->coupon_type == 'free_shipping' && $isEnable) {
        $return['status'] = true;
        $return['amount'] = 'free_shipping';
      }
    }

    return response($return);
  }


  public function reloadProductDeliveryCost()
  {
    $product_id = request('product_id');
    $item = $this->getFullInfo($product_id);
    if (empty($item)) {
      return response(['status' => false]);
    }

    $DeliveryCosts = [];

    if (is_array($item)) {
      $DeliveryCosts = key_exists('DeliveryCosts', $item) ? $item['DeliveryCosts'] : [];
    }

    $passData = [
      "status" => count($DeliveryCosts) ? true : false,
      "DeliveryCosts" => $DeliveryCosts,
    ];

    return response($passData);
  }


  public function incompleteOrderDelete($id)
  {
    $user_id = auth()->id();
    $order = Order::where('id', $id)->where('user_id', $user_id)->first();
    if ($order) {
      $orderItems = OrderItem::where('order_id', $id)->pluck('id')->toArray();
      OrderItem::where('order_id', $id)->delete();
      OrderItemVariation::whereIn('order_item_id', $orderItems)->delete();
      $order->delete();

      return \response([
        'status' => true,
        'icon' => 'warning',
        'msg' => 'Your order deleted successfully!',
      ]);
    }

    return \response([
      'status' => false,
      'icon' => 'error',
      'msg' => 'Delete failed',
    ]);
  }
}
