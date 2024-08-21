<?php

use App\Http\Controllers\{
    Admin\AdminController,
    Admin\PermissionsController,
    Admin\RoleController,
    Admin\UserController,
    Admin\SettingsController,
    Auth\ChangePasswordController,
    Auth\ForgotPasswordController,
    Auth\LoginController,
    Auth\RegisterController,
    Auth\ResetPasswordController,
    CartController,
    CartItemController,
    CategoryController,
    CheckoutController,
    Customer\CustomerController,
    FileUploadController,
    HomeController,
    InventoryController,
    InventoryMovementController,
    OrderController,
    OrderDetailController,
    PaymentController,
    ProductController,
    ReviewController,
    Seller\ReportController,
    Seller\SellerController,
    ShippingController,
    WebhookController
};
use Illuminate\Support\Facades\Route;

// Authentication Routes
Route::post('login', [LoginController::class, 'login'])->name('login');
Route::post('logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth:sanctum');
Route::post('register', [RegisterController::class, 'register'])->name('register');

// Password Reset Routes
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.reset');

// Password Change Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('password/change', [ChangePasswordController::class, 'changePassword'])->name('password.update');
});

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');

// Product routes
Route::get('products', [ProductController::class, 'index'])->name('products.index');
Route::get('products/{product}', [ProductController::class, 'show'])->name('products.show');

// Cart routes (accessible to guests and authenticated users)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('cart', [CartController::class, 'store'])->name('cart.store');
    Route::put('cart/{product}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('cart/{product}', [CartController::class, 'destroy'])->name('cart.destroy');
    Route::apiResource('cart_items', CartItemController::class)->names('cart_items');
});

// Authenticated routes
Route::middleware(['auth:sanctum'])->group(function () {
    Route::apiResource('categories', CategoryController::class)->names('categories');
    Route::apiResource('customers', CustomerController::class)->names('customers');
    Route::apiResource('inventories', InventoryController::class)->names('inventories');
    Route::apiResource('inventory-movements', InventoryMovementController::class)->names('inventory_movements');
    Route::apiResource('order-details', OrderDetailController::class)->names('order_details');
    Route::apiResource('payments', PaymentController::class)->names('payments');
    Route::apiResource('products', ProductController::class)->except(['index', 'show'])->names('products');
    Route::apiResource('reviews', ReviewController::class)->names('reviews');
    Route::apiResource('roles', RoleController::class)->names('roles');
    Route::apiResource('shipping', ShippingController::class)->names('shipping');
    Route::apiResource('users', UserController::class)->names('users');

    // Orders routes
    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    Route::post('orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::put('orders/{order}', [OrderController::class, 'update'])->name('orders.update');
    Route::delete('orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');
});

// Admin routes
Route::middleware(['auth:sanctum'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');

    Route::apiResource('users', UserController::class)->names('admin.users');
    Route::apiResource('roles', RoleController::class)->names('admin.roles');
    Route::apiResource('permissions', PermissionsController::class)->names('admin.permissions');
    Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('settings', [SettingsController::class, 'update'])->name('settings.update');
});

// Seller routes
Route::middleware(['auth:sanctum'])->prefix('seller')->name('seller.')->group(function () {
    Route::get('/', [SellerController::class, 'index'])->name('dashboard');

    Route::apiResource('products', ProductController::class)->names('seller.products');
    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{id}', [OrderController::class, 'show'])->name('orders.show');
    Route::apiResource('inventory', InventoryController::class)->names('seller.inventory');
    Route::apiResource('shipping', ShippingController::class)->names('seller.shipping');
    Route::apiResource('payments', PaymentController::class)->names('seller.payments');
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
});

// Customer routes
Route::middleware(['auth:sanctum'])->prefix('customer-page')->name('customer.')->group(function () {
    Route::get('/', [CustomerController::class, 'index'])->name('dashboard');
    // other customer routes
});

// Stripe Webhook
Route::post('stripe/webhook', [WebhookController::class, 'handle'])->name('stripe.webhook');

// Checkout routes (accessible to guests and authenticated users)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');
    Route::get('checkout/failure', [CheckoutController::class, 'failure'])->name('checkout.failure');
});

// Test routes (to be removed or protected)

// For testing in a development environment
Route::get('/admin-test', function () {
    return 'This is a test route!';
});
