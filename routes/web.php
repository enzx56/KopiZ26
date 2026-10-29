<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\CustomerAuthController;
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
| Website Publik (Customer Area & Katalog)
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    $settings = \App\Models\Setting::pluck('value', 'key')->all();
    $featuredProducts = \App\Models\Product::active()->take(6)->get();
    $activePreOrders = \App\Models\PreOrder::take(2)->get();
    return view('public.home', compact('settings', 'featuredProducts', 'activePreOrders'));
})->name('home');

Route::get('/menu', [PublicController::class, 'menu'])->name('menu');
Route::get('/menu/{slug}', [PublicController::class, 'menuDetail'])->name('menu.detail');
Route::get('/po', [PublicController::class, 'preOrders'])->name('po');
Route::get('/po/{slug}', [PublicController::class, 'preOrderDetail'])->name('po.detail');
Route::get('/keranjang', [PublicController::class, 'cart'])->name('cart');
Route::get('/checkout', [PublicController::class, 'checkout'])->name('checkout');
Route::post('/checkout', [PublicController::class, 'processCheckout'])->name('checkout.process');
Route::get('/pesanan/{orderCode}', [PublicController::class, 'trackOrder'])->name('order.track');

// Pelacakan & Akun Pelanggan (Customer Auth)
Route::get('/lacak', [CustomerAuthController::class, 'trackLookup'])->name('order.lookup');
Route::get('/daftar', [CustomerAuthController::class, 'showRegisterForm'])->name('customer.register');
Route::post('/daftar', [CustomerAuthController::class, 'register'])->name('customer.register.submit');
Route::get('/masuk', [CustomerAuthController::class, 'showLoginForm'])->name('customer.login');
Route::post('/masuk', [CustomerAuthController::class, 'login'])->name('customer.login.submit');
Route::post('/keluar', [CustomerAuthController::class, 'logout'])->name('customer.logout');
Route::get('/pesanan-saya', [CustomerAuthController::class, 'myOrders'])->name('customer.orders');

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
// Pastikan akses ke /admin selalu mulus tanpa pernah memicu error screen
Route::get('/admin', function () {
    if (auth()->guard('web')->check()) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('admin.login');
});

Route::prefix('admin')->name('admin.')->group(function () {
    // Auth Routes
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Protected Routes
    Route::middleware(['auth:web'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

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
