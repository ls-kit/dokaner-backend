<?php

namespace App\Http\Controllers\Frontend\Content;

use App\Http\Controllers\Controller;
use App\Models\Auth\User;
use App\Models\Content\Frontend\Address;
use Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AddressController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    dd('content address');
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
   * @param \Illuminate\Http\Request $request
   * @return JsonResponse
   */
  public function store(Request $request)
  {
    $data = $this->addressValidator();
    $address_id = \request('address_id');
    $auth_id = auth()->id();
    $data['user_id'] = $auth_id;
    if ($address_id) {
      $address = Address::where('user_id', $auth_id)->where('id', $address_id)->first();
      $address->update($data);
    } else {
      $address = Address::create($data);
    }

    $phone = auth()->user()->phone ?? null;

    if (!$phone && $auth_id) {
      $user = User::find($auth_id);
      $user->phone = $data['phone_one'];
      $user->save();
    }

    $response['status'] = false;
    if ($address) {
      $response['status'] = true;
    }

    // phone_one
    return response()->json($response);
  }

  /**
   * Display the specified resource.
   *
   * @param Address $address
   * @return JsonResponse
   */
  public function show()
  {
    $auth_id = auth()->id();
    $user = User::find($auth_id);
    $data['status'] = false;
    if (count($user->address)) {
      $data['status'] = true;
      $data['address'] = $user->address;
      $data['shipping_id'] = $user->shipping_id;
      $data['billing_id'] = $user->billing_id;
    }

    return response($data);
  }


  public function storeDefault()
  {
    $shipping_id = \request('shipping_id');
    $user_id = auth()->id();
    $user = User::find($user_id);
    $user->shipping_id = $shipping_id;
    $user->save();
    return response()->json(['status' => true]);
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param Address $address
   * @return \Illuminate\Http\Response
   */
  public function edit(Address $address)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param \Illuminate\Http\Request $request
   * @param Address $address
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, Address $address)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param Address $address
   * @return JsonResponse
   */
  public function destroy()
  {
    $id = \request('address_id');
    Address::destroy($id);

    $response['status'] = false;
    if ($id) {
      $response['status'] = true;
    }
    return response()->json($response);
  }

  public function addressValidator(int $update_id = null)
  {
    return request()->validate([
      'name' => 'required|string|max:255',
      'phone_one' => 'required|string|max:55',
      'phone_two' => 'nullable|string|max:55',
      'address' => 'required|string|max:800',
    ]);
  }
}
