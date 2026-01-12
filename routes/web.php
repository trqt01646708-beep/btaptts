<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Frontend\PostController as FrontendPostController;
use App\Http\Controllers\Frontend\ProductController as FrontendProductController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\RoleController as AdminRoleController;
use App\Http\Controllers\Admin\PermissionController as AdminPermissionController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;

/*
|--------------------------------------------------------------------------
| Frontend Routes
|--------------------------------------------------------------------------
*/

// Trang chủ
Route::get('/', [HomeController::class, 'index'])->name('home');

// Trang liên hệ
Route::get('/lien-he', [HomeController::class, 'contact'])->name('contact');
Route::post('/lien-he', [HomeController::class, 'sendContact'])->name('contact.submit');

// Sản phẩm
Route::prefix('san-pham')->group(function () {
    Route::get('/', [FrontendProductController::class, 'index'])->name('products.index');
    Route::get('/giam-gia', [FrontendProductController::class, 'sale'])->name('products.sale');
    Route::get('/danh-muc/{slug}', [FrontendProductController::class, 'category'])->name('products.category');
    Route::get('/{slug}', [FrontendProductController::class, 'show'])->name('products.show');
});

// Bài viết
Route::prefix('bai-viet')->group(function () {
    Route::get('/', [FrontendPostController::class, 'index'])->name('posts.index');
    Route::get('/danh-muc/{slug}', [FrontendPostController::class, 'category'])->name('posts.category');
    Route::get('/{slug}', [FrontendPostController::class, 'show'])->name('posts.show');
});

// Giỏ hàng
Route::prefix('gio-hang')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('cart.index');
    Route::post('/them', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cap-nhat', [CartController::class, 'update'])->name('cart.update');
    Route::post('/xoa', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/xoa-tat-ca', [CartController::class, 'clear'])->name('cart.clear');
    Route::get('/so-luong', [CartController::class, 'getCount'])->name('cart.count');
});

// Thanh toán
Route::prefix('thanh-toan')->group(function () {
    Route::get('/', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/thanh-cong/{orderNumber}', [CheckoutController::class, 'success'])->name('checkout.success');
});

// Đơn hàng (yêu cầu đăng nhập)
Route::prefix('don-hang')->middleware('auth')->group(function () {
    Route::get('/', [OrderController::class, 'index'])->name('orders.history');
    Route::get('/{orderNumber}', [OrderController::class, 'show'])->name('orders.show');
    Route::put('/{orderNumber}/huy', [OrderController::class, 'cancel'])->name('orders.cancel');
});

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/

// User Authentication
Route::middleware('guest')->group(function () {
    Route::get('/dang-nhap', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/dang-nhap', [LoginController::class, 'login'])->name('login.post');
    Route::get('/dang-ky', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/dang-ky', [RegisterController::class, 'register']);
});

Route::get('/dang-xuat', [LoginController::class, 'logout'])->name('logout');

// Admin Authentication
Route::prefix('admin')->group(function () {
    Route::get('/login', [LoginController::class, 'showAdminLoginForm'])->name('admin.login');
    Route::post('/login', [LoginController::class, 'adminLogin']);
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // Quản lý bài viết
    Route::resource('posts', AdminPostController::class);

    // Quản lý sản phẩm
    Route::resource('products', AdminProductController::class);

    // Quản lý danh mục
    Route::resource('categories', AdminCategoryController::class);

    // Quản lý người dùng
    Route::resource('users', AdminUserController::class);

    // Quản lý vai trò
    Route::resource('roles', AdminRoleController::class);

    // Quản lý quyền hạn
    Route::resource('permissions', AdminPermissionController::class);

    // Quản lý đơn hàng
    Route::get('orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::put('orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::put('orders/{order}/payment', [AdminOrderController::class, 'updatePayment'])->name('orders.update-payment');
    Route::delete('orders/{order}', [AdminOrderController::class, 'destroy'])->name('orders.destroy');
});
