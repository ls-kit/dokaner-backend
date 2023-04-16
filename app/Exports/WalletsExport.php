<?php

namespace App\Exports;

use App\Models\Content\OrderItem;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class WalletsExport implements FromQuery, WithHeadings
{
  use Exportable;

  public function __construct($order = '')
  {
    //    $this->order = $order;
  }

  public function query()
  {
    return OrderItem::query()->select(
      'id',
      'created_at',
      'order_item_number',
      'order_id',
      'product_id',
      'name',
      'shipped_by',
      'shipping_rate',
      'approxWeight',
      'chinaLocalDelivery',
      'order_number',
      'tracking_number',
      'actual_weight',
      'quantity',
      'product_value',
      'first_payment',
      'coupon_contribution',
      'shipping_charge',
      'courier_bill',
      'out_of_stock',
      'out_of_stock_type',
      'missing',
      'adjustment',
      'refunded',
      'last_payment',
      'due_payment',
      'status',
      'user_id'
    )->orderByDesc('id');
  }


  public function headings(): array
  {
    return [
      'id',
      'Date',
      'itemNumber',
      'order_id',
      'product_id',
      'name',
      'shipped_by',
      'shipping_rate',
      'approxWeight',
      'chinaLocalDelivery',
      'order_number',
      'tracking_number',
      'actual_weight',
      'quantity',
      'product_value',
      'first_payment',
      'coupon_contribution',
      'shipping_charge',
      'courier_bill',
      'out_of_stock',
      'out_of_stock_type',
      'missing',
      'adjustment',
      'refunded',
      'last_payment',
      'due_payment',
      'status',
      'user_id'
    ];
  }


  //  public function columnFormats(): array
  //  {
  //    return [
  //      'N' => NumberFormat::FORMAT_DATE_DDMMYYYY,
  //      'O' => NumberFormat::FORMAT_DATE_DDMMYYYY,
  //    ];
  //  }


}
