<?php

Breadcrumbs::for('admin.setting.logo', function ($trail) {
  $trail->push('Logo Settings', route('admin.setting.logo'));
});

Breadcrumbs::for('admin.setting.social', function ($trail) {
  $trail->push('social Link Settings', route('admin.setting.social'));
});
Breadcrumbs::for('admin.setting.general', function ($trail) {
  $trail->push('GeneralSettings', route('admin.setting.general'));
});

Breadcrumbs::for('admin.setting.price', function ($trail) {
  $trail->push('Price Settings', route('admin.setting.price'));
});

Breadcrumbs::for('admin.setting.limit', function ($trail) {
  $trail->push('Order Limitation Settings', route('admin.setting.limit'));
});

Breadcrumbs::for('admin.setting.message', function ($trail) {
  $trail->push('Customer Message Settings', route('admin.setting.message'));
});

Breadcrumbs::for('admin.setting.short.message', function ($trail) {
  $trail->push('Short Message Settings', route('admin.setting.short.message'));
});

Breadcrumbs::for('admin.setting.cache.control', function ($trail) {
  $trail->push('Cache Control', route('admin.setting.cache.control'));
});

Breadcrumbs::for('admin.front-setting.manage.sections', function ($trail) {
  $trail->push('Manage Sections', route('admin.front-setting.manage.sections'));
});

Breadcrumbs::for('admin.front-setting.banner.right', function ($trail) {
  $trail->push('Banner Right Settings', route('admin.front-setting.banner.right'));
});

Breadcrumbs::for('admin.front-setting.topNotice.create', function ($trail) {
  $trail->push('Top Notice', route('admin.front-setting.topNotice.create'));
});

Breadcrumbs::for('admin.front-setting.image.loading.create', function ($trail) {
  $trail->push('Image Loader Setting', route('admin.front-setting.image.loading.create'));
});

Breadcrumbs::for('admin.front-setting.manage.product-page-cards', function ($trail) {
    $trail->push('Manage Product Page Cards', route('admin.front-setting.manage.product-page-cards'));
});

Breadcrumbs::for('admin.front-setting.manage.homepage-cards', function ($trail) {
    $trail->push('Manage Homepage Cards', route('admin.front-setting.manage.homepage-cards'));
});

Breadcrumbs::for('admin.front-setting.checkout-discounts', function ($trail) {
    $trail->push('Checkout Discount Settings', route('admin.front-setting.checkout-discounts'));
});

Breadcrumbs::for('admin.front-setting.footer-brand-settings', function ($trail) {
    $trail->push('Pop-up Banner Settings', route('admin.front-setting.footer-brand-settings'));
});

Breadcrumbs::for('admin.front-setting.payment-qr-codes', function ($trail) {
    $trail->push('Payment QR Codes Settings', route('admin.front-setting.payment-qr-codes'));
});

Breadcrumbs::for('admin.front-setting.manage.featured-categories', function ($trail) {
    $trail->push('Manage Featured Categories', route('admin.front-setting.manage.featured-categories'));
});
