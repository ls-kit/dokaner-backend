<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InvoicePaymentInformation extends Notification
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
   * @return \Illuminate\Notifications\Messages\MailMessage
   */
  public function toMail($notifiable)
  {
    $order = $this->data;
    $invoice_id = $order->id ?? null;
    $invoice_no = $order->invoice_no ?? null;
    $amount = $order->total_payable ?? null;
    $userName = $order->user->full_name ?? 'Not Found';
    return (new MailMessage)->markdown('notification/invoicePaymentInfo', [
      'full_name' => $userName,
      'invoice_id' => $invoice_id,
      'transaction_id' => $invoice_no,
      'amount' => $amount
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
    $invoice_no = $this->data->invoice_no ?? null;
    $invoice_id = $this->data->id ?? null;
    $amount = $this->data->amount ?? null;
    return [
      'invoice_id' => $invoice_id,
      'invoice_no' => $invoice_no,
      'amount' => $amount,
      'type' => 'invoice_payment',
    ];
  }
}
