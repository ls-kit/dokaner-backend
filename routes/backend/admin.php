<?php

use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\Content\SettingController;
use App\Http\Controllers\Backend\Content\InvoiceController;
use App\Http\Controllers\Backend\Content\OrderController;
use App\Http\Controllers\Backend\Content\ProductController;
use App\Http\Controllers\Backend\Content\ProductSettingsController;
use App\Http\Controllers\Backend\Content\TaxonomyController;

// All route names are prefixed with 'admin.'.
Route::redirect('/', '/admin/dashboard', 301);
Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('export/{table}', [DashboardController::class, 'export'])->name('export');

Route::namespace('Content')->group(function () {
  Route::get('product/trashed', 'ProductController@trashed')->name('product.trashed');
  Route::get('product/restore/{post}', 'ProductController@restore')->name('product.restore');
  Route::get('product/restore/{post}', 'ProductController@restore')->name('product.restore');
  Route::get('product/duplicate', 'ProductController@duplicateIndex')->name('product.duplicate');
  Route::post('product/multi-delete', [ProductController::class, 'multiDelete']);
  Route::resource('product', 'ProductController')->except('create', 'show');

  Route::get('order/trashed', 'OrderController@trashed')->name('order.trashed');
  Route::get('order/restore/{order}', 'OrderController@restore')->name('order.restore');
  Route::get('order/{order}/print', 'OrderController@orderPrint')->name('order.print');
  Route::get('makeAsPayment/{order}', [OrderController::class, 'makeAsPayment'])->name('order.makeAsPayment');
  Route::get('makeAsFullPayment/{order}', [OrderController::class, 'makeAsFullPayment'])->name('order.makeAsFullPayment');
  Route::get('order/wallet', 'OrderController@walletOrders')->name('order.wallet');
  Route::post('order/wallet/{id}', [OrderController::class, 'walletDetails'])->name('order.wallet.details');
  Route::post('order/show/{id}', [OrderController::class, 'show'])->name('order.show');
  Route::put('order-update-shipping-rate/{id}', [OrderController::class, 'updateShippingRate'])->name('order.shipping-rate');
  Route::put('order-update-adjustment/{id}', [OrderController::class, 'updateAdjustment'])->name('order.adjustment');
  Route::get('ajax-getOrderItems', [OrderController::class, 'getOrderItems'])->name('ajax.getOrderItems');
  Route::resource('order', 'OrderController')->except('edit', 'update', 'show');
  Route::get('invoice/trashed', 'InvoiceController@trashed')->name('invoice.trashed');
  Route::get('invoice/restore/{invoice}', 'InvoiceController@restore')->name('invoice.restore');
  Route::get('invoice/confirm-received/{invoice}', 'InvoiceController@confirm_received')->name('invoice.confirm.received');
  Route::get('invoice/details/{invoice}', [InvoiceController::class, 'details'])->name('invoice.details');
  Route::resource('invoice', 'InvoiceController');

  Route::get('coupon/trashed', 'CouponController@trashed')->name('coupon.trashed');
  Route::get('coupon/restore/{customer}', 'CouponController@restore')->name('coupon.restore');
  Route::get('coupon/log', 'CouponController@couponLog')->name('coupon.log');
  Route::resource('coupon', 'CouponController');

  Route::get('customer/trashed', 'CustomerController@trashed')->name('customer.trashed');
  Route::get('customer/restore/{customer}', 'CustomerController@restore')->name('customer.restore');
  Route::resource('customer', 'CustomerController');

  Route::post('get-slug-from-title', 'PageController@get_slug_from_title');
  Route::get('page/trashed', 'PageController@trashed')->name('page.trashed');
  Route::get('page/restore/{page}', 'PageController@restore')->name('page.restore');
  Route::post('editor/image-upload', 'PageController@editor_image_upload');
  Route::resource('page', 'PageController');


  Route::resource('menu', 'MenuController')->except('show');

  Route::get('taxonomy/trashed', 'TaxonomyController@trashed')->name('taxonomy.trashed');
  Route::get('taxonomy/restore', 'TaxonomyController@restore')->name('taxonomy.restore');

  Route::post('taxonomy/make-top', [TaxonomyController::class, 'makeAsTop']);
  Route::post('taxonomy/make-active', [TaxonomyController::class, 'makeActive']);
  Route::post('taxonomy/make-delete', [TaxonomyController::class, 'makeDelete']);

  Route::resource('taxonomy', 'TaxonomyController');




  Route::resource('contact', 'ContactController')->except('create', 'store');


  Route::get('faq/trashed', 'FaqController@trashed')->name('faq.trashed');
  Route::get('faq/restore/{faq}', 'FaqController@restore')->name('faq.restore');
  Route::resource('faq', 'FaqController');

  Route::get('announcement/trashed', 'AnnouncementController@trashed')->name('announcement.trashed');
  Route::get('announcement/restore/{faq}', 'AnnouncementController@restore')->name('announcement.restore');
  Route::resource('announcement', 'AnnouncementController');

  Route::get('banner/trashed', 'BannerController@trashed')->name('banner.trashed');
  Route::get('banner/restore/{faq}', 'BannerController@restore')->name('banner.restore');
  Route::resource('banner', 'BannerController');


  Route::group(['prefix' => 'setting', 'as' => 'setting.'], function () {
    Route::get('price', [SettingController::class, 'price'])->name('price');
    Route::get('limit', [SettingController::class, 'limit'])->name('limit');
    Route::post('limitationStore', [SettingController::class, 'limitationStore'])->name('limitationStore');

    Route::get('message', [SettingController::class, 'message'])->name('message');
    Route::post('message-store', [SettingController::class, 'messageStore'])->name('message.store');


    Route::post('airShippingStore', [SettingController::class, 'airShippingStore'])->name('airShippingStore');
    Route::post('logo-store', [SettingController::class, 'logoStore'])->name('logoStore');
    Route::post('social-store', [SettingController::class, 'socialStore'])->name('socialStore');
    Route::get('general', [SettingController::class, 'general'])->name('general');

    Route::post('short-message', [SettingController::class, 'shortMessageStore'])->name('short.message.store');


    Route::get('cache-control', [SettingController::class, 'cacheControl'])->name('cache.control');
    Route::post('cache-control-store', [SettingController::class, 'cacheClear'])->name('cache.control.store');
    Route::post('cache-control-all-store', [SettingController::class, 'cacheClearAll'])->name('cache.control.all.store');
  });

  Route::group(['prefix' => 'front-setting', 'as' => 'front-setting.'], function () {
    Route::get('top-notice', [SettingController::class, 'topNoticeCreate'])->name('topNotice.create');
    Route::post('top-notice', [SettingController::class, 'topNoticeStore'])->name('topNotice.store');


    Route::get('manage-sections', [SettingController::class, 'manageSections'])->name('manage.sections');
    Route::post('manage-section-store', [SettingController::class, 'manageSectionsStore'])->name('manage.section.store');

    Route::post('cache-setting', [SettingController::class, 'cacheSetting'])->name('manage.cache.setting');

    Route::get('banner-right', [SettingController::class, 'bannerRight'])->name('banner.right');
    Route::post('banner-right', [SettingController::class, 'bannerRightStore'])->name('banner.right.store');

    Route::get('image-loader', [SettingController::class, 'createImageLoader'])->name('image.loading.create');
    Route::post('image-loader', [SettingController::class, 'storeImageLoader'])->name('image.loading.store');

    Route::get('manage-product-page-cards', [SettingController::class, 'manageProductPageCards'])->name('manage.product-page-cards');
    Route::post('manage-product-page-cards-store', [SettingController::class, 'manageProductPageCardsStore'])->name('manage.product-page-cards.store');

    Route::get('manage-homepage-cards', [SettingController::class, 'manageHomepageCards'])->name('manage.homepage-cards');
    Route::post('manage-homepage-cards-store', [SettingController::class, 'manageHomepageCardsStore'])->name('manage.homepage-cards.store');

    Route::get('checkout-discounts', [SettingController::class, 'checkoutDiscounts'])->name('checkout-discounts');
    Route::post('checkout-discounts', [SettingController::class, 'checkoutDiscountsStore'])->name('checkout-discounts.store');

    Route::get('popup-banner-settings', [SettingController::class, 'footerBrandSettings'])->name('footer-brand-settings');
    Route::post('popup-banner-settings-store', [SettingController::class, 'footerBrandSettingsStore'])->name('footer-brand-settings-store');

    Route::get('payment-qr-codes', [SettingController::class, 'paymentQrCodeSettings'])->name('payment-qr-codes');
    Route::post('payment-qr-codes-store', [SettingController::class, 'paymentQrCodeSettingsStore'])->name('payment-qr-codes-store');

    Route::get('manage-featured-categories', [SettingController::class, 'manageFeaturedCategories'])->name('manage.featured-categories');
    Route::post('manage-featured-categories-store', [SettingController::class, 'manageFeaturedCategoriesStore'])->name('manage.featured-categories.store');
  });

});
