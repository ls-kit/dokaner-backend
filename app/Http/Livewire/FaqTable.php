<?php

namespace App\Http\Livewire;

use App\Models\Content\Post;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\TableComponent;
use Rappasoft\LaravelLivewireTables\Traits\HtmlComponents;
use Rappasoft\LaravelLivewireTables\Views\Column;

class FaqTable extends TableComponent
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

    public $perPage = 15;
    public $perPageOptions = [];
    public $loadingIndicator = true;

    public function query(): Builder
    {
        return Post::with('user')->where('post_type', 'faq');
    }

    public function columns(): array
    {
        return [
            Column::make('#ID', 'id')
                ->sortable(),
            Column::make('Title', 'post_title')
                ->searchable(),
            Column::make('Faq Type', 'post_format')
                ->searchable()
                ->format(function (Post $model) {
                    return readable_status($model->post_format);
                }),
            Column::make('Status', 'post_status')
                ->searchable()
                ->format(function (Post $model) {
                    $status = $model->post_status == 'publish' ? '<span class="badge badge-success">Publish</span>' : '<span class="badge badge-info">' . $model->post_status . '</span>';
                    return $this->html($status);
                }),
            Column::make('Last Update', 'updated_at')
                ->searchable()
                ->format(function (Post $model) {
                    return Carbon::parse($model->updated_at)->diffForHumans();
                }),
            Column::make('Author', 'user.name')
                ->searchable(),
            Column::make(__('Action'), 'action')
                ->format(function (Post $model) {
                    return view('backend.content.faq.includes.actions', ['faq' => $model]);
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
        $array = ['post_title'];
        if (!in_array($attribute, $array)) {
            return ' text-center';
        }
        return $attribute;
    }


    public function setTableDataClass($attribute, $value): ?string
    {
        $array = ['post_title'];
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
