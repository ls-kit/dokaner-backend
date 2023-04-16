<?php

namespace App\Http\Livewire;

use App\Models\Content\Invoice;
use App\Models\Content\OrderItem;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\TableComponent;
use Rappasoft\LaravelLivewireTables\Traits\HtmlComponents;
use Rappasoft\LaravelLivewireTables\Views\Column;

class CustomerInvoiceTable extends TableComponent
{
  use HtmlComponents;
  /**
   * @var string
   */
  public $sortField = 'id';
  public $sortDirection = 'desc';

  public $perPage = 10;
  public $perPageOptions = [];

  public $loadingIndicator = true;

  public $searchEnabled = true;

  protected $options = [
    'bootstrap.classes.table' => 'table table-striped table-bordered',
    'bootstrap.classes.thead' => null,
    'bootstrap.classes.buttons.export' => 'btn',
    'bootstrap.container' => true,
    'bootstrap.responsive' => true,
  ];


  public $sortDefaultIcon = '<i class="text-muted fa fa-sort"></i>';
  public $ascSortIcon = '<i class="fa fa-sort-up"></i>';
  public $descSortIcon = '<i class="fa fa-sort-down"></i>';

  public function query(): Builder
  {
    $user_id = auth()->id();
    return Invoice::with('user', 'invoiceItems')->where('user_id', $user_id);
  }

  public function columns(): array
  {
    return [
      Column::make(__('Date'), 'created_at')
        ->format(function (Invoice $model) {
          return date('d-M-Y', strtotime($model->created_at));
        }),
      Column::make(__('InvoiceID'), 'invoice_no'),
      Column::make(__('ProductDue'), 'total_due'),
      Column::make(__('CourierBill'), 'total_courier'),
      Column::make(__('TotalPayable'), 'total_payable'),
      Column::make(__('PaymentMethod'), 'payment_method'),
      Column::make(__('Status'), 'status')
        ->searchable()
        ->format(function (Invoice $model) {
          return readable_status($model->status);
        }),
      Column::make(__('Action'), 'action')
        ->format(function (Invoice $model) {
          $tan_id = $model->order->transaction_id ?? '';
          $status = $model->status;
          $payNow = '';
          $details = '<a href="' . route('frontend.user.invoice-details', $model) . '" class="btn btn-success">Details</a>';
          if ($status == 'Pending') {
            $payNow = '<a href="' . route('frontend.user.invoice.payNow', $model) . '" class="btn btn-danger">Pay Now</a>';
          }

          $html_data = '<div class="btn-group  btn-group-sm" role="group" aria-label="Basic Group">' . $details . $payNow . '</div>';
          return $this->html($html_data);
        }),
    ];
  }


  public function setTableHeadClass($attribute): ?string
  {
    $array = ['created_at', 'total_due', 'total_courier', 'total_payable', 'payment_method', 'status', 'action'];
    if (in_array($attribute, $array)) {
      return ' text-center';
    }
    return $attribute;
  }


  public function setTableDataClass($attribute, $value): ?string
  {
    $array = ['created_at', 'total_due', 'total_courier', 'total_payable', 'payment_method', 'status', 'action'];
    if (in_array($attribute, $array)) {
      return ' text-center';
    }
    return $attribute;
  }

  public function setTableRowId($model): ?string
  {
    return $model->id;
  }
}
