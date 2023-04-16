<?php

namespace App\Http\Livewire;

use App\Models\Content\Product;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\TableComponent;
use Rappasoft\LaravelLivewireTables\Traits\HtmlComponents;
use Rappasoft\LaravelLivewireTables\Views\Column;

class ProductTable extends TableComponent
{
  use HtmlComponents;

  protected $options = [
    'bootstrap.classes.table' => 'table table-striped table-bordered',
    'bootstrap.classes.thead' => null,
    'bootstrap.classes.buttons.export' => 'btn btn-info',
    'bootstrap.container' => true,
    'bootstrap.responsive' => true,
  ];

  public $sortDefaultIcon = '<i class="text-muted fa fa-sort"></i>';
  public $ascSortIcon = '<i class="fa fa-sort-up"></i>';
  public $descSortIcon = '<i class="fa fa-sort-down"></i>';
  public $sortField = 'id';
  public $sortDirection = 'desc';

  public $perPage = 20;
  public $perPageOptions = [10, 20, 50, 100, 150];
  public $loadingIndicator = true;

  public $exportFileName = 'Product-table';
  public $exports = [];


  public function query(): Builder
  {
    return  Product::with('user', 'orderItems')->withCount('orderItems');
  }

  public function columns(): array
  {
    return [
      Column::make('<input type="checkbox" id="allSelectCheckbox">', 'checkbox')
        ->format(function (Product $model) {
          $checkbox = '<input type="checkbox" class="checkboxItem " data-status="' . $model->status . '" data-user="' . $model->user_id . '" name="wallet[]" value="' . $model->id . '">';
          return $this->html($checkbox);
        })->excludeFromExport(),
      // Column::make('#ID', 'id')
      //   ->sortable()
      //   ->searchable(),
      Column::make('OTC_Id', 'ItemId')
        ->searchable(),
      Column::make('Picture', 'MainPictureUrl')
        ->format(function (Product $model) {
          $image =  $this->image($model->MainPictureUrl, '', ['class' => 'img-fluid']);
          return $this->html($this->link(url('product/' . $model->ItemId), $image, ['target' => '_blank'], $secure = null, false));
        }),
      Column::make('Title', 'Title')
        ->searchable(),
      Column::make('TaobaoLink', '1688_link')
        ->format(function (Product $model) {
          $url = 'https://item.taobao.com/item.htm?id=' . $model->ItemId . '.html';
          $href = '<a href="' . $url . '" class="btn btn-sm btn-secondary" data-toggle="tooltip" title="' . $url . '" target="_blank">	
          <i class="fa fa-external-link"></i></a>';
          return $this->html($href);
        }),
      Column::make('CategoryId', 'CategoryId')
        ->searchable(),
      Column::make('VendorName', 'VendorName')
        ->searchable(),
      Column::make('Total Orders', 'order_items_count')
        ->sortable(),
      Column::make('Last-Update', 'updated_at')
        ->searchable()
        ->format(function (Product $model) {
          return date('d M, Y', strtotime($model->updated_at));
        }),
      Column::make(__('Action'), 'action')
        ->format(function (Product $model) {
          return view('backend.content.product.includes.actions', ['product' => $model]);
        }),
    ];
  }

  public function setTableHeadAttributes($attribute): array
  {
    if ($attribute == 'action') {
      return ['style' => 'min-width:80px;'];
    } elseif ($attribute == 'MainPictureUrl') {
      return ['style' => 'width:80px;'];
    } elseif ($attribute == 'ItemId') {
      return ['style' => 'min-width: 136px;'];
    } elseif ($attribute == '1688_link') {
      return ['style' => 'width:90px;'];
    } elseif ($attribute == 'checkbox') {
      return ['style' => 'width:90px;'];
    }
    return [
      'style' => 'min-width:120px'
    ];
  }

  public function setTableHeadClass($attribute): string
  {
    $attributes = ['ItemId', '1688_link', 'MainPictureUrl', 'action', 'id', 'checkbox'];
    if (in_array($attribute, $attributes)) {
      return ' align-middle text-center';
    }
    return $attribute;
  }


  public function setTableDataClass($attribute, $value): ?string
  {
    $attributes = ['ItemId', '1688_link', 'MainPictureUrl', 'action', 'id', 'checkbox'];
    if (in_array($attribute, $attributes)) {
      return $attribute . ' align-middle text-center';
    }
    return $attribute;
  }


  public function setTableRowId($model): ?string
  {
    return $model->id;
  }
}
