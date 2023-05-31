<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DetailController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\InStockController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\OutStockController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\HistoryStockController;
use App\Http\Controllers\Admin\DetailTransactionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'login')->name('login')->middleware('isLogin');
    Route::post('/login', 'doLogin')->name('do.login');
    Route::get('/register', 'register')->name('register')->middleware('isLogin');
    Route::post('/register', 'doRegister')->name('do.register');
    Route::get('/logout', 'logout')->name('logout');
});

Route::middleware('auth', 'OnlyAdmin')->group(function () {
    Route::prefix('admin')->group(function () {
        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index']);
        Route::get('/profile', [DashboardController::class, 'editProfile']);
        Route::match(['put', 'post'], '/profile', [DashboardController::class, 'saveProfile'])->name('admin.profile');

        // Category
        Route::resource('/category', CategoryController::class);

        // Product
        Route::resource('/product', ProductController::class);

        // Stock
        Route::prefix('stock')->group(function () {
            // Stock Masuk
            Route::resource('/masuk', InStockController::class);

            // Stock Keluar
            Route::get('/keluar', [OutStockController::class, 'index']);

            // History Stock
            Route::get('/stock-history', [HistoryStockController::class, 'index']);
        });

        // Transaction
        Route::get('/transaction', [TransactionController::class, 'index']);
        Route::get('/transaction/{id}/show', [DetailTransactionController::class, 'show']);

        // FAQ
        Route::resource('/faq', FaqController::class);
    });
});

// Home
Route::middleware('CheckRole')->group(function () {
    Route::get('/', [HomeController::class, 'index']);
    Route::get('/search', [HomeController::class, 'search']);
    Route::get('/category/{id}', [HomeController::class, 'showProductByCategory']);

    Route::get('/product/{id}', [DetailController::class, 'index']);
    Route::post('/product/add-to-cart/{id}', [DetailController::class, 'add']);

    Route::get('/cart', [CartController::class, 'index']);
    Route::get('/cart/{id}', [CartController::class, 'destroy']);

    // Checkout
    Route::post('/cart/checkout', [CartController::class, 'getCartData']);
    Route::get('/checkout', [CheckoutController::class, 'index']);
    Route::get('/checkout/courier', function () {
        return view('customer.cart.courier');
    });
    Route::get('/checkout/address/{id}', [ProfileController::class, 'address']); //pinjem function dari controller profile dulu

    // About Us
    Route::view('/about', 'customer.about.index');

    // Profile User
    Route::get('/profile/{id}', [ProfileController::class, 'editProfile']);
    Route::match(['put', 'post'], '/user/profile', [ProfileController::class, 'saveProfile'])->name('user.profile');
});
