<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Models\Auth\User;

/**
 * Class AccountController.
 */
class AccountController extends Controller
{
  /**
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function index()
  {
    return view('frontend.user.account');
  }

  public function updateInformation()
  {
    $redirect = request('redirect');
    return view('frontend.user.updateInformation', compact('redirect'));
  }

  public function updateInformationStore()
  {
    $auth_id = auth()->id();
    $data = request()->validate([
      'name' => 'required|string|max:255',
      'email' => 'nullable|string|max:100|' . $auth_id ? 'unique:users,email,' . $auth_id : 'unique:users,email', // unique page slug
      'phone' => 'nullable|string|max:100|' . $auth_id ? 'unique:users,phone,' . $auth_id : 'unique:users,phone', // unique page slug
      'redirect' => 'required|string|max:800',
    ]);

    $name = request('name');
    $expName = explode(' ', $name);
    $last_name = end($expName);

    $key = array_search($last_name, $expName);
    unset($expName[$key]);
    $first_name = implode(' ', $expName);

    $user = User::find($auth_id);
    $user->name = $name;
    $user->first_name = $first_name;
    $user->last_name = $last_name;
    if (request('email')) {
      $user->email = request('email');
    }
    if (request('phone')) {
      $user->phone = request('phone');
    }
    $user->save();

    $redirect = request('redirect');

    return redirect($redirect)->withFlashSuccess('Update Your Information Successfully!');
  }
}
