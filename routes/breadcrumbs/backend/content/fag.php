<?php

Breadcrumbs::for('admin.faq.index', function ($trail) {
    $trail->push('Frequently Asked Question', route('admin.faq.index'));
});

Breadcrumbs::for('admin.faq.trashed', function ($trail) {
    $trail->parent('admin.faq.index');
    $trail->push('Trashed', route('admin.faq.trashed'));
});

Breadcrumbs::for('admin.faq.create', function ($trail) {
    $trail->parent('admin.faq.index');
    $trail->push('Create FAQs', route('admin.faq.create'));
});

Breadcrumbs::for('admin.faq.edit', function ($trail, $id) {
    $trail->parent('admin.faq.index');
    $trail->push('Edit FAQs', route('admin.faq.edit', $id));
});
