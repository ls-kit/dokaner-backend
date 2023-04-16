<?php

Breadcrumbs::for('admin.contact.index', function ($trail) {
  $trail->push('Manage Contact', route('admin.contact.index'));
});

Breadcrumbs::for('admin.contact.trashed', function ($trail) {
  $trail->parent('admin.contact.index');
  $trail->push('Trashed', route('admin.contact.trashed'));
});

Breadcrumbs::for('admin.contact.show', function ($trail, $id) {
  $trail->parent('admin.contact.index');
  $trail->push('Contact Details', route('admin.contact.show', $id));
});

Breadcrumbs::for('admin.contact.edit', function ($trail, $id) {
  $trail->parent('admin.contact.index');
  $trail->push('Update Contact', route('admin.contact.edit', $id));
});
