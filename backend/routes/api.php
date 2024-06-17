<?php

use Illuminate\Support\Facades\Route;
use Rebing\GraphQL\GraphQLController;

// Define the route for the GraphQL endpoint
Route::post('/graphql', [GraphQLController::class, 'query'])->middleware('api');
