<?php

Breadcrumbs::for('admin.coupon.index', function ($trail) {
  $trail->push('Coupon', route('admin.coupon.index'));
});

Breadcrumbs::for('admin.coupon.log', function ($trail) {
  $trail->push('Coupon Log', route('admin.coupon.log'));
});

Breadcrumbs::for('admin.coupon.trashed', function ($trail) {
  $trail->parent('admin.coupon.index');
  $trail->push('Trashed', route('admin.coupon.trashed'));
});

Breadcrumbs::for('admin.coupon.create', function ($trail) {
  $trail->parent('admin.coupon.index');
  $trail->push('Create Coupon', route('admin.coupon.create'));
});

Breadcrumbs::for('admin.coupon.edit', function ($trail, $id) {
  $trail->parent('admin.coupon.index');
  $trail->push('Edit Coupon', route('admin.coupon.edit', $id));
});
