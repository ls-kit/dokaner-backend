<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderAuthInformation extends Notification
{
  use Queueable;

  public $data;

  /**
   * Create a new notification instance.
   *
   * @return void
   */
  public function __construct($data)
  {
    $this->data = $data;
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
   * @return MailMessage
   */
  public function toMail($notifiable)
  {
    $order = $this->data;
    $transaction_id = $order->transaction_id ?? null;
    $order_id = $order->id ?? null;
    $amount = $order->amount ?? null;
    $needToPay = $order->needToPay ?? null;
    $dueForProducts = $order->dueForProducts ?? null;
    $user = $order->user;
    $full_name = $user ? $user->first_name . ' ' . $user->last_name : 'Customer';

    return (new MailMessage)->markdown('notification/OrderAuthInfo', [
      'full_name' => $full_name,
      'transaction_id' => $transaction_id,
      'amount' => $amount,
      'needToPay' => $needToPay,
      'dueForProducts' => $dueForProducts,
      'order_id' => $order_id
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
    $transaction_id = $this->data->transaction_id ?? null;
    $order_id = $this->data->id ?? null;
    $amount = $this->data->amount ?? null;
    return [
      'invoice_id' => $order_id,
      'transaction_id' => $transaction_id,
      'amount' => $amount,
    ];
  }
}
