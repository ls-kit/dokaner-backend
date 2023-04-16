<?php

namespace App\Notifications\Backend;

use App\Models\Content\Contact;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContactInformation extends Notification
{
  use Queueable;

  public $contact;

  /**
   * Create a new notification instance.
   *
   * @return void
   */
  public function __construct($contact)
  {
    $this->contact = $contact;
  }

  /**
   * Get the notification's delivery channels.
   *
   * @param  mixed  $notifiable
   * @return array
   */
  public function via($notifiable)
  {
    return ['mail'];
  }

  /**
   * Get the mail representation of the notification.
   *
   * @param  mixed  $notifiable
   * @return \Illuminate\Notifications\Messages\MailMessage
   */
  public function toMail($notifiable)
  {
    $contact = $this->contact;

    return (new MailMessage)
      ->subject('Direct Contact')
      ->replyTo($contact->email)
      ->greeting('Hello, ' . $notifiable->full_name)
      ->line($contact->message)
      ->line('Customer Name: ' . $contact->name)
      ->line('Customer Phone: ' . $contact->phone)
      ->line('Customer Email: ' . $contact->email)
      ->line('Thank you for using our application!');
  }

  /**
   * Get the array representation of the notification.
   *
   * @param  mixed  $notifiable
   * @return array
   */
  public function toArray($notifiable)
  {
    return [
      //
    ];
  }
}
