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
    UpdateCategoryMutation,
    DeleteCategoryMutation,
    CreateCustomerMutation,
    UpdateCustomerMutation,
    DeleteCustomerMutation,
    CreateInventoryMovementMutation,
    UpdateInventoryMovementMutation,
    DeleteInventoryMovementMutation,
    CreateInventoryMutation,
    UpdateInventoryMutation,
    DeleteInventoryMutation,
    CreateOrderDetailMutation,
    UpdateOrderDetailMutation,
    DeleteOrderDetailMutation,
    CreateOrderMutation,
    UpdateOrderMutation,
    DeleteOrderMutation,
    CreatePaymentMutation,
    UpdatePaymentMutation,
    DeletePaymentMutation,
    CreateProductMutation,
    UpdateProductMutation,
    DeleteProductMutation,
    CreateReviewMutation,
    UpdateReviewMutation,
    DeleteReviewMutation,
    CreateRoleMutation,
    UpdateRoleMutation,
    DeleteRoleMutation,
    CreateShippingMutation,
    UpdateShippingMutation,
    DeleteShippingMutation,
    CreateUserMutation,
    UpdateUserMutation,
    DeleteUserMutation
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
    'middleware' => [],
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
                'updateCategory' => UpdateCategoryMutation::class,
                'deleteCategory' => DeleteCategoryMutation::class,
                'createCustomer' => CreateCustomerMutation::class,
                'updateCustomer' => UpdateCustomerMutation::class,
                'deleteCustomer' => DeleteCustomerMutation::class,
                'createInventoryMovement' => CreateInventoryMovementMutation::class,
                'updateInventoryMovement' => UpdateInventoryMovementMutation::class,
                'deleteInventoryMovement' => DeleteInventoryMovementMutation::class,
                'createInventory' => CreateInventoryMutation::class,
                'updateInventory' => UpdateInventoryMutation::class,
                'deleteInventory' => DeleteInventoryMutation::class,
                'createOrderDetail' => CreateOrderDetailMutation::class,
                'updateOrderDetail' => UpdateOrderDetailMutation::class,
                'deleteOrderDetail' => DeleteOrderDetailMutation::class,
                'createOrder' => CreateOrderMutation::class,
                'updateOrder' => UpdateOrderMutation::class,
                'deleteOrder' => DeleteOrderMutation::class,
                'createPayment' => CreatePaymentMutation::class,
                'updatePayment' => UpdatePaymentMutation::class,
                'deletePayment' => DeletePaymentMutation::class,
                'createProduct' => CreateProductMutation::class,
                'updateProduct' => UpdateProductMutation::class,
                'deleteProduct' => DeleteProductMutation::class,
                'createReview' => CreateReviewMutation::class,
                'updateReview' => UpdateReviewMutation::class,
                'deleteReview' => DeleteReviewMutation::class,
                'createRole' => CreateRoleMutation::class,
                'updateRole' => UpdateRoleMutation::class,
                'deleteRole' => DeleteRoleMutation::class,
                'createShipping' => CreateShippingMutation::class,
                'updateShipping' => UpdateShippingMutation::class,
                'deleteShipping' => DeleteShippingMutation::class,
                'createUser' => CreateUserMutation::class,
                'updateUser' => UpdateUserMutation::class,
                'deleteUser' => DeleteUserMutation::class,
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
    'types' => [],
    'error_formatter' => function (\GraphQL\Error\Error $e) {
        $debug = config('app.debug');
        $message = $e->getMessage();
        $previous = $e->getPrevious();

        if ($previous) {
            $message = $previous->getMessage();
        }

        $error = [
            'message' => $message,
            'locations' => $e->getLocations(),
            'path' => $e->getPath(),
        ];

        if ($debug) {
            $error['extensions'] = [
                'trace' => $e->getTrace(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ];
        }

        return $error;
    },
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
