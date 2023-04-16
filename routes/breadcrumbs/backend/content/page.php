<?php

Breadcrumbs::for('admin.page.index', function ($trail) {
    $trail->push('Page', route('admin.page.index'));
});

Breadcrumbs::for('admin.page.trashed', function ($trail) {
    $trail->parent('admin.page.index');
    $trail->push('Trashed', route('admin.page.trashed'));
});

Breadcrumbs::for('admin.page.create', function ($trail) {
    $trail->parent('admin.page.index');
    $trail->push('Create Page', route('admin.page.create'));
});

Breadcrumbs::for('admin.page.edit', function ($trail, $id) {
    $trail->parent('admin.page.index');
    $trail->push('Edit Page', route('admin.page.edit', $id));
});
