<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PermissionsController;
use App\Http\Controllers\Auth\ChangePasswordController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\InventoryMovementController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderDetailController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\Seller\SellerController;
use App\Http\Controllers\ShippingController;
use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;

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
Route::get('/test', function () {
    return 'Test route is working';
});
Route::get('upload', function () {
    return view('upload');
});
Route::post('upload', [FileUploadController::class, 'store'])->name('file.upload');

// Pages
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('products', [ProductController::class, 'index'])->name('products.index');

// Cart routes (accessible to guests and authenticated users)
Route::get('cart', [CartController::class, 'index'])->name('cart.index');
Route::post('cart', [CartController::class, 'store'])->name('cart.store');
Route::put('cart/{product}', [CartController::class, 'update'])->name('cart.update');
Route::delete('cart/{product}', [CartController::class, 'destroy'])->name('cart.destroy');

// Authenticated routes
Route::middleware(['auth'])->group(function () {
    Route::resource('categories', CategoryController::class);
    Route::resource('customers', CustomerController::class);
    Route::resource('inventories', InventoryController::class);
    Route::resource('inventory-movements', InventoryMovementController::class);
    Route::resource('order-details', OrderDetailController::class);
    Route::resource('payments', PaymentController::class);
    Route::resource('products', ProductController::class);
    Route::resource('reviews', ReviewController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('shipping', ShippingController::class);
    Route::resource('users', UserController::class);

    // Orders routes
    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/create', [OrderController::class, 'create'])->name('orders.create');
    Route::post('orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('orders/{order}/edit', [OrderController::class, 'edit'])->name('orders.edit');
    Route::put('orders/{order}', [OrderController::class, 'update'])->name('orders.update');
    Route::delete('orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');
});

// Admin routes
Route::middleware(['auth', 'check.role:admin-page'])->prefix('admin-page')->name('admin-page.')->group(function() {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');

    // User routes
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::get('users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('users', [UserController::class, 'store'])->name('users.store');
    Route::get('users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('users/{id}', [UserController::class, 'destroy'])->name('users.destroy');

    // Role routes
    Route::get('roles', [RoleController::class, 'index'])->name('roles.index');
    Route::get('roles/create', [RoleController::class, 'create'])->name('roles.create');
    Route::post('roles', [RoleController::class, 'store'])->name('roles.store');
    Route::get('roles/{id}/edit', [RoleController::class, 'edit'])->name('roles.edit');
    Route::put('roles/{id}', [RoleController::class, 'update'])->name('roles.update');
    Route::delete('roles/{id}', [RoleController::class, 'destroy'])->name('roles.destroy');

    // Permission routes
    Route::get('permissions', [PermissionsController::class, 'index'])->name('permissions.index');
    Route::get('permissions/create', [PermissionsController::class, 'create'])->name('permissions.create');
    Route::post('permissions', [PermissionsController::class, 'store'])->name('permissions.store');
    Route::get('permissions/{id}/edit', [PermissionsController::class, 'edit'])->name('permissions.edit');
    Route::put('permissions/{id}', [PermissionsController::class, 'update'])->name('permissions.update');
    Route::delete('permissions/{id}', [PermissionsController::class, 'destroy'])->name('permissions.destroy');

    // Existing seller-page functionalities
    Route::resource('products', ProductsController::class);
    Route::resource('orders', OrdersController::class);
    Route::resource('inventory', InventoryController::class);
    Route::resource('shipping', ShippingController::class);
    Route::resource('payments', PaymentsController::class);
});

// Seller routes
Route::middleware(['auth', 'check.role:seller-page'])->prefix('seller-page')->name('seller-page.')->group(function() {
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
    Route::get('shipping', [SellerController::class, 'shipping'])->name('shipping.index');
    Route::get('shipping/create', [SellerController::class, 'createShipping'])->name('shipping.create');
    Route::post('shipping', [SellerController::class, 'storeShipping'])->name('shipping.store');
    Route::get('shipping/{id}/edit', [SellerController::class, 'editShipping'])->name('shipping.edit');
    Route::put('shipping/{id}', [SellerController::class, 'updateShipping'])->name('shipping.update');
    Route::delete('shipping/{id}', [SellerController::class, 'deleteShipping'])->name('shipping.destroy');

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

// Stripe Payment Routes
Route::middleware(['auth'])->group(function () {
    Route::get('payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('payments/create', [PaymentController::class, 'create'])->name('payments.create');
    Route::post('payments', [PaymentController::class, 'store'])->name('payments.store');
    Route::post('payments/confirm', [PaymentController::class, 'confirm'])->name('payments.confirm');
    Route::get('payments/{payment}', [PaymentController::class, 'show'])->name('payments.show');
    Route::get('payments/{payment}/edit', [PaymentController::class, 'edit'])->name('payments.edit');
    Route::put('payments/{payment}', [PaymentController::class, 'update'])->name('payments.update');
    Route::delete('payments/{payment}', [PaymentController::class, 'destroy'])->name('payments.destroy');
});

// Stripe Webhook
Route::post('stripe/webhook', [WebhookController::class, 'handle']);

// Checkout routes (accessible to guests and authenticated users)
Route::get('checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');
Route::get('checkout/failure', [CheckoutController::class, 'failure'])->name('checkout.failure');
