<?php

use App\Http\Controllers\{Admin\AdminController,
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
    TestController,
    WebhookController};
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

// Password Change Routes
Route::middleware('auth')->group(function () {
    Route::get('password/change', [ChangePasswordController::class, 'showChangePasswordForm'])->name('password.change');
    Route::post('password/change', [ChangePasswordController::class, 'changePassword'])->name('password.update');
});

// Profile route (if required)
Route::middleware('auth')->get('profile', function () {
    return view('profile'); // or specify a controller action if you have one
})->name('profile');

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', function () {
    return view('pages.about');
})->name('about');
Route::get('/test', function () {
    return 'Test route is working';
});
Route::get('upload', function () {
    return view('upload');
});
Route::post('upload', [FileUploadController::class, 'store'])->name('file.upload');

// Pages
Route::get('products', [ProductController::class, 'index'])->name('products.index');

// Cart routes (accessible to guests and authenticated users)
Route::get('cart', [CartController::class, 'index'])->name('cart.index');
Route::post('cart', [CartController::class, 'store'])->name('cart.store');
Route::put('cart/{product}', [CartController::class, 'update'])->name('cart.update');
Route::delete('cart/{product}', [CartController::class, 'destroy'])->name('cart.destroy');
Route::resource('cart_items', CartItemController::class);

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
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
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

    // Settings routes
    Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('settings', [SettingsController::class, 'update'])->name('settings.update');
});


// Seller routes
Route::middleware(['auth'])->prefix('seller')->name('seller.')->group(function () {
    Route::get('/', [SellerController::class, 'index'])->name('dashboard');

    Route::get('products', [ProductController::class, 'index'])->name('products.index');
    Route::get('products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('products', [ProductController::class, 'store'])->name('products.store');
    Route::get('products/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('products/{id}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');

    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{id}', [OrderController::class, 'show'])->name('orders.show');

    Route::get('inventory', [InventoryController::class, 'index'])->name('inventory.index');
    Route::get('inventory/create', [InventoryController::class, 'create'])->name('inventory.create');
    Route::post('inventory', [InventoryController::class, 'store'])->name('inventory.store');
    Route::get('inventory/{id}/edit', [InventoryController::class, 'edit'])->name('inventory.edit');
    Route::put('inventory/{id}', [InventoryController::class, 'update'])->name('inventory.update');
    Route::delete('inventory/{id}', [InventoryController::class, 'destroy'])->name('inventory.destroy');

    Route::get('shipping', [ShippingController::class, 'index'])->name('shipping.index');
    Route::get('shipping/create', [ShippingController::class, 'create'])->name('shipping.create');
    Route::post('shipping', [ShippingController::class, 'store'])->name('shipping.store');
    Route::get('shipping/{id}/edit', [ShippingController::class, 'edit'])->name('shipping.edit');
    Route::put('shipping/{id}', [ShippingController::class, 'update'])->name('shipping.update');
    Route::delete('shipping/{id}', [ShippingController::class, 'destroy'])->name('shipping.destroy');

    Route::get('payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('payments/create', [PaymentController::class, 'create'])->name('payments.create');
    Route::post('payments', [PaymentController::class, 'store'])->name('payments.store');
    Route::get('payments/{id}/edit', [PaymentController::class, 'edit'])->name('payments.edit');
    Route::put('payments/{id}', [PaymentController::class, 'update'])->name('payments.update');
    Route::delete('payments/{id}', [PaymentController::class, 'destroy'])->name('payments.destroy');

    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
});

// Customer routes
Route::middleware(['auth'])->prefix('customer-page')->name('customer-page.')->group(function () {
    Route::get('/', [CustomerController::class, 'index'])->name('dashboard');
    // other customer routes
});

// Guest routes
Route::middleware(['auth'])->prefix('guest-page')->name('guest-page.')->group(function () {
    Route::get('/', function () {
        return view('guest.dashboard');
    })->name('dashboard');
    // other guest routes
});

// Stripe Webhook
Route::post('stripe/webhook', [WebhookController::class, 'handle']);

// Checkout routes (accessible to guests and authenticated users)
Route::get('checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');
Route::get('checkout/failure', [CheckoutController::class, 'failure'])->name('checkout.failure');

// Test routes
Route::get('/admin-test', [TestController::class, 'adminAccess']);
