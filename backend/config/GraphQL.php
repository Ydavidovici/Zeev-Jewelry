<?php

use App\GraphQL\Queries\{
    CategoryQuery,
    CustomerQuery,
    InventoryMovementQuery,
    InventoryQuery,
    OrderDetailQuery,
    OrderQuery,
    PaymentQuery,
    ProductQuery,
    ReviewQuery,
    RoleQuery,
    ShippingQuery,
    SimpleQuery,
    UserQuery
};

use App\GraphQL\Mutations\{
    CreateCategoryMutation,
    CreateCustomerMutation,
    CreateInventoryMovementMutation,
    CreateInventoryMutation,
    CreateOrderDetailMutation,
    CreateOrderMutation,
    CreatePaymentMutation,
    CreateProductMutation,
    CreateReviewMutation,
    CreateRoleMutation,
    CreateShippingMutation,
    CreateUserMutation
};

use App\GraphQL\Types\{
    CategoryType,
    CustomerType,
    InventoryMovementType,
    InventoryType,
    OrderDetailType,
    OrderType,
    PaymentType,
    ProductType,
    ReviewType,
    RoleType,
    ShippingType,
    UserType
};

use App\GraphQL\Inputs\{
    CategoryInputType,
    CustomerInputType,
    InventoryInputType,
    InventoryMovementInputType,
    OrderDetailInputType,
    OrderInputType,
    PaymentInputType,
    ProductInputType,
    ReviewInputType,
    RoleInputType,
    ShippingInputType,
    UserInputType
};

return [
    'prefix' => 'graphql',
    'routes' => '{graphql_schema?}',
    'controllers' => \Rebing\GraphQL\GraphQLController::class . '@query',
    'middleware' => [], // Add necessary middleware here
    'default_schema' => 'default',
    'schemas' => [
        'default' => [
            'query' => [
                'categoryQuery' => CategoryQuery::class,
                'customerQuery' => CustomerQuery::class,
                'inventoryMovementQuery' => InventoryMovementQuery::class,
                'inventoryQuery' => InventoryQuery::class,
                'orderDetailQuery' => OrderDetailQuery::class,
                'orderQuery' => OrderQuery::class,
                'paymentQuery' => PaymentQuery::class,
                'productQuery' => ProductQuery::class,
                'reviewQuery' => ReviewQuery::class,
                'roleQuery' => RoleQuery::class,
                'shippingQuery' => ShippingQuery::class,
                'simpleQuery' => SimpleQuery::class,
                'userQuery' => UserQuery::class,
            ],
            'mutation' => [
                'createCategory' => CreateCategoryMutation::class,
                'createCustomer' => CreateCustomerMutation::class,
                'createInventoryMovement' => CreateInventoryMovementMutation::class,
                'createInventory' => CreateInventoryMutation::class,
                'createOrderDetail' => CreateOrderDetailMutation::class,
                'createOrder' => CreateOrderMutation::class,
                'createPayment' => CreatePaymentMutation::class,
                'createProduct' => CreateProductMutation::class,
                'createReview' => CreateReviewMutation::class,
                'createRole' => CreateRoleMutation::class,
                'createShipping' => CreateShippingMutation::class,
                'createUser' => CreateUserMutation::class,
            ],
            'types' => [
                CategoryType::class,
                CustomerType::class,
                InventoryMovementType::class,
                InventoryType::class,
                OrderDetailType::class,
                OrderType::class,
                PaymentType::class,
                ProductType::class,
                ReviewType::class,
                RoleType::class,
                ShippingType::class,
                UserType::class,
                CategoryInputType::class,
                CustomerInputType::class,
                InventoryInputType::class,
                InventoryMovementInputType::class,
                OrderDetailInputType::class,
                OrderInputType::class,
                PaymentInputType::class,
                ProductInputType::class,
                ReviewInputType::class,
                RoleInputType::class,
                ShippingInputType::class,
                UserInputType::class,
            ],
        ],
    ],
    'types' => [], // Define global types here if needed
    'error_formatter' => ['\Rebing\GraphQL\GraphQL', 'formatError'],
    'errors_handler' => ['\Rebing\GraphQL\GraphQL', 'handleErrors'],
    'params_key' => 'params',
    'batching' => [
        'enabled' => true,
    ],
    'graphiql' => [
        'enabled' => true,
        'route' => '/graphiql',
        'middleware' => [], // Add necessary middleware here
    ],
];
