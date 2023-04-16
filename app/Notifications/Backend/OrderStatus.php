<?php

namespace App\Notifications\Backend;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderStatus extends Notification implements ShouldQueue
{
  use Queueable;

  public $data;
  public $subject;

  /**
   * Create a new notification instance.
   *
   * @return void
   */
  public function __construct($data, $subject)
  {
    $this->data = $data;
    $this->subject = $subject;
  }

  /**
   * Get the notification's delivery channels.
   *
   * @param mixed $notifiable
   * @return array
   */
  public function via($notifiable)
  {
    return ['mail', 'database'];
  }

  /**
   * Get the mail representation of the notification.
   *
   * @param mixed $notifiable
   * @return \Illuminate\Notifications\Messages\MailMessage
   */
  public function toMail($notifiable)
  {
    $userData = $this->data;
    $subject = $this->subject;

    return (new MailMessage())
      ->subject($subject)
      ->replyTo('support@chinabazarb2b.com')
      ->markdown('notification/orderStatus', [
        'data' => $userData,
        'notifiable' => $notifiable
      ]);
  }

  /**
   * Get the array representation of the notification.
   *
   * @param mixed $notifiable
   * @return array
   */
  public function toArray($notifiable)
  {
    $userData = $this->data;
    return [
      'message' => $userData,
    ];
  }
}
