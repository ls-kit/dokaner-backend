<?php

namespace App\Http\Livewire;

use App\Models\Content\Order;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\TableComponent;
use Rappasoft\LaravelLivewireTables\Traits\HtmlComponents;
use Rappasoft\LaravelLivewireTables\Views\Column;

class TrashedOrderTable extends TableComponent
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
        return Order::onlyTrashed()->with('user');
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
                ->sortable()
                ->format(function (Order $model) {
                    return date('d-M-Y', strtotime($model->created_at));
                }),
            Column::make('Order No.', 'order_number')
                ->searchable()
                ->sortable()
                ->format(function (Order $model) {
                    return $model->order_number;
                }),
            Column::make('Transaction No', 'transaction_id')
                ->searchable()
                ->sortable(),
            Column::make('Customer', 'name')
                ->searchable()
                ->sortable(),
            Column::make('Amount', 'amount')
                ->searchable()
                ->sortable(),
            Column::make('Paid', 'needToPay')
                ->searchable()
                ->sortable(),
            Column::make('Due', 'dueForProducts')
                ->searchable()
                ->sortable(),
            Column::make('Status', 'status')
                ->searchable()
                ->sortable(),
            Column::make('Actions')
                ->format(function (Order $model) {
                    return view('backend.content.order.includes.actions', ['order' => $model, 'incomplete' => true]);
                })
        ];
    }


    public function setTableRowId($model): ?string
    {
        return $model->id;
    }
}
