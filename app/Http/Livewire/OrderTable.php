<?php

namespace App\Http\Livewire;

use App\Models\Content\Order;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\TableComponent;
use Rappasoft\LaravelLivewireTables\Traits\HtmlComponents;
use Rappasoft\LaravelLivewireTables\Views\Column;

class OrderTable extends TableComponent
{
    use HtmlComponents;
    /**
     * @var string
     */
    public $sortField = 'id';
    public $sortDirection = 'desc';

    public $perPage = 20;
    public $perPageOptions = [10, 20, 50, 100, 150];
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
        return Order::with('user')->whereNotIn('status', ['Partial Paid']);
    }

    public function columns(): array
    {
        return [
            Column::make('<input type="checkbox" id="allSelectCheckbox">', 'checkbox')
                ->format(function (Order $model) {
                    $checkbox = '<input type="checkbox" class="checkboxItem " data-status="' . $model->status . '" data-user="' . $model->user_id . '" name="wallet[]" value="' . $model->id . '">';
                    return $this->html($checkbox);
                })->excludeFromExport(),
            Column::make('Date', 'created_at')
                ->searchable()
                ->format(function (Order $model) {
                    return date('d-M-Y', strtotime($model->created_at));
                }),
            Column::make('Transaction No', 'transaction_id')
                ->searchable(),
            Column::make('Customer', 'name')
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
                ->searchable(),
            Column::make('Actions', 'action')
                ->format(function (Order $model) {
                    return view('backend.content.order.includes.actions', ['order' => $model]);
                })
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
