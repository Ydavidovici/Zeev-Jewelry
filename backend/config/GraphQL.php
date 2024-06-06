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
    InventoryMovementInputType,
    InventoryInputType,
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

    'middleware' => [],

    'default_schema' => 'default',

    'schemas' => [
        'default' => [
            'query' => [
                'categories' => CategoryQuery::class,
                'customers' => CustomerQuery::class,
                'inventoryMovements' => InventoryMovementQuery::class,
                'inventories' => InventoryQuery::class,
                'orderDetails' => OrderDetailQuery::class,
                'orders' => OrderQuery::class,
                'payments' => PaymentQuery::class,
                'products' => ProductQuery::class,
                'reviews' => ReviewQuery::class,
                'roles' => RoleQuery::class,
                'shippings' => ShippingQuery::class,
                'users' => UserQuery::class,
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
                'Category' => CategoryType::class,
                'Customer' => CustomerType::class,
                'InventoryMovement' => InventoryMovementType::class,
                'Inventory' => InventoryType::class,
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
                'InventoryMovementInput' => InventoryMovementInputType::class,
                'InventoryInput' => InventoryInputType::class,
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
