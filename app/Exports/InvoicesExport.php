<?php

namespace App\Exports;

use App\Models\Content\Invoice;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class InvoicesExport implements FromQuery, WithHeadings
{

  use Exportable;

  public function __construct($order = '')
  {
//    $this->order = $order;
  }

  public function query()
  {
    return Invoice::query()->select(
      'id',
      'invoice_no',
      'transaction_id',
      'customer_name',
      'customer_phone',
      'customer_address',
      'total_payable',
      'total_courier',
      'payment_method',
      'delivery_method',
      'total_due',
      'status',
      'user_id',
      'created_at',
      'updated_at'
    )->orderByDesc('id');
  }


  public function headings(): array
  {
    return [
      'id',
      'invoice_no',
      'transaction_id',
      'customer_name',
      'customer_phone',
      'customer_address',
      'total_payable',
      'total_courier',
      'payment_method',
      'delivery_method',
      'total_due',
      'status',
      'user_id',
      'created_at',
      'updated_at'
    ];
  }
}
