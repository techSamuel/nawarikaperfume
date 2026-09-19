<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductReviewController;
use App\Http\Controllers\Admin\VisitorLogController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/shop', [ShopController::class, 'index'])->name('shop');
Route::get('/product/{slug}', [ShopController::class, 'show'])->name('product.show');
Route::get('/track', [HomeController::class, 'trackPage'])->name('order.track.page');
Route::get('/track-order', [HomeController::class, 'trackOrder'])->name('order.track');
Route::get('/language/{locale}', [HomeController::class, 'switchLanguage'])->name('language.switch');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::get('/faq', [HomeController::class, 'faq'])->name('faq');
Route::get('/shipping-policy', [HomeController::class, 'shippingPolicy'])->name('shipping.policy');
Route::get('/return-policy', [HomeController::class, 'returnPolicy'])->name('return.policy');
Route::get('/api/products/search', [ShopController::class, 'liveSearch'])->name('api.products.search');
Route::post('/api/visitor/ping', [ShopController::class, 'pingVisitorLog'])->name('api.visitor.ping');

// Secret Artisan Routes
Route::get('/run-migrations', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        return 'Migrations ran successfully! <br> Output: ' . \Illuminate\Support\Facades\Artisan::output();
    } catch (\Exception $e) {
        return 'Error: ' . $e->getMessage();
    }
});

Route::get('/run-seed', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('db:seed', ['--force' => true]);
        return 'Database seeded successfully! Admin user and products have been created. <br> Output: ' . \Illuminate\Support\Facades\Artisan::output();
    } catch (\Exception $e) {
        return 'Error: ' . $e->getMessage();
    }
});

Route::get('/run-setup', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('key:generate', ['--force' => true]);
        \Illuminate\Support\Facades\Artisan::call('config:clear');
        \Illuminate\Support\Facades\Artisan::call('cache:clear');
        return 'Setup completed! App Key Generated and Caches Cleared.';
    } catch (\Exception $e) {
        return 'Error: ' . $e->getMessage();
    }
});

// Cart Routes
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
Route::get('/cart/count', [CartController::class, 'count'])->name('cart.count');
Route::get('/checkout/sidebar', [CheckoutController::class, 'sidebar'])->name('checkout.sidebar');

// Checkout Routes (Public)
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/order/confirmation/{orderNumber}', [CheckoutController::class, 'confirmation'])->name('order.confirmation');

// Authenticated Routes
Route::middleware('auth')->group(function () {


    // Account Routes
    Route::get('/account/orders', [AccountController::class, 'orders'])->name('account.orders');
    Route::get('/account/orders/{orderNumber}', [AccountController::class, 'orderDetail'])->name('account.order-detail');

    // Profile Routes (Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Routes
Route::middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('categories', CategoryController::class)->except(['show']);
        Route::resource('products', ProductController::class)->except(['show']);
        Route::post('/product-reviews', [ProductReviewController::class, 'store'])->name('product-reviews.store');
        Route::delete('/product-reviews/{review}', [ProductReviewController::class, 'destroy'])->name('product-reviews.destroy');

        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
        Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
        Route::delete('/orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');

        Route::get('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
        Route::post('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'store'])->name('settings.store');

        Route::get('/visitors', [VisitorLogController::class, 'index'])->name('visitors.index');
        Route::delete('/visitors/{visitorLog}', [VisitorLogController::class, 'destroy'])->name('visitors.destroy');
        Route::post('/visitors/clear', [VisitorLogController::class, 'clearAll'])->name('visitors.clear');
    });

require __DIR__.'/auth.php';
