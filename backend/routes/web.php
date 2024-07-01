<?php

use Illuminate\Support\Facades\Route;

// Define a route for the welcome view
Route::get('/', function () {
    return view('welcome');
});

// Define a route for the GraphiQL interface
Route::get('/graphiql', function () {
    return view('graphiql');
});

// Define a test route to verify routing works
Route::get('/test', function () {
    return 'Test route is working';
});
