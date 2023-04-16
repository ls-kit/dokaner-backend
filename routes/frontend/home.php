<?php

use App\Http\Controllers\Frontend\ContactController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\AjaxController;
use App\Http\Controllers\Frontend\User\AccountController;
use App\Http\Controllers\Frontend\User\DashboardController;
use App\Http\Controllers\Frontend\User\ProfileController;
use App\Http\Controllers\SslCommerzPaymentController;

use App\Http\Controllers\Frontend\Content\AddressController;
use App\Http\Controllers\Frontend\Content\WishlistController;
use App\Http\Controllers\Frontend\Auth\OtpLoginController;

/*
 * Frontend Controllers
 * All route names are prefixed with 'frontend.'.
 */

Route::get('/', [HomeController::class, 'index'])->name('index');

Route::post('picture-search', [HomeController::class, 'pictureSearch'])->name('pictureSearch');
Route::get('search', [HomeController::class, 'search'])->name('search');

Route::get('contact-us', [ContactController::class, 'index'])->name('contact');
Route::post('contact/send', [ContactController::class, 'send'])->name('contact.send');

Route::get('about-us', [HomeController::class, 'aboutUs'])->name('aboutUs');
Route::get('fags', [HomeController::class, 'faqs'])->name('faqs');

Route::get('shop-now', [HomeController::class, 'shopNow'])->name('shopNow');
Route::get('products-best-selling', [HomeController::class, 'productsBestSelling'])->name('productsBestSelling');
Route::get('products-customer-loving', [HomeController::class, 'productsCustomerLoving'])->name('productsCustomerLoving');
Route::get('products-buying-on-process', [HomeController::class, 'productsBuyingOnProcess'])->name('productsBuyingOnProcess');

Route::group(['middleware' => ['auth']], function () {
  Route::get('shopping-cart', [HomeController::class, 'shoppingCart'])->name('shoppingCart');
  Route::get('payment', [HomeController::class, 'payment'])->name('payment');

  Route::get('/order/details/{id}', [HomeController::class, 'customerOrderDetails'])->name('customer.order.details');
});

Route::get('product/{item}', [HomeController::class, 'productDetails'])->name('product');

Route::group(['as' => 'ajax.', 'prefix' => 'ajax'], function () {
  Route::post('load-category-products', [AjaxController::class, 'categoryProducts'])->name('categoryProducts');
  Route::post('load-search-products', [AjaxController::class, 'searchProducts'])->name('searchProducts');
  Route::post('load-additional-info', [AjaxController::class, 'getAdditionalInformation']);
  Route::post('load-physical-parameters', [AjaxController::class, 'getPhysicalParameters'])->name('getPhysicalParameters');
  Route::post('load-description', [AjaxController::class, 'getItemDescription'])->name('getItemDescription');
  Route::post('load-seller-information', [AjaxController::class, 'getItemSellerInformation'])->name('getItemSellerInformation');

  Route::post('load-customer-cart', [AjaxController::class, 'LoadCustomerCart']);
  Route::post('reload-product-delivery-cost', [AjaxController::class, 'reloadProductDeliveryCost']);

  Route::post('subscribe-email', [AjaxController::class, 'subscribeEmail'])->name('subscribeEmail');
  Route::post('update-customer-checkout', [AjaxController::class, 'updateCustomerCheckout'])->name('updateCustomerCheckout');
  Route::post('notice-mark-unread', [AjaxController::class, 'noticeMarkUnread'])->name('noticeMarkUnread');
  Route::post('coupon-code-validation', [AjaxController::class, 'couponCodeValidate']);

  Route::post('login-with-otp', [OtpLoginController::class, 'loginWithOtp']); // login with otp
  Route::post('otp-code-verify', [OtpLoginController::class, 'OtpCodeVerify']); // login with otp

  Route::group(['middleware' => 'auth', 'as' => 'customer.'], function () {
    Route::post('address-show', [AddressController::class, 'show']);
    Route::post('address-store-default', [AddressController::class, 'storeDefault'])->name('address.store.default');
    Route::post('address', [AddressController::class, 'store'])->name('address.store');
    Route::post('delete', [AddressController::class, 'destroy'])->name('address.delete');

    Route::post('order-confirm', [AjaxController::class, 'orderConfirm'])->name('order.confirm');

    Route::delete('incomplete/order/delete/{id}', [AjaxController::class, 'incompleteOrderDelete'])->name('incomplete.order.delete');
  });
});


/*
 * These frontend controllers require the user to be logged in
 * All route names are prefixed with 'frontend.'
 * These routes can not be hit if the password is expired
 */
Route::group(['middleware' => ['auth', 'password_expires']], function () {
  Route::group(['namespace' => 'User', 'as' => 'user.'], function () {
    // User Dashboard Specific
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('notification', [DashboardController::class, 'notification'])->name('dashboard.notification');

    // manage order information
    Route::get('order-details/{tranId}', [DashboardController::class, 'orderDetails'])->name('order-details');
    Route::get('failed-order-pay-now/{tranId}', [DashboardController::class, 'failedOrderPayNow'])->name('failedOrderPayNow');

    // manage Invoice information
    Route::get('invoice-details/{invoice_id}', [DashboardController::class, 'invoiceDetails'])->name('invoice-details');
    Route::get('invoice-pay-now/{tranId}', [DashboardController::class, 'invoicePayNow'])->name('invoice.payNow');


    // User Account Specific
    Route::get('account', [AccountController::class, 'index'])->name('account');
    Route::get('update-information', [AccountController::class, 'updateInformation'])->name('update.information');
    Route::post('update-information', [AccountController::class, 'updateInformationStore'])->name('update.information.store');

    // User Profile Specific
    Route::patch('profile/update', [ProfileController::class, 'update'])->name('profile.update');

    // show user wishlist
    Route::get('wishlist', [WishlistController::class, 'index'])->name('wishlist.index');

    // user wishlist updated
    Route::post('wishlist/store', [WishlistController::class, 'store'])->name('wishlist.store');
    Route::post('wishlist/count-wishlist', [WishlistController::class, 'show'])->name('wishlist.show');
    Route::get('wishlist-remove/{wishlist}', [WishlistController::class, 'remove'])->name('wishlist.remove');
  });
});




// SSLCOMMERZ Start
Route::post('/pay-via-ajax', [SslCommerzPaymentController::class, 'payViaAjax']); // for some of
Route::post('sslcommerz/payment', [SslCommerzPaymentController::class, 'index']);
Route::post('/success', [SslCommerzPaymentController::class, 'success']);
Route::post('/fail', [SslCommerzPaymentController::class, 'fail']);
Route::post('/cancel', [SslCommerzPaymentController::class, 'cancel']);
Route::post('/ipn', [SslCommerzPaymentController::class, 'ipn']);
//SSLCOMMERZ END
