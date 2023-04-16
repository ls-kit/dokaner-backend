<?php

namespace App\Http\Livewire;

use App\Models\Content\OrderItem;
use App\Models\Content\Order;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\TableComponent;
use Rappasoft\LaravelLivewireTables\Traits\HtmlComponents;
use Rappasoft\LaravelLivewireTables\Views\Column;

class CustomerOrderTable extends TableComponent
{
    use HtmlComponents;
    /**
     * @var string
     */
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    public $perPage = 15;
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
        return Order::with('user', 'orderItems')
            ->where('user_id', $user_id);
    }

    public function columns(): array
    {
        return [
            Column::make('Date', 'created_at')
                ->searchable()
                ->format(function (Order $model) {
                    return date('d-M-Y', strtotime($model->created_at));
                }),
            Column::make('Day Count', 'created_at')
                ->format(function (Order $model) {
                    $current_date = date('d-M-Y', strtotime($model->created_at));
                    $current_day = date('l', strtotime($current_date));
                    $end_date = date('d-M-Y', strtotime(now()));
                    $end_day = date('l', strtotime(now()));
                    $holidays = ['21-Feb', '08-Mar', '26-Mar', '19-Apr', '23-Apr', '01-May', '04-May', '28-Jun',
                                '29-Jun', '15-Aug', '06-Sep', '28-Sep', '24-Oct', '16-Dec', '25-Dec'];

                    if ($end_day != "Friday" && $end_day != "Saturday") {
                        if (in_array(date('d-M', strtotime($end_date)), $holidays)) {
                            $days = 0;
                        } else {
                            $days = 1;
                        }
                    } else {
                        $days = 0;
                    }

                    while($current_date != $end_date) {
                        if ($current_day != "Friday" && $current_day != "Saturday") {
                            if (!in_array(date('d-M', strtotime($current_date)), $holidays)) {
                                $days++;
                            }
                        }

                        $current_date = date('d-M-Y', strtotime($current_date . "+ 1 day"));
                        $current_day = date('l', strtotime($current_date));
                    }

                    return $days;
                }),
            Column::make('Invoice', 'order_number')
                ->searchable()
                ->sortable()
                ->format(function (Order $model) {
                    return $model->order_number;
                }),
            Column::make('TotalAmount', 'amount')
                ->searchable(),
            Column::make('1stPayment', 'needToPay')
                ->format(function (Order $model) {
                    return $model->needToPay;
                }),
            Column::make('Due', 'dueForProducts')
                ->format(function (Order $model) {
                    return $this->html('<span class="dueForProducts">' . $model->dueForProducts . '</span>');
                }),
            Column::make('TrxId', 'trxId')
                ->format(function (Order $model) {
                    $trxId = json_decode($model->trxId);
                    if (isset($trxId->payment_1st)) {
                        return $this->html('<div class="trxId">Initial: ' . $trxId->payment_1st . '</div><div class="trxId">Final: ' . $trxId->payment_2nd . '</div>');
                    } else {
                        return $this->html('<div class="trxId">Initial: ' . $model->trxId . '</div>');
                    }
                }),
            Column::make('Ref', 'refNumber')
                ->format(function (Order $model) {
                    return $this->html('<div class="trxId">' . $model->refNumber . '</div>');
                }),
            Column::make('Status', 'status')
                ->searchable()
                ->format(function (Order $model) {
                    $status = str_replace('-', ' ', $model->status);
                    return ucwords($status);
                }),
            Column::make('Order Numbers', 'orderItems.order_item_number')
                ->hide()
                ->searchable()
                ->format(function (Order $model) {}),
            Column::make(__('Action'), 'action')
                ->format(function (Order $model) {
                    $tan_id = $model->order->transaction_id ?? '';
                    $details = '<a href="' . route('frontend.user.order-details', $model) . '" class="btn btn-sm btn-success">Details</a>';
                    $payNow = '<a href="' . route('frontend.user.failedOrderPayNow', $tan_id) . '" class="btn btn-fill-line btn-sm">Pay Now</a>';
                    $button = $model->status == 'Waiting for Payment' ? $payNow : $details;
                    return $this->html($button);
                }),
        ];
    }


    public function setTableHeadClass($attribute): ?string
    {
        $array = ['created_at', 'order_number', 'amount', 'needToPay', 'dueForProducts', 'due_payment', 'status', 'action'];
        if (in_array($attribute, $array)) {
            return $attribute . ' text-center';
        }
        return $attribute;
    }


    public function setTableDataClass($attribute, $value): ?string
    {
        // $array = ['name'];
        // if (in_array($attribute, $array)) {
        //   return 'align-middle';
        // }
        return 'text-center align-middle';
    }

    public function setTableRowId($model): ?string
    {
        return $model->id;
    }
}
