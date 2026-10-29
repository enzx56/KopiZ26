<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\PreOrderController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SettingController;

/*
|--------------------------------------------------------------------------
| Website Publik (Customer Area)
|--------------------------------------------------------------------------
*/
Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/menu', [PublicController::class, 'menu'])->name('menu');
Route::get('/menu/{slug}', [PublicController::class, 'menuDetail'])->name('menu.detail');
Route::get('/po', [PublicController::class, 'preOrders'])->name('po');
Route::get('/po/{slug}', [PublicController::class, 'preOrderDetail'])->name('po.detail');
Route::get('/keranjang', [PublicController::class, 'cart'])->name('cart');
Route::get('/checkout', [PublicController::class, 'checkout'])->name('checkout');
Route::post('/checkout', [PublicController::class, 'processCheckout'])->name('checkout.process');
Route::get('/pesanan/{orderCode}', [PublicController::class, 'trackOrder'])->name('order.track');

// Halaman Statis & Informasi Brand
Route::get('/tentang', [PublicController::class, 'about'])->name('about');
Route::get('/cara-pesan', [PublicController::class, 'howToOrder'])->name('how-to-order');
Route::get('/kontak', [PublicController::class, 'contact'])->name('contact');
Route::get('/kebijakan-privasi', [PublicController::class, 'privacy'])->name('privacy');
Route::get('/syarat-ketentuan', [PublicController::class, 'terms'])->name('terms');

/*
|--------------------------------------------------------------------------
| Admin Area
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    // Auth Routes
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Protected Routes
    Route::middleware(['auth'])->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // Products
        Route::resource('products', ProductController::class);
        Route::post('products/{product}/toggle-status', [ProductController::class, 'toggleStatus'])->name('products.toggle-status');

        // Categories
        Route::resource('categories', CategoryController::class);

        // Pre-Orders
        Route::resource('pre-orders', PreOrderController::class);

        // Orders
        Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');
        Route::post('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
        Route::get('orders/{order}/print', [OrderController::class, 'printReceipt'])->name('orders.print');

        // Customers
        Route::get('customers', [CustomerController::class, 'index'])->name('customers.index');

        // Reports & Export
        Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('reports/export-csv', [ReportController::class, 'exportCsv'])->name('reports.export-csv');

        // Settings & Content
        Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
        Route::post('settings', [SettingController::class, 'update'])->name('settings.update');
    });
});
