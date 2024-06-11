<?php

use App\GraphQL\Queries\CategoryQuery;
use App\GraphQL\Queries\CustomerQuery;
use App\GraphQL\Queries\InventoryQuery;
use App\GraphQL\Queries\OrderQuery;
use App\GraphQL\Queries\PaymentQuery;
use App\GraphQL\Queries\ProductQuery;
use App\GraphQL\Queries\ReviewQuery;
use App\GraphQL\Queries\RoleQuery;
use App\GraphQL\Queries\ShippingQuery;
use App\GraphQL\Queries\SimpleQuery; // For testing purposes
use App\GraphQL\Queries\UserQuery;

use App\GraphQL\Mutations\CreateCategoryMutation;
use App\GraphQL\Mutations\CreateCustomerMutation;
use App\GraphQL\Mutations\CreateInventoryMutation;
use App\GraphQL\Mutations\CreateOrderMutation;
use App\GraphQL\Mutations\CreatePaymentMutation;
use App\GraphQL\Mutations\CreateProductMutation;
use App\GraphQL\Mutations\CreateReviewMutation;
use App\GraphQL\Mutations\CreateRoleMutation;
use App\GraphQL\Mutations\CreateShippingMutation;
use App\GraphQL\Mutations\CreateUserMutation;

use App\GraphQL\Types\CategoryType;
use App\GraphQL\Types\CustomerType;
use App\GraphQL\Types\InventoryType;
use App\GraphQL\Types\InventoryMovementType;
use App\GraphQL\Types\OrderDetailType;
use App\GraphQL\Types\OrderType;
use App\GraphQL\Types\PaymentType;
use App\GraphQL\Types\ProductType;
use App\GraphQL\Types\ReviewType;
use App\GraphQL\Types\RoleType;
use App\GraphQL\Types\ShippingType;
use App\GraphQL\Types\UserType;

use App\GraphQL\Inputs\CategoryInputType;
use App\GraphQL\Inputs\CustomerInputType;
use App\GraphQL\Inputs\InventoryInputType;
use App\GraphQL\Inputs\InventoryMovementInputType;
use App\GraphQL\Inputs\OrderDetailInputType;
use App\GraphQL\Inputs\OrderInputType;
use App\GraphQL\Inputs\PaymentInputType;
use App\GraphQL\Inputs\ProductInputType;
use App\GraphQL\Inputs\ReviewInputType;
use App\GraphQL\Inputs\RoleInputType;
use App\GraphQL\Inputs\ShippingInputType;
use App\GraphQL\Inputs\UserInputType;

return [
    'prefix' => 'graphql',
    'routes' => '{graphql_schema?}',
    'controllers' => \Rebing\GraphQL\GraphQLController::class . '@query',
    'middleware' => [],
    'default_schema' => 'default',
    'schemas' => [
        'default' => [
            'query' => [
                'simpleQuery' => SimpleQuery::class,
                'category' => CategoryQuery::class,
                'customer' => CustomerQuery::class,
                'inventory' => InventoryQuery::class,
                'order' => OrderQuery::class,
                'payment' => PaymentQuery::class,
                'product' => ProductQuery::class,
                'review' => ReviewQuery::class,
                'role' => RoleQuery::class,
                'shipping' => ShippingQuery::class,
                'user' => UserQuery::class,
            ],
            'mutation' => [
                'createCategory' => CreateCategoryMutation::class,
                'createCustomer' => CreateCustomerMutation::class,
                'createInventory' => CreateInventoryMutation::class,
                'createOrder' => CreateOrderMutation::class,
                'createPayment' => CreatePaymentMutation::class,
                'createProduct' => CreateProductMutation::class,
                'createReview' => CreateReviewMutation::class,
                'createRole' => CreateRoleMutation::class,
                'createShipping' => CreateShippingMutation::class,
                'createUser' => CreateUserMutation::class,
            ],
            'types' => [
                'Category' => CategoryType::class,
                'Customer' => CustomerType::class,
                'Inventory' => InventoryType::class,
                'InventoryMovement' => InventoryMovementType::class,
                'OrderDetail' => OrderDetailType::class,
                'Order' => OrderType::class,
                'Payment' => PaymentType::class,
                'Product' => ProductType::class,
                'Review' => ReviewType::class,
                'Role' => RoleType::class,
                'Shipping' => ShippingType::class,
                'User' => UserType::class,
            ],
            'inputs' => [
                'CategoryInput' => CategoryInputType::class,
                'CustomerInput' => CustomerInputType::class,
                'InventoryInput' => InventoryInputType::class,
                'InventoryMovementInput' => InventoryMovementInputType::class,
                'OrderDetailInput' => OrderDetailInputType::class,
                'OrderInput' => OrderInputType::class,
                'PaymentInput' => PaymentInputType::class,
                'ProductInput' => ProductInputType::class,
                'ReviewInput' => ReviewInputType::class,
                'RoleInput' => RoleInputType::class,
                'ShippingInput' => ShippingInputType::class,
                'UserInput' => UserInputType::class,
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
