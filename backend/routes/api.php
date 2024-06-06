<?php

use Rebing\GraphQL\Support\GraphQLController;

Route::group(['prefix' => 'graphql', 'middleware' => ['api']], function () {
    Route::post('/', [GraphQLController::class, 'query']);
});
