<?php

Breadcrumbs::for('admin.announcement.index', function ($trail) {
    $trail->push('Announcement', route('admin.announcement.index'));
});

Breadcrumbs::for('admin.announcement.trashed', function ($trail) {
    $trail->parent('admin.announcement.index');
    $trail->push('Trashed', route('admin.announcement.trashed'));
});

Breadcrumbs::for('admin.announcement.create', function ($trail) {
    $trail->parent('admin.announcement.index');
    $trail->push('Create Announcement', route('admin.announcement.create'));
});

Breadcrumbs::for('admin.announcement.edit', function ($trail, $id) {
    $trail->parent('admin.announcement.index');
    $trail->push('Edit Announcement', route('admin.announcement.edit', $id));
});
