<?php

namespace App\Http\Livewire;

use App\Models\Auth\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\TableComponent;
use Rappasoft\LaravelLivewireTables\Traits\HtmlComponents;
use Rappasoft\LaravelLivewireTables\Views\Column;

class CustomerTable extends TableComponent
{
  use HtmlComponents;

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
  public $sortField = 'created_at';
  public $sortDirection = 'desc';

  public $perPage = 20;
  public $perPageOptions = [];
  public $loadingIndicator = true;


  public function query(): Builder
  {
    return User::withCount('orders');
  }

  public function columns(): array
  {
    return [
      Column::make('Customer Name', 'name')
        ->searchable(),
      Column::make('Email', 'email')
        ->searchable(),
      Column::make('Phone', 'phone')
        ->searchable(),
      Column::make('Total Orders', 'orders_count')
        ->sortable(),
      Column::make('Register At', 'created_at')
        ->searchable()
        ->format(function (User $model) {
          return Carbon::parse($model->created_at)->diffForHumans();
        }),
      Column::make(__('Action'), 'action')
        ->format(function (User $model) {
          return view('backend.content.customer.includes.actions', ['customer' => $model]);
        }),
    ];
  }


  public function setTableHeadClass($attribute): ?string
  {
    $array = ['created_at', 'orders_count', 'status', 'action'];
    if (in_array($attribute, $array)) {
      return ' text-center';
    }
    return $attribute;
  }


  public function setTableDataClass($attribute, $value): ?string
  {
    $array = ['created_at', 'orders_count', 'status', 'action'];
    if (in_array($attribute, $array)) {
      return ' text-center';
    }
    return $attribute;
  }
}
