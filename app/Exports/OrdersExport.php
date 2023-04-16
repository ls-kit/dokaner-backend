<?php

namespace App\Exports;

use App\Models\Content\Order;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OrdersExport implements FromQuery, WithHeadings
{

  use Exportable;

  private $order;

  public function __construct($order = '')
  {
//    $this->order = $order;
  }

  public function query()
  {
    return Order::query()->select(
      'id',
      'order_number',
      'name',
      'email',
      'phone',
      'amount',
      'needToPay',
      'dueForProducts',
      'status',
      'transaction_id',
      'currency',
      'coupon_code',
      'coupon_victory',
      'user_id',
      'created_at',
      'updated_at'
    )->orderByDesc('id');
  }


  public function headings(): array
  {
    return [
      '#Id',
      'orderNumber',
      'name',
      'email',
      'phone',
      'amount',
      'needToPay',
      'dueForProducts',
      'status',
      'transaction_id',
      'currency',
      'coupon_code',
      'couponVictory',
      'userId',
      'OrderDate',
      'LastModify'
    ];
  }

//
//  public function columnFormats(): array
//  {
//    return [
//      'N' => NumberFormat::FORMAT_DATE_DDMMYYYY,
//      'O' => NumberFormat::FORMAT_DATE_DDMMYYYY,
//    ];
//  }




}
