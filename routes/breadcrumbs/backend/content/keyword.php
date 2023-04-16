<?php

Breadcrumbs::for('admin.keyword.index', function ($trail) {
    $trail->push(__('Keywords'), route('admin.keyword.index'));
});

Breadcrumbs::for('admin.keyword.trashed', function ($trail) {
    $trail->parent('admin.keyword.index');
    $trail->push('Trashed', route('admin.keyword.trashed'));
});

Breadcrumbs::for('admin.keyword.create', function ($trail) {
    $trail->parent('admin.keyword.index');
    $trail->push(__('Create'), route('admin.keyword.create'));
});

Breadcrumbs::for('admin.keyword.edit', function ($trail, $id) {
    $trail->parent('admin.keyword.index');
    $trail->push(__('Edit'), route('admin.keyword.edit', $id));
});
