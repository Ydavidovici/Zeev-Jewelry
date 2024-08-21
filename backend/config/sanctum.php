<?php

use Laravel\Sanctum\Sanctum;

return [

    'stateful' => [],

    'guard' => ['api'],

    'expiration' => null,

    'token_prefix' => '',

    'middleware' => [
        'encrypt_cookies' => Illuminate\Cookie\Middleware\EncryptCookies::class,
    ],

];
