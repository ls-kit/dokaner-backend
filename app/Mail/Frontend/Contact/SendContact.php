<?php

namespace App\Mail\Frontend\Contact;

use App\Models\Auth\User;
use Illuminate\Bus\Queueable;
use Illuminate\Http\Request;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Class SendContact.
 */
class SendContact extends Mailable
{
  use Queueable, SerializesModels;

  /**
   * @var Request
   */
  public $request;

  /**
   * SendContact constructor.
   *
   * @param Request $request
   */
  public function __construct(Request $request)
  {
    $this->request = $request;
  }

  /**
   * Build the message.
   *
   * @return $this
   */
  public function build()
  {
    $users = User::role('administrator')->whereConfirmed(1)->pluck('email')->toArray();
    array_push($users, config('mail.from.address'));

    return $this->to($users)
      ->view('frontend.mail.contact')
      ->text('frontend.mail.contact-text')
      ->subject(__('strings.emails.contact.subject', ['app_name' => app_name()]))
      ->from($this->request->email, $this->request->name)
      ->replyTo($this->request->email, $this->request->name);
  }
}
