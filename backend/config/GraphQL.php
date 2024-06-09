<?php

use App\GraphQL\Queries\{CategoryQuery, CustomerQuery, InventoryQuery, OrderQuery, PaymentQuery, ProductQuery, ReviewQuery, RoleQuery, ShippingQuery, UserQuery};
use App\GraphQL\Mutations\{CreateCategoryMutation, CreateCustomerMutation, CreateInventoryMutation, CreateOrderMutation, CreatePaymentMutation, CreateProductMutation, CreateReviewMutation, CreateRoleMutation, CreateShippingMutation, CreateUserMutation};
use App\GraphQL\Types\{CategoryType, CustomerType, InventoryType, InventoryMovementType, OrderDetailType, OrderType, PaymentType, ProductType, ReviewType, RoleType, ShippingType, UserType};
use App\GraphQL\Inputs\{CategoryInputType, CustomerInputType, InventoryInputType, InventoryMovementInputType, OrderDetailInputType, OrderInputType, PaymentInputType, ProductInputType, ReviewInputType, RoleInputType, ShippingInputType, UserInputType};

return [
    'prefix' => 'graphql',
    'routes' => '{graphql_schema?}',
    'controllers' => \Rebing\GraphQL\GraphQLController::class . '@query',
    'middleware' => [],
    'default_schema' => 'default',
    'schemas' => [
        'default' => [
            'query' => [
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
