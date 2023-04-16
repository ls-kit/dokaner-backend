<?php

namespace App\Http\Controllers\Frontend\Content;

use App\Http\Controllers\Controller;
use App\Models\Content\Frontend\Wishlist;
use App\Models\Content\Product;
use Auth;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $wishlist = Auth::user()->wishlist;
    return view('frontend.wishlist', compact('wishlist'));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    //
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $item_id = request('item_id');
    $product = Product::where('ItemId', $item_id)->first();
    $auth_id = auth()->id();
    Wishlist::updateOrCreate(['ItemId' => $item_id, 'user_id' => $auth_id], ['product_id' => $product->id]);

    $wishlist = Auth::user()->wishlist->count() ?? 0;

    return response()->json(['total_w_list' => $wishlist]);
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Models\Content\Frontend\Wishlist  $wishlist
   * @return \Illuminate\Http\Response
   */
  public function show()
  {
    $wishlist = Auth::user()->wishlist->count() ?? 0;
    return response()->json(['total_w_list' =>  $wishlist]);
  }



  public function remove($id)
  {
    $wishlist =  Wishlist::where('ItemId', $id)->first();
    if ($wishlist) {
      $wishlist->delete();
    }
    return redirect()->back()->withFlashInfo('Remove from Wishlist successfully');
  }




  /**
   * Show the form for editing the specified resource.
   *
   * @param  \App\Models\Content\Frontend\Wishlist  $wishlist
   * @return \Illuminate\Http\Response
   */
  public function edit(Wishlist $wishlist)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Models\Content\Frontend\Wishlist  $wishlist
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id)
  {
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Models\Content\Frontend\Wishlist  $wishlist
   * @return \Illuminate\Http\Response
   */
  public function destroy(Wishlist $wishlist)
  {
    //
  }
}
