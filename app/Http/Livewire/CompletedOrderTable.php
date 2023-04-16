<?php

namespace App\Http\Livewire;

use App\Models\Content\Order;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\TableComponent;
use Rappasoft\LaravelLivewireTables\Traits\HtmlComponents;
use Rappasoft\LaravelLivewireTables\Views\Column;

class CompletedOrderTable extends TableComponent
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

    public $exportFileName = 'Order-table';
    public $exports = [];

    public function query(): Builder
    {
        return Order::with('user')
            ->where('user_id', auth()->id())
            ->whereIn('status', ['Waiting for Payment', 'Partial Paid']);
    }

    public function columns(): array
    {
        return [
            Column::make('Date', 'created_at')
                ->searchable()
                ->format(function (Order $model) {
                    return date('d-M-Y', strtotime($model->created_at));
                }),
            Column::make('TransactionNo', 'transaction_id')
                ->searchable(),
            Column::make('Amount', 'amount')
                ->searchable()
                ->format(function (Order $model) {
                    return floating($model->amount);
                }),
            Column::make('Paid', 'needToPay')
                ->searchable()
                ->format(function (Order $model) {
                    return floating($model->needToPay);
                }),
            Column::make('Due', 'dueForProducts')
                ->searchable()
                ->format(function (Order $model) {
                    return floating($model->dueForProducts);
                }),
            Column::make('Status', 'status')
                ->searchable()
            ->format(function (Order $model){
                $status = '<span>'.$model->status.'</span>';
                if($model->status === 'Waiting for Payment'){
                    $status = '<span class="text-danger">'.$model->status.'</span>';
                }
                return $this->html($status);
            }),
            Column::make(__('Action'), 'action')
                ->format(function (Order $model) {
//                    $tan_id = $model->transaction_id ?? '';
//                    $details = '<a href="' . route('frontend.user.order-details', $model) . '" class="btn btn-sm btn-success">Details</a>';
//                    $payNow = '<a href="' . route('frontend.user.failedOrderPayNow', $tan_id) . '" class="btn btn-fill-line btn-sm">Pay Now</a>';
//                    $button = $model->status == 'Waiting for Payment' ? $payNow : $details;
                    return view('frontend.user.includes.actions.paymentAction', ['order' => $model]);
                }),
        ];
    }

    public function setTableHeadClass($attribute): ?string
    {
        $array = ['action', 'status', 'dueForProducts', 'needToPay', 'amount', 'transaction_id', 'created_at'];
        if (in_array($attribute, $array)) {
            return ' text-center';
        }
        return $attribute;
    }


    public function setTableDataClass($attribute, $value): ?string
    {
        $array = ['action', 'status', 'dueForProducts', 'needToPay', 'amount', 'transaction_id', 'created_at'];
        if (in_array($attribute, $array)) {
            return 'text-center align-middle';
        }
        return 'align-middle';
    }

    public function setTableRowId($model): ?string
    {
        return $model->id;
    }

}
