<?php

namespace App\Http\Livewire;

use App\Models\Content\OrderItem;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\TableComponent;
use Rappasoft\LaravelLivewireTables\Traits\HtmlComponents;
use Rappasoft\LaravelLivewireTables\Views\Column;

class OrderItemTable extends TableComponent
{
  use HtmlComponents;
  /**
   * @var string
   */
  public $sortField = 'id';
  public $sortDirection = 'desc';

  public $perPage = 20;
  public $perPageOptions = [10, 20, 30, 50, 100, 500];

  public $loadingIndicator = true;
  public $clearSearchButton = true;

  protected $options = ['bootstrap.classes.table' => 'table table-bordered table-hover'];

  public $sortDefaultIcon = '<i class="text-muted fa fa-sort"></i>';
  public $ascSortIcon = '<i class="fa fa-sort-up"></i>';
  public $descSortIcon = '<i class="fa fa-sort-down"></i>';

  public $exportFileName = 'Customer-Wallet';
  public $exports = ['xlsx', 'xls', 'pdf', 'csv'];


  public $status;
  public $customer;

  public function mount($status, $customer)
  {
    $this->status = $status;
    $this->customer = $customer;
  }


  public function query(): Builder
  {
    $orderItem = OrderItem::with('user', 'order', 'product');
    $orderItem = $this->status ? $orderItem->where('status', $this->status) : $orderItem;
    return $this->customer ? $orderItem->where('user_id', $this->customer) : $orderItem;
  }

  public function columns(): array
  {
    return [
      Column::make('All', 'id')
        ->format(function (OrderItem $model) {
          $checkbox = '<input type="checkbox" class="checkboxItem " data-status="' . $model->status . '" data-user="' . $model->user_id . '" name="wallet[]" value="' . $model->id . '">';
          return $this->html($checkbox);
        })->excludeFromExport(),
      Column::make('Date', 'created_at')
        ->searchable()
        ->sortable()
        ->format(function (OrderItem $model) {
          return date('d-M-Y', strtotime($model->created_at));
        }),
      Column::make('Transaction No.', 'order.transaction_id')
        ->searchable()
        ->sortable()
        ->format(function (OrderItem $model) {
          return $model->order->transaction_id ?? 'N/A';
        }),
      Column::make('Item No.', 'order_item_number')
        ->searchable()
        ->sortable()
        ->format(function (OrderItem $model) {
          return $this->html('<span class="order_item_number">' . $model->order_item_number . '</span>');
        }),
      Column::make('Customer', 'user.name')
        ->searchable()
        ->sortable()
        ->format(function (OrderItem $model) {
          return $model->user->name ? $model->user->full_name : 'N/A';
        }),
      Column::make('Tracking No.', 'tracking_number')
        ->searchable()
        ->sortable()
        ->format(function (OrderItem $model) {
          return $this->html('<span class="tracking_number">' . $model->tracking_number . '</span>');
        }),
      Column::make('1688 Order No.', 'order_number')
        ->searchable()
        ->sortable()
        ->format(function (OrderItem $model) {
          return $this->html('<span class="order_number">' . $model->order_number . '</span>');
        }),
      Column::make('Products Title', 'name')
        ->searchable()
        ->sortable()
        ->format(function (OrderItem $model) {
          return $this->html('<span class="product_name" data-product-id="' . $model->product_id . '">' . strip_tags($model->name) . '</span>');
        }),
      Column::make('Shipped By', 'shipped_by')
        ->format(function (OrderItem $model) {
          return $this->html('<span>' . $model->shipped_by . '</span> <br> <span class="text-danger">' . $model->shipping_rate . '</span>');
        }),
      Column::make('1688 Link', '1688_link')
        ->format(function (OrderItem $model) {
          $url = isset($model->product) ? explode('-', $model->product->ItemId) : [];
          return $this->html($this->link("https://detail.1688.com/offer/" . end($url) . ".html", 'Click', ['target' => '_blank']));
        })
        ->excludeFromExport(),
      Column::make('Quantity', 'quantity')
        ->format(function (OrderItem $model) {
          return $this->html('<span class="quantity">' . $model->quantity . '</span>');
        }),
      Column::make('Products Value', 'product_value')
        ->format(function (OrderItem $model) {
          return $this->html('<span class="product_value">' . $model->product_value . '</span>');
        }),
      Column::make('Local Delivery', 'chinaLocalDelivery'),
      Column::make('Coupon Value', 'coupon_contribution')
        ->format(function (OrderItem $model) {
          $coupon = $model->coupon_contribution ? $model->coupon_contribution : 0;
          return $this->html('<span class="coupon_contribution">' . $coupon . '</span>');
        }),
      Column::make('1st Payment', 'first_payment')
        ->format(function (OrderItem $model) {
          return $this->html('<span class="first_payment">' . $model->first_payment . '</span>');
        }),
      Column::make('Out of stock', 'out_of_stock')
        ->format(function (OrderItem $model) {
          return $this->html('<span class="out_of_stock">' . $model->out_of_stock . '</span>');
        }),
      Column::make('Missing/Shortage', 'missing')
        ->format(function (OrderItem $model) {
          return $this->html('<span class="missing">' . $model->missing . '</span>');
        }),
      Column::make('Refunded', 'refunded')
        ->format(function (OrderItem $model) {
          return $this->html('<span class="refunded">' . $model->refunded . '</span>');
        }),
      Column::make('Adjustment', 'adjustment')
        ->format(function (OrderItem $model) {
          return $this->html('<span class="adjustment">' . $model->adjustment . '</span>');
        }),
      Column::make('Weight', 'actual_weight')
        ->format(function (OrderItem $model) {
          return $this->html('<span class="actual_weight">' . $model->actual_weight . '</span>');
        }),
      Column::make('Weight charges', 'shipping_charge')
        ->format(function (OrderItem $model) {
          return $this->html('<span class="shipping_charge">' . $model->shipping_charge . '</span>');
        }),
      Column::make('Courier Bill', 'courier_bill')
        ->format(function (OrderItem $model) {
          return $this->html('<span class="courier_bill">' . $model->courier_bill . '</span>');
        }),
      Column::make('Last Payment', 'last_payment')
        ->format(function (OrderItem $model) {
          return $this->html('<span class="last_payment">' . $model->last_payment . '</span>');
        }),
      Column::make('Due', 'due_payment')
        ->format(function (OrderItem $model) {
          return $this->html('<span class="due_payment">' . $model->due_payment . '</span>');
        }),
      Column::make('Status', 'status')
        ->searchable()
        ->format(function (OrderItem $model) {
          return $this->html('<span class="status">' . $model->status . '</span>');
        }),
      Column::make(__('Action'), 'action')
        ->format(function (OrderItem $model) {
          $htmlHref = '<a href="' . route('admin.order.wallet.details', $model->id) . '" class="btn btn-success btn-sm" target="_blank">Details</a>';
          return $this->html($htmlHref);
        })->excludeFromExport(),
    ];
  }


  public function setTableHeadAttributes($attribute): array
  {
    if ($attribute == 'action') {
      return ['style' => 'min-width:80px;'];
    } elseif ($attribute == 'name') {
      return ['style' => 'min-width:260px;'];
    } elseif ($attribute == 'order_item_number') {
      return ['style' => 'min-width: 100px'];
    } elseif ($attribute == 'transaction_id') {
      return ['style' => 'min-width: 130px'];
    } elseif ($attribute == 'order_number') {
      return ['style' => 'min-width: 150px'];
    }
    return [
      'style' => 'min-width:120px'
    ];
  }

  public function setTableHeadClass($attribute): ?string
  {
    $array = ['id', 'image', 'order_item_number', 'shipped_by', 'chinaLocalDelivery', '1688_link', 'action'];
    if (in_array($attribute, $array)) {
      $allSelect = $attribute == 'id' ? 'allSelectTitle' : '';
      return ' text-center ' . $allSelect;
    }


    return $attribute;
  }


  public function setTableDataClass($attribute, $value): ?string
  {
    $array = ['name'];
    if (in_array($attribute, $array)) {
      return 'align-middle';
    }
    return 'text-center align-middle';
  }

  public function setTableRowId($model): ?string
  {
    return $model->id;
  }
}
