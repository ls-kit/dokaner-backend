<?php

namespace App\Http\Livewire\Backend;

use App\Models\Content\Taxonomy;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\TableComponent;
use Rappasoft\LaravelLivewireTables\Traits\HtmlComponents;
use Rappasoft\LaravelLivewireTables\Views\Column;

class CategoryTable extends TableComponent
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
  public $sortField = 'id';
  public $sortDirection = 'desc';

  public $exports = ['csv', 'xls', 'xlsx'];
  // public $exportFileName = 'CategoryTable';

  public $perPage = 20;
  public $perPageOptions = [10, 20, 30, 50, 100, 200, 500, 1000];
  public $loadingIndicator = true;

  public $isParent = false;
  public $category = false;
  public $subCategory = false;
  public $isTop = false;
  public $active = false;

  public function mount($isParent, $category, $subCategory, $isTop, $active)
  {
    $this->isParent = $isParent;
    $this->category = $category;
    $this->subCategory = $subCategory;
    $this->isTop = $isTop;
    $this->active = $active;
  }

  public function query(): Builder
  {
    $taxonomy =  Taxonomy::with('user', 'parent', 'children')
      ->withCount('children');

    if ($this->category && !$this->subCategory) {
      $taxonomy->where('ParentId', $this->category);
    }

    if ($this->subCategory) {
      $taxonomy->where('ParentId', $this->subCategory);
    }

    if ($this->isParent == true) {
      $taxonomy->whereNull('ParentId');
    }
    if ($this->isTop == true) {
      $taxonomy->whereNotNull('is_top');
    }
    if ($this->active == true) {
      $taxonomy->whereNotNull('active');
    }
    return $taxonomy;
  }

  public function columns(): array
  {
    return [
      Column::make('<input type="checkbox" id="allSelectCheckbox">', 'checkbox')
        ->format(function (Taxonomy $model) {
          $checkbox = '<input type="checkbox" class="checkboxItem " data-status="' . $model->status . '" data-user="' . $model->user_id . '" name="wallet[]" value="' . $model->id . '">';
          return $this->html($checkbox);
        })->excludeFromExport(),
      Column::make('#ID', 'id')
        ->searchable()
        ->sortable(),
      Column::make('Name', 'name')
        ->searchable(),
      Column::make('Keyword', 'keyword')
        ->searchable(),
      Column::make('ProviderType', 'ProviderType')
        ->searchable()
        ->format(function (Taxonomy $model) {
          return $model->ProviderType ? $model->ProviderType : 'N/A';
        }),
      Column::make('OTC_ID', 'otc_id')
        ->searchable(),
      Column::make('Parent', 'parent.name')
        ->searchable()
        ->format(function (Taxonomy $model) {
          return $model->parent ? $model->parent->name : 'N/A';
        }),
      Column::make('Picture', 'picture')
        ->format(function (Taxonomy $model) {
          $picture = $model->picture ? $model->picture : null;
          if (!$picture) {
            $picture = $model->IconImageUrl ? $model->IconImageUrl : null;
          }
          $picture = $picture ? $picture : 'img/backend/no-image.gif';
          $img = '<img src="' . asset($picture) . '" style="width:80px" />';
          return $this->html($img);
        }),
      Column::make('Icon', 'icon')
        ->format(function (Taxonomy $model) {
          $icon = $model->icon ? $model->icon : 'img/backend/no-image.gif';
          $img = '<img src="' . asset($icon) . '" style="width:30px" />';
          return $this->html($img);
        }),
      Column::make('Children', 'children_count')
        ->sortable()
        ->format(function (Taxonomy $model) {
          return $model->children_count ? $model->children_count : $model->children1_count;
        }),
      Column::make('Top', 'is_top')
        ->searchable()
        ->format(function (Taxonomy $model) {
          $isTop = '<span class="badge badge-danger" title="Make as top">No</span>';
          if ($model->is_top) {
            $isTop = '<span class="badge badge-success" title="Remove from top">Yes</span>';
          }
          return $this->html($isTop);
        }),
      Column::make('Active', 'active')
        ->searchable()
        ->format(function (Taxonomy $model) {
          $active = $model->active ? '<span class="badge badge-success" title="Active">Yes</span>' : '<span class="badge badge-danger" title="Inactive">No</span>';
          return $this->html($active);
        }),
      Column::make('AddedBy', 'user.name')
        ->searchable(),
      Column::make(__('Action'), 'action')
        ->format(function (Taxonomy $model) {
          return view('backend.content.taxonomy.includes.actions', ['taxonomy' => $model]);
        }),
    ];
  }


  public function setTableHeadAttributes($attribute): array
  {
    if ($attribute == 'action') {
      return ['style' => 'min-width:80px;'];
    }
    return [];
  }

  public function setTableHeadClass($attribute): ?string
  {
    $array = ['name', 'keyword', 'children'];
    if (!in_array($attribute, $array)) {
      return $attribute . ' text-center';
    }
    return $attribute;
  }


  public function setTableDataClass($attribute, $value): ?string
  {
    $array = ['name', 'keyword'];
    if (in_array($attribute, $array)) {
      return $attribute . ' align-middle';
    }
    return $attribute . ' text-center align-middle';
  }

  public function setTableRowId($model): ?string
  {
    return $model->id;
  }
}
