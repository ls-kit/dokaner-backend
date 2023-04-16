<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Content\Frontend\CustomerCart;
use App\Models\Content\Frontend\Wishlist;
use App\Models\Content\Order;
use App\Models\Content\OrderItem;
use App\Models\Content\Post;
use App\Models\Content\Product;
use App\Models\Content\Taxonomy;
use Auth;
use Illuminate\Support\Facades\Crypt;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Validator;

/**
 * Class HomeController.
 */
class HomeController extends Controller
{
  /**
   * @return \Illuminate\View\View
   */
  public function index()
  {


    return view('frontend.auth.login');



    $cookie_announced = Cookie::get('_announce');
    if (!$cookie_announced) {
      Cookie::queue('_announce', 'read_announce', 10);
      $announcement = Post::where('post_type', 'announcement')
        ->where('post_status', 'publish')
        ->latest()
        ->first();
    } else {
      $announcement = null;
    }
    // $this->apiTestingDeveloping();
    $categories = Taxonomy::whereNull('ParentId')->whereNotNull('active')->orderBy('created_at')->get();
    $banners = Post::where('post_type', 'banner')->where('post_status', 'publish')->limit(5)->latest()->get();
    $recentProducts = Product::latest()->paginate(30);
    $recentOrders = OrderItem::with('product')->select('product_id')->groupBy('product_id')->latest()->paginate(30);
    $wishlistProducts = Wishlist::withTrashed()->with('product')->select('ItemId')->groupBy('ItemId')->latest()->paginate(30);
    $someoneBuying = CustomerCart::withTrashed()->with('product')->select('ItemId')->groupBy('ItemId')->latest()->paginate(30);

    return view('frontend.index', compact('announcement', 'categories', 'banners', 'recentProducts', 'recentOrders', 'wishlistProducts', 'someoneBuying'));
  }

  public function apiTestingDeveloping()
  {
    $client = new Client([
      'base_uri' => 'http://otapi.net/service-json/',
      'timeout' => 4.0,
    ]);

    $response = $client->request('GET', 'GetItemFullInfo', ['query' => [
      'instanceKey' => config('app.instance_key'),
      'language' => 'en',
      'itemParameters' => '<Parameters AllowIncomplete="false" AllowDeleted="false" WaitingTime="1" />',
      'itemId' => 'abb-610882143290',
    ]]);

    $content = $response->getBody();
    $content = json_decode($content);

    dd($content);
  }

  public function category($slug)
  {
    $page = Post::where('post_type', 'page')
      ->where('post_slug', trim($slug))
      ->where('post_status', 'publish')->first();

    if ($page) {
      return view('frontend.pages.page', compact('page'));
    }
    $category = Taxonomy::with('childrens')->where('slug', trim($slug))->whereNotNull('active')->firstOrFail();
    $childrens = $category ? $category->childrens : collect([]);
    // $items = otc_category_items($category->otc_id);
    // dd($category);
    if (!$childrens->count()) {
      $subcategory = null;
      return view('frontend.categoryProductList', compact('category', 'subcategory'));
    }
    return view('frontend.categoryList', compact('category', 'childrens'));
  }


  public function categoryProductList($cat_slug, $subcat_slug)
  {
    $category = Taxonomy::where('slug', $cat_slug)->whereNotNull('active')->firstOrFail();
    $subcategory = Taxonomy::where('slug', $subcat_slug)->where('ParentId', $category->otc_id)->whereNotNull('active')->firstOrFail();

    // $slug = md5($subcategory->slug);
    // Cache::forget($slug);

    //dd($category);
    return view('frontend.categoryProductList', compact('category', 'subcategory'));
  }


  public function pictureSearch()
  {

    $resData['status'] = false;

    if (request()->ajax()) {
      $validator = Validator::make(request()->all(), [
        'picture' => 'required|max:8000|mimes:jpeg,jpg,png,webp,gif',
      ]);

      $resData['status'] = false;

      if ($validator->fails()) {
        $resData['message'] = 'Image size is greater than 6mb or Image formate not support';
        return response()->json($resData);
      }
      if (request()->hasFile('picture')) {
        $picture = request()->file('picture');
        $saveDirectory = 'search/' . date('Y-m-d');
        $prefix = 'CNB-' . time();
        $oriPicName = $picture->getClientOriginalName();
        $search = store_search_picture($picture, $saveDirectory, $prefix);

        $encryptedPath = Crypt::encryptString($search);

        $resData['status'] = true;
        $href = route('frontend.search', ['s' => $oriPicName, 'type' => 'picture', 'image' => $encryptedPath]);
        $resData['href'] = $href;
      }
    }

    return response()->json($resData);
  }


  public function search()
  {
    $image = request('image');
    if ($image) {
      $image = Crypt::decryptString($image);
    }
    return view('frontend.searchList', compact('image'));
  }


  public function productDetails($item_id)
  {
    $item = Product::where('ItemId', $item_id)->first();
    $Pictures = [];
    $error = false;
    $new_item = [];
    if (!$item) {
      $new_item = otc_items_full_info($item_id);
      if (empty($new_item)) {
        $error = true;
      } else {
        $Pictures = $new_item['Pictures'] ?? [];
        $item = $new_item;
      }
    } else {
      $item = $item->toArray();
      if (!key_exists('ItemId', $item)) {
        $error = true;
      }
      $Pictures = json_decode($item['Pictures'], true) ?? [];
    }


    if ($error) {
      abort(404);
    }

    $wishList = Auth::user()->wishlist ?? collect([]);
    $item_id = key_exists('Id', $item) ? $item['Id'] : $item['ItemId'];
    $CategoryId = key_exists('CategoryId', $item) ? $item['CategoryId'] : '';

    $exit_wishList = $wishList->contains('ItemId', $item_id);

    $page = Post::where('post_slug', 'shipping-and-delivery')
      ->where('post_status', 'publish')
      ->where('post_type', 'page')
      ->first();
    $relatedProducts = Product::where('CategoryId', $CategoryId)
      ->whereNotIn('ItemId', [$item_id])
      ->latest()->offset(0)->limit(15)->get();

    $ApproxWeight = 0;
    $hasWeight = 0; // let db column false
    if (key_exists('PhysicalParameters', $item)) {
      $PhysicalParameters = $item['PhysicalParameters'];
      $Parameters = is_array($PhysicalParameters) ? $PhysicalParameters : json_decode($PhysicalParameters, true);
      if (is_array($Parameters)) {
        $hasWeight = empty($new_item) ? 1 : $hasWeight;  // let db column null
        if (!empty($Parameters)) {
          $hasWeight = 2; // let db column has weight
          $ApproxWeight = key_exists('ApproxWeight', $Parameters) ? $Parameters['ApproxWeight'] : 0;
          $Weight = key_exists('Weight', $Parameters) ? $Parameters['Weight'] : 0;
          $ApproxWeight = $ApproxWeight ? $ApproxWeight : $Weight;
        }
      }
    }

    //    dd('$ApproxWeight ' . $ApproxWeight . '- has wright' . $hasWeight);

    return view('frontend.productDetails', compact('item', 'exit_wishList', 'relatedProducts', 'page', 'Pictures', 'ApproxWeight', 'hasWeight'));
  }

  public function shopNow()
  {
    $recentProducts = Product::latest()->paginate(36);
    return view('frontend.pages.shopNow', compact('recentProducts'));
  }

  public function productsBestSelling()
  {
    $bestSelling = Product::latest()->paginate(36);
    return view('frontend.pages.products-best-selling', compact('bestSelling'));
  }

  public function productsCustomerLoving()
  {
    $customerLoving = Product::latest()->paginate(36);
    return view('frontend.pages.products-customer-loving', compact('customerLoving'));
  }

  public function productsBuyingOnProcess()
  {
    $buyingProcess = Product::latest()->paginate(36);
    return view('frontend.pages.products-buying-on-process', compact('buyingProcess'));
  }


  public function shoppingCart()
  {
    return view('frontend.shoppingCart');
  }

  public function payment()
  {
    return view('frontend.payment');
  }


  public function customerOrderDetails($id)
  {
    $developer = request('developer');
    $user_id = auth()->id();
    if ($developer) {
      $order = Order::with('orderItems')->where('id', $id)->firstOrFail();
    } else {
      $order = Order::with('orderItems')->where('id', $id)->where('user_id', $user_id)->firstOrFail();
    }
    //        dd($order);
    return view('frontend.user.order.customerOrderDetais', compact('order'));
  }


  public function faqs()
  {
    return view('frontend.pages.faqs', [
      'faqs' => Post::wherePostType('faq')->wherePostStatus('publish')->oldest()->get(),
    ]);
  }


  public function aboutUs()
  {
    return view('frontend.pages.about-us', [
      'about' => Post::wherePostType('page')->wherePostSlug('about-us')->firstOrFail(),
    ]);
  }
}
