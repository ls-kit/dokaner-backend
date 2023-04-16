<?php

Breadcrumbs::for('admin.customer.index', function ($trail) {
  $trail->push('Customer', route('admin.customer.index'));
});

Breadcrumbs::for('admin.customer.trashed', function ($trail) {
  $trail->parent('admin.customer.index');
  $trail->push('Trashed', route('admin.customer.trashed'));
});

Breadcrumbs::for('admin.customer.create', function ($trail) {
  $trail->parent('admin.customer.index');
  $trail->push('Create Customer', route('admin.customer.create'));
});

Breadcrumbs::for('admin.customer.edit', function ($trail, $id) {
  $trail->parent('admin.customer.index');
  $trail->push('Edit Customer', route('admin.customer.edit', $id));
});
