<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\InventoryMovementController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderDetailController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ShippingController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Auth\ChangePasswordController;
use App\Http\Controllers\Seller\SellerController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;


// Authentication Routes
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// Registration Routes
Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);

// Password Reset Routes
Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [ResetPasswordController::class, 'reset']);

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/test', function () {
    return 'Test route is working';
});

Route::get('upload', function () {
    return view('upload');
});

Route::post('upload', [FileUploadController::class, 'store'])->name('file.upload');

// Authenticated routes
Route::middleware(['auth'])->group(function () {
    Route::resource('categories', CategoryController::class);
    Route::resource('customers', CustomerController::class);
    Route::resource('inventories', InventoryController::class);
    Route::resource('inventory-movements', InventoryMovementController::class);
    Route::resource('orders', OrderController::class);
    Route::resource('order-details', OrderDetailController::class);
    Route::resource('payments', PaymentController::class);
    Route::resource('products', ProductController::class);
    Route::resource('reviews', ReviewController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('shippings', ShippingController::class);
    Route::resource('users', UserController::class);

    Route::post('upload', [FileUploadController::class, 'store'])->name('file.upload');
});


// Admin routes
Route::middleware(['auth', 'role:admin-page'])->prefix('admin-page')->name('admin-page.')->group(function() {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');

    // User routes
    Route::get('users', [AdminController::class, 'users'])->name('users.index');
    Route::get('users/create', [AdminController::class, 'createUser'])->name('users.create');
    Route::post('users', [AdminController::class, 'storeUser'])->name('users.store');
    Route::get('users/{id}/edit', [AdminController::class, 'editUser'])->name('users.edit');
    Route::put('users/{id}', [AdminController::class, 'updateUser'])->name('users.update');
    Route::delete('users/{id}', [AdminController::class, 'deleteUser'])->name('users.destroy');

    // Role routes
    Route::get('roles', [AdminController::class, 'roles'])->name('roles.index');
    Route::get('roles/create', [AdminController::class, 'createRole'])->name('roles.create');
    Route::post('roles', [AdminController::class, 'storeRole'])->name('roles.store');
    Route::get('roles/{id}/edit', [AdminController::class, 'editRole'])->name('roles.edit');
    Route::put('roles/{id}', [AdminController::class, 'updateRole'])->name('roles.update');
    Route::delete('roles/{id}', [AdminController::class, 'deleteRole'])->name('roles.destroy');

    // Permission routes
    Route::get('permissions', [AdminController::class, 'permissions'])->name('permissions.index');
    Route::get('permissions/create', [AdminController::class, 'createPermission'])->name('permissions.create');
    Route::post('permissions', [AdminController::class, 'storePermission'])->name('permissions.store');
    Route::get('permissions/{id}/edit', [AdminController::class, 'editPermission'])->name('permissions.edit');
    Route::put('permissions/{id}', [AdminController::class, 'updatePermission'])->name('permissions.update');
    Route::delete('permissions/{id}', [AdminController::class, 'deletePermission'])->name('permissions.destroy');

    // Existing seller-page functionalities
    Route::resource('products', ProductController::class);
    Route::resource('orders', OrderController::class);
    Route::resource('inventory', InventoryController::class);
    Route::resource('shipping', ShippingController::class);
    Route::resource('payments', PaymentController::class);
});



// Seller routes
Route::middleware(['auth', 'role:seller-page'])->prefix('seller-page')->name('seller-page.')->group(function() {
    Route::get('/', [SellerController::class, 'index'])->name('dashboard');

    // Product Routes
    Route::get('products', [SellerController::class, 'products'])->name('products.index');
    Route::get('products/create', [SellerController::class, 'createProduct'])->name('products.create');
    Route::post('products', [SellerController::class, 'storeProduct'])->name('products.store');
    Route::get('products/{id}/edit', [SellerController::class, 'editProduct'])->name('products.edit');
    Route::put('products/{id}', [SellerController::class, 'updateProduct'])->name('products.update');
    Route::delete('products/{id}', [SellerController::class, 'deleteProduct'])->name('products.destroy');

    // Order Routes
    Route::get('orders', [SellerController::class, 'orders'])->name('orders.index');
    Route::get('orders/{id}', [SellerController::class, 'showOrder'])->name('orders.show');

    // Inventory Routes
    Route::get('inventory', [SellerController::class, 'inventory'])->name('inventory.index');
    Route::get('inventory/create', [SellerController::class, 'addToInventory'])->name('inventory.add');
    Route::post('inventory', [SellerController::class, 'storeInventory'])->name('inventory.store');
    Route::get('inventory/{id}/edit', [SellerController::class, 'editInventory'])->name('inventory.edit');
    Route::put('inventory/{id}', [SellerController::class, 'updateInventory'])->name('inventory.update');
    Route::delete('inventory/{id}', [SellerController::class, 'deleteInventory'])->name('inventory.destroy');

    // Shipping Routes
    Route::get('shippings', [SellerController::class, 'shippings'])->name('shippings.index');
    Route::get('shippings/create', [SellerController::class, 'createShipping'])->name('shippings.create');
    Route::post('shippings', [SellerController::class, 'storeShipping'])->name('shippings.store');
    Route::get('shippings/{id}/edit', [SellerController::class, 'editShipping'])->name('shippings.edit');
    Route::put('shippings/{id}', [SellerController::class, 'updateShipping'])->name('shippings.update');
    Route::delete('shippings/{id}', [SellerController::class, 'deleteShipping'])->name('shippings.destroy');

    // Payment Routes
    Route::get('payments', [SellerController::class, 'payments'])->name('payments.index');
    Route::get('payments/create', [SellerController::class, 'createPayment'])->name('payments.create');
    Route::post('payments', [SellerController::class, 'storePayment'])->name('payments.store');
    Route::get('payments/{id}/edit', [SellerController::class, 'editPayment'])->name('payments.edit');
    Route::put('payments/{id}', [SellerController::class, 'updatePayment'])->name('payments.update');
    Route::delete('payments/{id}', [SellerController::class, 'deletePayment'])->name('payments.destroy');
});


// Password Change Routes
Route::middleware('auth')->group(function () {
    Route::get('password/change', [ChangePasswordController::class, 'showChangePasswordForm'])->name('password.change');
    Route::post('password/change', [ChangePasswordController::class, 'changePassword'])->name('password.update');
});

// Cart and checkout routes
Route::middleware(['auth'])->group(function () {
    Route::get('cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('cart', [CartController::class, 'store'])->name('cart.store');
    Route::put('cart/{product}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('cart/{product}', [CartController::class, 'destroy'])->name('cart.destroy');

    Route::get('checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('checkout', [CheckoutController::class, 'store'])->name('checkout.store');
});