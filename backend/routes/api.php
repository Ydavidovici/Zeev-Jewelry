<?php

use App\Http\Controllers\{
    Admin\AdminController,
    Admin\PermissionsController,
    Admin\RoleController,
    Admin\UserController,
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
Route::post('login', [LoginController::class, 'login'])->name('api.login');
Route::post('logout', [LoginController::class, 'logout'])->name('api.logout')->middleware('auth:sanctum');
Route::post('register', [RegisterController::class, 'register'])->name('api.register');

// Password Reset Routes
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('api.password.email');
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('api.password.reset');

// Password Change Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('password/change', [ChangePasswordController::class, 'changePassword'])->name('api.password.update');
});

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('api.home');

// Product routes
Route::get('products', [ProductController::class, 'index'])->name('api.products.index');
Route::get('products/{product}', [ProductController::class, 'show'])->name('api.products.show');

// Cart routes (accessible to guests and authenticated users)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('cart', [CartController::class, 'index'])->name('api.cart.index');
    Route::post('cart', [CartController::class, 'store'])->name('api.cart.store');
    Route::put('cart/{product}', [CartController::class, 'update'])->name('api.cart.update');
    Route::delete('cart/{product}', [CartController::class, 'destroy'])->name('api.cart.destroy');
    Route::apiResource('cart_items', CartItemController::class)->names('api.cart_items');
});

// Authenticated routes
Route::middleware(['auth:sanctum'])->group(function () {
    Route::apiResource('categories', CategoryController::class)->names('api.categories');
    Route::apiResource('customers', CustomerController::class)->names('api.customers');
    Route::apiResource('inventories', InventoryController::class)->names('api.inventories');
    Route::apiResource('inventory-movements', InventoryMovementController::class)->names('api.inventory_movements');
    Route::apiResource('order-details', OrderDetailController::class)->names('api.order_details');
    Route::apiResource('payments', PaymentController::class)->names('api.payments');
    Route::apiResource('products', ProductController::class)->except(['index', 'show'])->names('api.products');
    Route::apiResource('reviews', ReviewController::class)->names('api.reviews');
    Route::apiResource('roles', RoleController::class)->names('api.roles');
    Route::apiResource('shipping', ShippingController::class)->names('api.shipping');
    Route::apiResource('users', UserController::class)->names('api.users');

    // Orders routes
    Route::get('orders', [OrderController::class, 'index'])->name('api.orders.index');
    Route::post('orders', [OrderController::class, 'store'])->name('api.orders.store');
    Route::get('orders/{order}', [OrderController::class, 'show'])->name('api.orders.show');
    Route::put('orders/{order}', [OrderController::class, 'update'])->name('api.orders.update');
    Route::delete('orders/{order}', [OrderController::class, 'destroy'])->name('api.orders.destroy');
});

// Admin routes
Route::middleware(['auth:sanctum'])->prefix('admin')->name('api.admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');

    Route::apiResource('users', UserController::class)->names('api.admin.users');
    Route::apiResource('roles', RoleController::class)->names('api.admin.roles');
    Route::apiResource('permissions', PermissionsController::class)->names('api.admin.permissions');
    Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('settings', [SettingsController::class, 'update'])->name('settings.update');
});

// Seller routes
Route::middleware(['auth:sanctum'])->prefix('seller')->name('api.seller.')->group(function () {
    Route::get('/', [SellerController::class, 'index'])->name('dashboard');

    Route::apiResource('products', ProductController::class)->names('api.seller.products');
    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{id}', [OrderController::class, 'show'])->name('orders.show');
    Route::apiResource('inventory', InventoryController::class)->names('api.seller.inventory');
    Route::apiResource('shipping', ShippingController::class)->names('api.seller.shipping');
    Route::apiResource('payments', PaymentController::class)->names('api.seller.payments');
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
});

// Customer routes
Route::middleware(['auth:sanctum'])->prefix('customer-page')->name('api.customer.')->group(function () {
    Route::get('/', [CustomerController::class, 'index'])->name('dashboard');
    // other customer routes
});

// Stripe Webhook
Route::post('stripe/webhook', [WebhookController::class, 'handle'])->name('api.stripe.webhook');

// Checkout routes (accessible to guests and authenticated users)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('checkout', [CheckoutController::class, 'index'])->name('api.checkout.index');
    Route::post('checkout', [CheckoutController::class, 'store'])->name('api.checkout.store');
    Route::get('checkout/success', [CheckoutController::class, 'success'])->name('api.checkout.success');
    Route::get('checkout/failure', [CheckoutController::class, 'failure'])->name('api.checkout.failure');
});

// Test routes (to be removed or protected)
Route::get('/admin-test', [TestController::class, 'adminAccess']);
