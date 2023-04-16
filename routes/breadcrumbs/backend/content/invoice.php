<?php

Breadcrumbs::for('admin.invoice.index', function ($trail) {
  $trail->push('Invoice', route('admin.invoice.index'));
});

Breadcrumbs::for('admin.invoice.trashed', function ($trail) {
  $trail->parent('admin.invoice.index');
  $trail->push('Trashed', route('admin.invoice.trashed'));
});
