<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\Contact\SendContactRequest;
use App\Mail\Frontend\Contact\SendContact;
use App\Models\Auth\User;
use App\Models\Content\Contact;
use App\Models\Content\Post;
use App\Notifications\Backend\ContactInformation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Notification;

/**
 * Class ContactController.
 */
class ContactController extends Controller
{
  /**
   * @return \Illuminate\View\View
   */
  public function index()
  {
    $contact = Post::where('post_type', 'page')
      ->where('post_slug', 'contact-us')
      ->where('post_status', 'publish')->first();

    return view('frontend.pages.contact', compact('contact'));
  }

  /**
   * @param SendContactRequest $request
   *
   * @return mixed
   * @throws \Throwable
   */
  public function send(SendContactRequest $request)
  {
    $users = User::role(['administrator', 'editor'])->get();
    DB::transaction(function () use ($users) {
      $contact = Contact::create([
        'name' => request('name'),
        'email' => request('email'),
        'phone' => request('phone'),
        'message' => request('message'),
        'status' => 'contact',
      ]);

      Notification::send($users, new ContactInformation($contact));
    });

    return redirect()->back()->withFlashSuccess(__('alerts.frontend.contact.sent'));
  }
}
