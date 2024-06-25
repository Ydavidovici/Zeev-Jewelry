<?php

namespace App\GraphQL\Types;

use App\Models\Order;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;
use Rebing\GraphQL\Support\Facades\GraphQL;

class OrderType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Order',
        'description' => 'An order',
        'model' => Order::class,
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::id()),
                'description' => 'The ID of the order',
            ],
            'customer' => [
                'type' => GraphQL::type('Customer'),
                'description' => 'The customer who placed the order',
            ],
            'order_date' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The date the order was placed',
            ],
            'total_amount' => [
                'type' => Type::nonNull(Type::float()),
                'description' => 'The total amount of the order',
            ],
            'is_guest' => [
                'type' => Type::boolean(),
                'description' => 'Whether the order was placed by a guest',
            ],
            'status' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The status of the order',
            ],
        ];
    }
}
