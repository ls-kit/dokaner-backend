<?php

Breadcrumbs::for('admin.product.index', function ($trail) {
  $trail->push('Product', route('admin.product.index'));
});

Breadcrumbs::for('admin.product.trashed', function ($trail) {
  $trail->parent('admin.product.index');
  $trail->push('Trashed', route('admin.product.trashed'));
});

Breadcrumbs::for('admin.product.create', function ($trail) {
  $trail->parent('admin.product.index');
  $trail->push('Create Product', route('admin.product.create'));
});

Breadcrumbs::for('admin.product.edit', function ($trail, $id) {
  $trail->parent('admin.product.index');
  $trail->push('Edit Product', route('admin.product.edit', $id));
});
