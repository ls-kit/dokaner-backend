<?php

Breadcrumbs::for('admin.taxonomy.index', function ($trail) {
  $trail->push(__('Categories'), route('admin.taxonomy.index'));
});

Breadcrumbs::for('admin.taxonomy.trashed', function ($trail) {
  $trail->parent('admin.taxonomy.index');
  $trail->push('Trashed', route('admin.taxonomy.trashed'));
});


Breadcrumbs::for('admin.taxonomy.create', function ($trail) {
  $trail->parent('admin.taxonomy.index');
  $trail->push(__('Create'), route('admin.taxonomy.create'));
});

Breadcrumbs::for('admin.taxonomy.edit', function ($trail, $id) {
  $trail->parent('admin.taxonomy.index');
  $trail->push(__('Edit'), route('admin.taxonomy.edit', $id));
});


Breadcrumbs::for('admin.sub.taxonomy.index', function ($trail) {
  $trail->push(__('Sub Categories'), route('admin.sub.taxonomy.index'));
});

Breadcrumbs::for('admin.sub.taxonomy.create', function ($trail) {
  $trail->parent('admin.sub.taxonomy.index');
  $trail->push(__('Create Sub Category'), route('admin.sub.taxonomy.create'));
});

Breadcrumbs::for('admin.sub.taxonomy.edit', function ($trail, $id) {
  $trail->parent('admin.sub.taxonomy.index');
  $trail->push(__('Edit Sub Category'), route('admin.sub.taxonomy.edit', $id));
});
