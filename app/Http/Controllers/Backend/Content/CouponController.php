<?php

namespace App\Http\Controllers\Backend\Content;

use App\Http\Controllers\Controller;
use App\Models\Content\Coupon;
use App\Models\Content\CouponUser;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $coupons = Coupon::latest()->paginate();
    return view('backend.content.coupon.index', compact('coupons'));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    return view('backend.content.coupon.create');
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $data = $this->couponValidator();
    $data['active'] = request('active') ? request('active') : null;
    $data['expiry_date'] = Carbon::parse(request('expiry_date'))->addSeconds(86399)->toDateTimeString();
    $data['user_id'] = auth()->id();
    Coupon::create($data);
    return redirect()->route('admin.coupon.index')->withFlashSuccess('Coupon Created Successfully');
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Models\Content\Coupon  $coupon
   * @return \Illuminate\Http\Response
   */
  public function show(Coupon $coupon)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  \App\Models\Content\Coupon  $coupon
   * @return \Illuminate\Http\Response
   */
  public function edit(Coupon $coupon)
  {
    return view('backend.content.coupon.edit', compact('coupon'));
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Models\Content\Coupon  $coupon
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, Coupon $coupon)
  {
    $data = $this->couponValidator($coupon->id);
    $data['active'] = request('active') ? request('active') : null;
    $data['expiry_date'] = Carbon::parse(request('expiry_date'))->addSeconds(86399)->toDateTimeString();
    $data['user_id'] = auth()->id();
    // dd($data);
    $coupon->update($data);
    return redirect()->route('admin.coupon.index')->withFlashSuccess('Coupon Updated Successfully');
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Models\Content\Coupon  $coupon
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    $coupon = Coupon::withTrashed()->findOrFail($id);
    if ($coupon->trashed()) {
      $coupon->forceDelete();
      return redirect()->route('admin.coupon.index')->withFlashSuccess('Permanent Deleted Successfully');
    } else if ($coupon->delete()) {
      return redirect()->route('admin.coupon.index')->withFlashSuccess('Trashed Successfully');
    }
    return redirect()->route('admin.coupon.index')->withFlashSuccess('Delete failed');
  }

  public function couponValidator(int $id = null)
  {
    return request()->validate([
      'active' => 'nullable|date|date_format:Y-m-d H:i:s',
      'coupon_code' => 'required|string|max:191|' . $id ? 'unique:coupons,coupon_code,' . $id : 'unique:coupons,coupon_code', // unique coupon code
      'coupon_type' => 'required|string|max:191',
      'coupon_amount' => 'nullable|numeric|min:0',
      'minimum_spend' => 'nullable|numeric|min:0',
      'maximum_spend' => 'nullable|numeric|min:0',
      'limit_per_coupon' => 'nullable|numeric|min:0',
      'limit_per_user' => 'nullable|numeric|min:0',
      'expiry_date' => 'nullable|date|date_format:Y-m-d'
    ]);
  }

  public function trashed()
  {
    $coupons = Coupon::onlyTrashed()->latest()->paginate();
    return view('backend.content.coupon.trash', compact('coupons'));
  }

  public function restore($id)
  {
    Coupon::onlyTrashed()->findOrFail($id)->restore();
    return redirect()->route('admin.coupon.index')->withFlashSuccess('Coupon Restore Successfully');
  }


  public function couponLog()
  {
    $logs = CouponUser::with('user', 'order', 'coupon')->paginate();

    return view('backend.content.coupon.coupon-log', compact('logs'));
  }
}
