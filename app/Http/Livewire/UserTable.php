<?php

namespace App\Http\Livewire;

use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\TableComponent;
use Rappasoft\LaravelLivewireTables\Traits\HtmlComponents;
use Rappasoft\LaravelLivewireTables\Views\Column;

class UserTable extends TableComponent
{
  use HtmlComponents;
  /**
   * @var string
   */
  public $sortField = 'id';
  public $sortDirection = 'desc';
  public $perPage = 30;
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
  public $exports = ['xls', 'pdf', 'csv'];


  public function query(): Builder
  {
    return User::with('roles');
  }

  public function columns(): array
  {
    return [
      Column::make('Full Name', 'first_name')
        ->searchable()
        ->format(function (User $model) {
          return $model->full_name;
        }),
      Column::make('Email', 'email')
        ->searchable(),
      Column::make('Confirmed', 'confirmed')
        ->format(function (User $model) {
          return view('backend.auth.user.includes.confirm', ['user' => $model]);
        }),
      Column::make('Roles', 'roles.name')
        ->searchable()
        ->format(function (User $model) {
          return $model->roles_label;
        }),
      Column::make('Other Permissions', 'other_permission')
        ->format(function (User $model) {
          return $model->permissions_label;
        }),
      Column::make('Social', 'social')
        ->format(function (User $model) {
          return view('backend.auth.user.includes.social-buttons', ['user' => $model]);
        }),
      Column::make('Last Updated', 'updated_at')
        ->searchable()
        ->format(function (User $model) {
          return $model->updated_at->diffForHumans();
        }),
      Column::make('Actions', 'action')
        ->format(function (User $model) {
          return view('backend.auth.user.includes.actions', ['user' => $model]);
        })
    ];
  }

  public function setTableHeadClass($attribute): ?string
  {
    $array = ['action', 'updated_at', 'social', 'other_permission', 'confirmed'];
    if (in_array($attribute, $array)) {
      return ' text-center';
    }
    return $attribute;
  }


  public function setTableDataClass($attribute, $value): ?string
  {
     $array = ['action', 'updated_at', 'social', 'other_permission', 'confirmed'];
     if (in_array($attribute, $array)) {
       return 'text-center align-middle';
     }
    return 'align-middle';
  }

}
