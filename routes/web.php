<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\Checkout\CheckoutController;
use App\Http\Controllers\Checkout\DiscountCheckoutController;
use App\Http\Controllers\Checkout\PaymentCheckoutController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\LegalPageController;
use App\Http\Controllers\Profile\ProfileController;
use App\Http\Controllers\Profile\ProfileOrderController;
use App\Http\Controllers\ReferralController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ShoppingCartController;
use App\Http\Middleware\ProductInSession;
use App\Http\Controllers\Auth\OtpController;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\PosController;
use App\Http\Controllers\ReviewController;
use App\Http\Middleware\RolePos;

Route::get('/sms_response', [OtpController::class, 'test'])->name('test');

Route::get('/legal/{slug}', [LegalPageController::class, 'show'])->name('legal.show');
Route::get('/legal/{id}/edit', [LegalPageController::class, 'edit'])->name('legal.edit');
Route::put('/legal/{id}/update', [LegalPageController::class, 'update'])->name('legal.update');

Route::group(['prefix' => 'ssl'], function () {
    Route::post('/purchase', [PaymentCheckoutController::class, 'ssl_purchase'])->name('ssl.purchase');
    Route::post('/success', [PaymentCheckoutController::class, 'ssl_success'])->name('ssl.success');
    Route::post('/failure', [PaymentCheckoutController::class, 'ssl_failure'])->name('ssl.failure');
    Route::post('/cancel', [PaymentCheckoutController::class, 'ssl_cancel'])->name('ssl.cancel');
    Route::post('/ipn', [PaymentCheckoutController::class, 'ssl_ipn'])->name('ssl.ipn');
});

Route::resource('reviews', ReviewController::class);

Route::group(['prefix' => 'pos', 'middleware' => RolePos::class], function () {
    Route::get('/', [PosController::class, 'index'])->name('pos.index');
    Route::post('/store', [PosController::class, 'store'])->name('pos.store');
});

Route::controller(PageController::class)->group(function () {
    Route::get('/', 'home')->name('home');
    Route::get('/offers', 'offers')->name('offers');
    Route::get('/contact-us', 'contact')->name('contact');
    Route::get('/product/{slug}/ref/{ref}', 'product')->name('product');
    Route::get('/product_popup/{slug}/ref/{ref}', 'product_popup')->name('product_popup');
});

Route::controller(BlogController::class)->group(function () {
    Route::get('/blog', 'blog')->name('blog');
    Route::get('/post/{slug}', 'post')->name('post');
    Route::get('/author/{slug}', 'post')->name('post.author');
});

Route::get('/department/{department}', [DepartmentController::class, 'department'])->name('department');

Route::get('/search', [SearchController::class, 'search'])->name('search');

Route::post('/subscribe', [NewsletterController::class, 'newsletter'])->name('subscribe');

Route::post('/contact-form', function () {
    return Redirect::back()->with('success', 'Message sent successfully');
})->name('contact-form');

Route::resource('shopping-cart', ShoppingCartController::class)->only([
    'index',
    'store',
    'update',
    'destroy',
]);

Route::post('/send-login', [OtpController::class, 'login'])->name('send-login');

Route::post('/send-register', [OtpController::class, 'register'])->name('send-register');

Route::middleware('auth')->group(function () {
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::controller(ProfileController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/account-details', 'accountDetails')->name('account-details');
            Route::patch('/account-details', 'update')->name('account-details.update');
        });
        Route::controller(ReferralController::class)->group(function () {
            Route::get('/referrals', 'referrals')->name('referrals');
            Route::get('/refund/{code}/{productId}', 'processRefund')->name('refund.request');
        });
        Route::controller(ReviewController::class)->group(function () {
            Route::get('/review', 'review')->name('review');
            Route::patch('/update/{id}', 'update')->name('review.update');
            Route::post('/store', 'store')->name('review.store');
        });
        Route::controller(ProfileOrderController::class)->group(function () {
            Route::get('/my-orders', 'orders')->name('orders');
            Route::get('/order/{code}', action: 'orderDetails')->name('order');
            Route::get('/order-pdf/{code}', 'invoicePdf')->name('invoice');
        });
    });

    Route::controller(CheckoutController::class)->group(function () {
        Route::get('/checkout', 'checkout')->name('checkout')->middleware(ProductInSession::class);
        Route::post('/checkout/add-single-product', 'addSingleProduct')->name('checkout.add-single-product');
        Route::get('/checkout/add-shopping-cart', 'addShoppingCart')->name('checkout.add-shopping-cart');
    });

    Route::post('/set-shipping', function (Request $request) {
        session()->put('shipping', $request->shipping);
    })->name('set-shipping');

    Route::post('/set-discount', function (Request $request) {
        session()->put('referral_discount', $request->referral_discount);
    })->name('set-discount');

    Route::controller(PaymentCheckoutController::class)->middleware(ProductInSession::class)->group(function () {
        Route::post('/purchase', 'purchase')->name('purchase');
    });
});

require __DIR__ . '/auth.php';
