<?php

namespace App\GraphQL\Types;

use App\Models\OrderDetail;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;
use Rebing\GraphQL\Support\Facades\GraphQL;

class OrderDetailType extends GraphQLType
{
    protected $attributes = [
        'name' => 'OrderDetail',
        'description' => 'Details of an order',
        'model' => OrderDetail::class,
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::id()),
                'description' => 'The ID of the order detail',
            ],
            'order' => [
                'type' => GraphQL::type('Order'),
                'description' => 'The order associated with the detail',
            ],
            'product' => [
                'type' => GraphQL::type('Product'),
                'description' => 'The product in the order detail',
            ],
            'quantity' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'The quantity of the product',
            ],
            'price' => [
                'type' => Type::nonNull(Type::float()),
                'description' => 'The price of the product',
            ],
        ];
    }
}
