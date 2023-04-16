<?php

namespace App\Http\Livewire;

use App\Models\Content\OrderItem;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\TableComponent;
use Rappasoft\LaravelLivewireTables\Traits\HtmlComponents;
use Rappasoft\LaravelLivewireTables\Views\Column;

class WalletTable extends TableComponent
{
    use HtmlComponents;
    /**
     * @var string
     */
    public $sortField = 'id';
    public $sortDirection = 'desc';

    public $perPage = 20;
    public $perPageOptions = [10, 20, 30, 50, 100, 200, 500, 1000];

    public $loadingIndicator = true;
    public $clearSearchButton = true;


    protected $options = [
        'bootstrap.classes.table' => 'table table-bordered table-hover',
        'bootstrap.classes.thead' => null,
        'bootstrap.classes.buttons.export' => 'btn btn-info',
        'bootstrap.container' => true,
        'bootstrap.responsive' => true,
    ];

    public $sortDefaultIcon = '<i class="text-muted fa fa-sort"></i>';
    public $ascSortIcon = '<i class="fa fa-sort-up"></i>';
    public $descSortIcon = '<i class="fa fa-sort-down"></i>';

    public $exportFileName = 'Customer-Wallet';
    public $exports = [];


    public $status;
    public $customer;

    public function mount($status, $customer)
    {
        $this->status = $status;
        $this->customer = $customer;
    }

    public function query(): Builder
    {
        $customer = $this->customer;
        $status = $this->status;
        $status = $status ? explode(',', $this->status) : [];
        $status = array_filter($status, function ($v) {
            return $v != 'null';
        });

        $orderItem = OrderItem::with('user', 'order', 'product');
        $orderItem = !empty($status) ? $orderItem->whereIn('status', $status) : $orderItem;
        return $customer ? $orderItem->where('user_id', $customer) : $orderItem;
    }

    public function columns(): array
    {
        return [
            Column::make('<input type="checkbox" id="allSelectCheckbox">', 'checkbox')
                ->format(function (OrderItem $model) {
                    $checkbox = '<input type="checkbox" class="checkboxItem " data-status="' . $model->status . '" data-user="' . $model->user_id . '" name="wallet[]" value="' . $model->id . '">';
                    return $this->html($checkbox);
                })->excludeFromExport(),
            Column::make('Date', 'created_at')
                ->searchable()
                ->format(function (OrderItem $model) {
                    return date('d-M-Y', strtotime($model->created_at));
                }),
            Column::make('TranID.', 'order.transaction_id')
                ->searchable()
                ->format(function (OrderItem $model) {
                    return $model->order->transaction_id ?? 'N/A';
                }),
            Column::make('ItemNo.', 'order_item_number')
                ->searchable()
                ->sortable()
                ->format(function (OrderItem $model) {
                    return $this->html('<span class="order_item_number">' . $model->order_item_number . '</span>');
                }),
            Column::make('Customer', 'user.name')
                ->searchable()
                ->format(function (OrderItem $model) {
                    return $model->user->name ? $model->user->full_name : 'N/A';
                }),
            Column::make('Status', 'status')
                ->searchable()
                ->format(function (OrderItem $model) {
                    return $this->html('<span class="status" data-status="' . $model->status . '">' . $model->status . '</span>');
                }),
            Column::make('OrderNo.', 'order_number')
                ->searchable()
                ->format(function (OrderItem $model) {
                    return $this->html('<span class="order_number">' . $model->order_number . '</span>');
                }),
            Column::make('TrackingNo.', 'tracking_number')
                ->searchable()
                ->format(function (OrderItem $model) {
                    return $this->html('<span class="tracking_number">' . $model->tracking_number . '</span>');
                }),
            Column::make('ProductsTitle', 'name')
                ->searchable()
                ->format(function (OrderItem $model) {
                    return $this->html('<span class="product_name" data-product-id="' . $model->product_id . '">' . strip_tags($model->name) . '</span>');
                }),
            Column::make('ShippingRate', 'shipped_by')
                ->format(function (OrderItem $model) {
                    return $this->html('<span class="text-danger">' . $model->shipping_rate . '</span>');
                }),
            Column::make('1688cartLink', '1688_link')
                ->format(function (OrderItem $model) {
                    $product_id = isset($model->product) ? $model->product->ItemId : '';
                    return $this->html($this->link("https://1688cart.com/product/" . $product_id, 'Click', ['target' => '_blank']));
                })
                ->excludeFromExport(),
            Column::make('Quantity', 'quantity')
                ->format(function (OrderItem $model) {
                    return $this->html('<span class="quantity">' . $model->quantity . '</span>');
                }),
            Column::make('ProductsValue', 'product_value')
                ->format(function (OrderItem $model) {
                    return $this->html('<span class="product_value">' . $model->product_value . '</span>');
                }),
            Column::make('LocalDelivery', 'chinaLocalDelivery'),
            Column::make('Coupon Value', 'coupon_contribution')
                ->format(function (OrderItem $model) {
                    $coupon = $model->coupon_contribution ? $model->coupon_contribution : 0;
                    return $this->html('<span class="coupon_contribution">' . $coupon . '</span>');
                }),
            Column::make('1stPayment', 'first_payment')
                ->format(function (OrderItem $model) {
                    return $this->html('<span class="first_payment">' . $model->first_payment . '</span>');
                }),
            Column::make('2ndPayment TrxID', 'full_payment')
                ->format(function (OrderItem $model) {
                    return $this->html('<span class="full_payment">' . $model->full_payment . '</span>');
                }),
            Column::make('Outofstock', 'out_of_stock')
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
            Column::make('Weightcharges', 'shipping_charge')
                ->format(function (OrderItem $model) {
                    return $this->html('<span class="shipping_charge">' . $model->shipping_charge . '</span>');
                }),
            Column::make('CourierBill', 'courier_bill')
                ->format(function (OrderItem $model) {
                    return $this->html('<span class="courier_bill">' . $model->courier_bill . '</span>');
                }),
            Column::make('LastPayment', 'last_payment')
                ->format(function (OrderItem $model) {
                    return $this->html('<span class="last_payment">' . $model->last_payment . '</span>');
                }),
            Column::make('Due', 'due_payment')
                ->format(function (OrderItem $model) {
                    return $this->html('<span class="due_payment">' . $model->due_payment . '</span>');
                }),
            Column::make('Ref.Invoice', 'invoice_no')
                ->format(function (OrderItem $model) {
                    return $this->html('<span class="invoice_no">' . $model->invoice_no . '</span>');
                }),
            Column::make(__('Action'), 'action')
                ->format(function (OrderItem $model) {
                    $htmlHref = '<div class="d-flex"><a href="' . route('admin.order.wallet.details', $model->id) . '" class="btn btn-secondary btn-sm mr-2" data-method="show" data-toggle="tooltip" data-placement="top" title="Order Details"><i class="fa fa-file-o"></i></a>
                        <a href="' . route('admin.order.show', $model->order_id) . '" class="btn btn-primary btn-sm" data-method="show" data-toggle="tooltip" data-placement="top" title="View Invoice"><i class="fa fa-file-o"></i></a></div>';
                    return $this->html($htmlHref);
                })
                ->excludeFromExport(),
        ];
    }


    public function setTableHeadAttributes($attribute): array
    {
        if ($attribute == 'action') {
            return ['style' => 'min-width:80px;'];
        } elseif ($attribute == 'name') {
            return ['style' => 'min-width:260px;'];
        } elseif ($attribute == 'order_item_number') {
            return ['style' => 'min-width: 120px'];
        } elseif ($attribute == 'transaction_id') {
            return ['style' => 'min-width: 130px'];
        } elseif ($attribute == 'order_number') {
            return ['style' => 'min-width: 150px'];
        } elseif ($attribute == 'checkbox') {
            return ['style' => 'min-width: 100px'];
        }
        return [
            'style' => 'min-width:120px'
        ];
    }

    public function setTableHeadClass($attribute): ?string
    {
        $array = ['id', 'image', 'shipped_by', 'chinaLocalDelivery', '1688_link', 'action', 'due_payment'];
        if (in_array($attribute, $array)) {
            $allSelect = $attribute == 'id' ? 'allSelectTitle' : '';
            return ' text-center ' . $allSelect;
        }

        $array1 = ['checkbox'];
        if (in_array($attribute, $array1)) {
            $allSelect = $attribute == 'id' ? 'allSelectTitle' : '';
            return ' text-center sticky sticky1' . $allSelect;
        }

        $array2 = ['created_at'];
        if (in_array($attribute, $array2)) {
            $allSelect = $attribute == 'id' ? 'allSelectTitle' : '';
            return ' text-center sticky sticky2' . $allSelect;
        }

        $array3 = ['order.transaction_id'];
        if (in_array($attribute, $array3)) {
            $allSelect = $attribute == 'id' ? 'allSelectTitle' : '';
            return ' text-center sticky sticky3' . $allSelect;
        }

        $array4 = ['order_item_number'];
        if (in_array($attribute, $array4)) {
            $allSelect = $attribute == 'id' ? 'allSelectTitle' : '';
            return ' text-center sticky sticky4' . $allSelect;
        }

        $array5 = ['user.name'];
        if (in_array($attribute, $array5)) {
            $allSelect = $attribute == 'id' ? 'allSelectTitle' : '';
            return ' text-center sticky sticky5' . $allSelect;
        }

        $array6 = ['status'];
        if (in_array($attribute, $array6)) {
            $allSelect = $attribute == 'id' ? 'allSelectTitle' : '';
            return ' text-center sticky sticky6' . $allSelect;
        }

        $array7 = ['order_number'];
        if (in_array($attribute, $array7)) {
            $allSelect = $attribute == 'id' ? 'allSelectTitle' : '';
            return ' text-center sticky sticky7' . $allSelect;
        }

        $array8 = ['tracking_number'];
        if (in_array($attribute, $array8)) {
            $allSelect = $attribute == 'id' ? 'allSelectTitle' : '';
            return ' text-center sticky sticky8' . $allSelect;
        }

        return $attribute;
    }


    public function setTableDataClass($attribute, $value): ?string
    {
        $array = ['name'];
        if (in_array($attribute, $array)) {
            return 'align-middle';
        }

        $array1 = ['checkbox'];
        if (in_array($attribute, $array1)) {
            return 'text-center align-middle sticky sticky1';
        }

        $array2 = ['created_at'];
        if (in_array($attribute, $array2)) {
            return 'text-center align-middle sticky sticky2';
        }

        $array3 = ['order.transaction_id'];
        if (in_array($attribute, $array3)) {
            return 'text-center align-middle sticky sticky3';
        }

        $array4 = ['order_item_number'];
        if (in_array($attribute, $array4)) {
            return 'text-center align-middle sticky sticky4';
        }

        $array5 = ['user.name'];
        if (in_array($attribute, $array5)) {
            return 'text-center align-middle sticky sticky5';
        }

        $array6 = ['status'];
        if (in_array($attribute, $array6)) {
            return 'text-center align-middle sticky sticky6';
        }

        $array7 = ['order_number'];
        if (in_array($attribute, $array7)) {
            return 'text-center align-middle sticky sticky7';
        }

        $array8 = ['tracking_number'];
        if (in_array($attribute, $array8)) {
            return 'text-center align-middle sticky sticky8';
        }

        return 'text-center align-middle';
    }

    public function setTableRowId($model): ?string
    {
        return $model->id;
    }
}
