<?php

use App\GraphQL\Queries\ProductQuery;
use App\GraphQL\Queries\UserQuery;
use App\GraphQL\Mutations\CreateProductMutation;
use App\GraphQL\Mutations\CreateUserMutation;
use App\GraphQL\Types\CategoryType;
use App\GraphQL\Types\CustomerType;
use App\GraphQL\Types\InventoryMovementType;
use App\GraphQL\Types\InventoryType;
use App\GraphQL\Types\OrderDetailType;
use App\GraphQL\Types\OrderType;
use App\GraphQL\Types\PaymentType;
use App\GraphQL\Types\ProductType;
use App\GraphQL\Types\ReviewType;
use App\GraphQL\Types\RoleType;
use App\GraphQL\Types\ShippingType;
use App\GraphQL\Types\UserType;

return [

    'prefix' => 'graphql',

    'routes' => '{graphql_schema?}',

    'controllers' => \Rebing\GraphQL\GraphQLController::class . '@query',

    'middleware' => [],

    'default_schema' => 'default',

    'schemas' => [
        'default' => [
            'query' => [
                'users' => UserQuery::class,
                'products' => ProductQuery::class,
            ],
            'mutation' => [
                'createUser' => CreateUserMutation::class,
                'createProduct' => CreateProductMutation::class,
            ],
            'types' => [
                'User' => UserType::class,
                'Product' => ProductType::class,
                'Role' => RoleType::class,
                'Category' => CategoryType::class,
                'Customer' => CustomerType::class,
                'Order' => OrderType::class,
                'OrderDetail' => OrderDetailType::class,
                'Payment' => PaymentType::class,
                'Shipping' => ShippingType::class,
                'Review' => ReviewType::class,
                'Inventory' => InventoryType::class,
                'InventoryMovement' => InventoryMovementType::class,
            ],
        ],
    ],

    'types' => [],

    'error_formatter' => ['\Rebing\GraphQL\GraphQL', 'formatError'],

    'errors_handler' => ['\Rebing\GraphQL\GraphQL', 'handleErrors'],

    'params_key' => 'params',

    'batching' => [
        'enabled' => true,
    ],

    'graphiql' => [
        'enabled' => true,
        'route' => '/graphiql',
        'middleware' => [],
    ],
];
