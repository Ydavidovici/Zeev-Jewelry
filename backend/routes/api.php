<?php

use App\Http\Controllers\{
    Admin\AdminController,
    Admin\PermissionsController,
    Admin\RoleController,
    Admin\UserController,
    Admin\SettingsController,
    Admin\AdminReportController,
    Auth\ChangePasswordController,
    Auth\ForgotPasswordController,
    Auth\LoginController,
    Auth\RegisterController,
    Auth\ResetPasswordController,
    CartController,
    CartItemController,
    CategoryController,
    CheckoutController,
    FileUploadController,
    HomeController,
    InventoryController,
    InventoryMovementController,
    OrderController,
    OrderDetailController,
    PaymentController,
    ProductController,
    ReviewController,
    Seller\SellerReportController,
    Seller\SellerController,
    ShippingController,
    WebhookController
};
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// Authentication Routes
Route::post('login', [LoginController::class, 'login'])->name('login');
Route::post('logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth:api');
Route::post('register', [RegisterController::class, 'register'])->name('register');

// Password Reset Routes
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.reset');

// Password Change Routes
Route::middleware('auth:api')->group(function () {
    Route::post('password/change', [ChangePasswordController::class, 'changePassword'])->name('password.update');
});

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/current-settings', [SettingsController::class, 'getCurrentSettings'])->name('current.settings');

// Product routes
Route::get('products', [ProductController::class, 'index'])->name('products.index');
Route::get('products/{product}', [ProductController::class, 'show'])->name('products.show');

// Cart routes (accessible to guests and authenticated users)
Route::middleware('auth:api')->group(function () {
    Route::get('cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('cart', [CartController::class, 'store'])->name('cart.store');
    Route::put('cart/{product}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('cart/{product}', [CartController::class, 'destroy'])->name('cart.destroy');

    // Cart items routes
    Route::apiResource('cart_items', CartItemController::class)->names('cart_items');
});

// Checkout Routes (for viewing cart, placing orders, and confirming success/failure)
Route::middleware('auth:api')->group(function () {
    Route::get('checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');
    Route::get('checkout/failure', [CheckoutController::class, 'failure'])->name('checkout.failure');
});

// Authenticated routes
Route::middleware(['auth:api'])->group(function () {
    Route::apiResource('categories', CategoryController::class)->names('categories');
    Route::apiResource('inventory', InventoryController::class)->names('inventory');
    Route::apiResource('inventory-movements', InventoryMovementController::class)->names('inventory-movements');
    Route::apiResource('order-details', OrderDetailController::class)->names('order_details');
    Route::apiResource('payments', PaymentController::class)->names('payments');
    Route::apiResource('products', ProductController::class)->except(['index', 'show'])->names('products');
    Route::apiResource('reviews', ReviewController::class)->names('reviews');
    Route::apiResource('roles', RoleController::class)->names('roles');
    Route::apiResource('shipping', ShippingController::class)->names('shipping');

    // Orders routes
    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    Route::post('orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::put('orders/{order}', [OrderController::class, 'update'])->name('orders.update');
    Route::delete('orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');
});

// File upload routes
Route::middleware('auth:api')->group(function () {
    Route::post('files', [FileUploadController::class, 'store'])->name('files.store');
    Route::get('files', [FileUploadController::class, 'index'])->name('files.index');
    Route::delete('files/{filename}', [FileUploadController::class, 'destroy'])->name('files.destroy');
});

// Admin routes
Route::prefix('admin')
    ->middleware('auth:api')
    ->name('admin.')
    ->group(function () {

        // Dashboard route (authorization check will be inside the controller)
        Route::get('dashboard', [AdminController::class, 'index'])->name('index');

        // Report route (authorization check will be inside the controller)
        Route::get('report', [AdminReportController::class, 'index'])->name('report.index');

        // Permissions Routes
        Route::get('permissions', [PermissionsController::class, 'index'])->name('permissions.index');
        Route::post('permissions', [PermissionsController::class, 'store'])->name('permissions.store');
        Route::get('permissions/{permission}', [PermissionsController::class, 'show'])->name('permissions.show'); // route-model binding
        Route::put('permissions/{permission}', [PermissionsController::class, 'update'])->name('permissions.update');
        Route::delete('permissions/{permission}', [PermissionsController::class, 'destroy'])->name('permissions.destroy');

        // Roles Routes
        Route::get('roles', [RoleController::class, 'index'])->name('roles.index');
        Route::post('roles', [RoleController::class, 'store'])->name('roles.store');
        Route::get('roles/{role}', [RoleController::class, 'show'])->name('roles.show'); // route-model binding
        Route::put('roles/{role}', [RoleController::class, 'update'])->name('roles.update');
        Route::delete('roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');

        // Users management
        Route::apiResource('users', UserController::class)->names('users');

        // Settings management
        Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
        Route::post('settings', [SettingsController::class, 'store'])->name('settings.store');
        Route::put('settings/{key}', [SettingsController::class, 'update'])->name('settings.update'); // key should be unique
        Route::delete('settings/{key}', [SettingsController::class, 'destroy'])->name('settings.destroy');
    });

// Seller routes
Route::prefix('Seller')
    ->middleware('auth:api')
    ->name('seller.')
    ->group(function () {
        Route::get('dashboard', [SellerController::class, 'index'])->name('dashboard');
        Route::get('reports', [SellerReportController::class, 'index'])->name('reports.index');
    });


// Webhook Routes
Route::post('/webhook/stripe', [WebhookController::class, 'handle'])->name('webhook.handle');

// Test Routes
Route::post('/test-password/email', function (Request $request) {
    \Log::info('Request received:', $request->all());
    $request->validate(['email' => 'required|email']);
    \Log::info('Validation passed');
    return response()->json(['message' => 'Validation passed.'], 200);
})->withoutMiddleware(['auth', 'throttle', 'verified']);

Route::middleware('auth:api')->get('/some-protected-route', function () {
    return response()->json(['message' => 'You are authenticated.']);
});
