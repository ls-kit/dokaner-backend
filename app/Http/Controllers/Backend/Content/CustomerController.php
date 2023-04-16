<?php

namespace App\Http\Controllers\Backend\Content;

use App\Http\Controllers\Controller;
use App\Models\Auth\User;
use App\Models\Content\Order;
use App\Models\Content\Payment;
use App\Notifications\OrderAuthInformation;
use App\Notifications\OrderPending;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Throwable;

class CustomerController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function index()
  {
    return view('backend.content.customer.index');
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return Response
   */
  public function create()
  {
    //
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param Request $request
   * @return \Illuminate\Http\RedirectResponse
   * @throws Throwable
   */
  public function store(Request $request)
  {
    dd('store');
  }

  /**
   * Display the specified resource.
   *
   * @param int $id
   * @return void
   */
  public function show($id)
  {
    dd('show');
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param int $id
   * @return Response
   */
  public function edit($id)
  {
    dd('edit');
  }

  /**
   * Update the specified resource in storage.
   *
   * @param Request $request
   * @param int $id
   * @return Response
   */
  public function update(Request $request, $id)
  {
    dd('update');
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param int $page
   * @return Response
   */
  public function destroy($id)
  {
    $user = User::withTrashed()->findOrFail($id);
    if ($user->trashed()) {
      DB::table('password_histories')->where('user_id', $id)->delete();
      DB::table('social_accounts')->where('user_id', $id)->delete();
      $user->forceDelete();
      return redirect()->route('admin.customer.index')->withFlashSuccess('Customer Deleted Successfully');
    } else if ($user->delete()) {
      return redirect()->route('admin.customer.index')->withFlashSuccess('Customer Trashed Successfully');
    }
    return redirect()->route('admin.customer.index')->withFlashSuccess('Customer Delete failed');
  }

  public function trashed()
  {
    $customers = User::withCount('orders')->onlyTrashed()->latest()->paginate(10);
    return view('backend.content.customer.trash', compact('customers'));
  }

  public function restore($id)
  {
    User::onlyTrashed()->findOrFail($id)->restore();
    return redirect()->route('admin.customer.index')->withFlashSuccess('Customer Restored Successfully');
  }






  public function paymentValidator($update_id = null)
  {

    return request()->validate([
      'type' => 'required|string|max:155|exists:package_types,slug',
      'plan' => 'required|string|max:155|exists:packages,slug',
      'package' => 'required|numeric|max:9999|exists:packages,id',
      'domain' => $update_id ? 'required|string|max:191|unique:orders,domain,' . $update_id : 'required|string|max:191|unique:orders,domain',
      'payment_method' => 'required|string|max:191',
      'agent_account' => 'required|string|max:191', // payment received agent number
      'subs_year' => 'required|numeric|max:5',
      'subs_total' => 'required|numeric|max:9999',
      'client_account' => 'required|string|max:191',
      'transaction_no' => 'required|string|max:191',
    ]);
  }
}
