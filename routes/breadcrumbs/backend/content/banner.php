<?php

Breadcrumbs::for('admin.banner.index', function ($trail) {
    $trail->push('Banner', route('admin.banner.index'));
});

Breadcrumbs::for('admin.banner.trashed', function ($trail) {
    $trail->parent('admin.banner.index');
    $trail->push('Trashed', route('admin.banner.trashed'));
});

Breadcrumbs::for('admin.banner.create', function ($trail) {
    $trail->parent('admin.banner.index');
    $trail->push('Create Banner', route('admin.banner.create'));
});

Breadcrumbs::for('admin.banner.edit', function ($trail, $id) {
    $trail->parent('admin.banner.index');
    $trail->push('Edit Banner', route('admin.banner.edit', $id));
});
