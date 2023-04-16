<?php

namespace App\Http\Livewire;

use App\Models\Content\Invoice;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\TableComponent;
use Rappasoft\LaravelLivewireTables\Traits\HtmlComponents;
use Rappasoft\LaravelLivewireTables\Views\Column;

class InvoiceTable extends TableComponent
{
  use HtmlComponents;
  /**
   * @var string
   */
  public $sortField = 'id';
  public $sortDirection = 'desc';
  public $loadingIndicator = true;
  public $clearSearchButton = true;
  public $sortDefaultIcon = '<i class="text-muted fa fa-sort"></i>';
  public $ascSortIcon = '<i class="fa fa-sort-up"></i>';
  public $descSortIcon = '<i class="fa fa-sort-down"></i>';
  protected $options = [
    'bootstrap.classes.table' => 'table table-striped text-center table-bordered',
    'bootstrap.classes.buttons.export' => 'btn btn-info',
  ];

  public $exportFileName = 'Invoice-table';
  public $exports = ['xls', 'pdf', 'csv'];
  public $perPage = 20;
  public $perPageOptions = [];



  public function query(): Builder
  {
    return Invoice::with('user', 'invoiceItems')->withCount('invoiceItems');
  }

  public function columns(): array
  {
    return [
      Column::make('Date', 'created_at')
        ->searchable()
        ->format(function (Invoice $model) {
          return date('d-M-Y', strtotime($model->created_at));
        }),
      Column::make('InvoiceID', 'invoice_no')
        ->sortable()
        ->searchable(),
      Column::make('Customer', 'customer_name')
        ->searchable(),
      Column::make('CustomerPhone', 'customer_phone')
        ->searchable(),
      Column::make('PaymentMethod', 'payment_method')
        ->searchable()
        ->format(function (Invoice $model) {
          return readable_status($model->payment_method);
        }),
      Column::make('DeliveryMethod', 'delivery_method')
        ->searchable()
        ->format(function (Invoice $model) {
          return readable_status($model->delivery_method);
        }),
      Column::make('TotalPayable', 'total_payable')
        ->sortable()
        ->searchable(),
      Column::make('Status', 'status')
        ->searchable()
        ->format(function (Invoice $model) {
          return readable_status($model->status);
        }),
      Column::make(__('Action'), 'action')
        ->format(function (Invoice $model) {
          return view('backend.content.invoice.includes.actions', ['invoice' => $model]);
        })
        ->excludeFromExport(),
    ];
  }


  public function setTableHeadAttributes($attribute): array
  {
    if ($attribute == 'action') {
      return ['style' => 'min-width:80px;'];
    } elseif ($attribute == 'customer_name') {
      return ['style' => 'min-width:150px;'];
    } elseif ($attribute == 'customer_phone') {
      return ['style' => 'min-width: 150px'];
    }
    return [
      'style' => 'min-width:120px'
    ];
  }

  public function setTableHeadClass($attribute): ?string
  {
    $array = ['transaction_id', 'customer_name', 'customer_phone', 'total_due', 'total_courier', 'total_payable', 'action'];
    if (in_array($attribute, $array)) {
      return $attribute . ' text-center';
    }
    return $attribute;
  }


  public function setTableRowId($model): ?string
  {
    return $model->id;
  }
}
