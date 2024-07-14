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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', function () {
    return 'Test route is working';
});

Route::get('upload', function () {
    return view('upload');
});

Route::post('upload', [FileUploadController::class, 'store'])->name('file.upload');


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
