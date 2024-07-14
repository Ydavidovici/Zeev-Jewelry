<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileUploadController;

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
