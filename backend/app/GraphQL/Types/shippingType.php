<?php

namespace App\GraphQL\Types;

use App\GraphQL\Types\GraphQL;
use App\Models\Shipping;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class ShippingType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Shipping',
        'description' => 'Shipping details',
        'model' => Shipping::class,
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::id()),
                'description' => 'The ID of the shipping record',
            ],
            'order' => [
                'type' => GraphQL::type('Order'),
                'description' => 'The order associated with the shipping',
            ],
            'shipping_type' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The type of shipping',
            ],
            'shipping_cost' => [
                'type' => Type::nonNull(Type::float()),
                'description' => 'The cost of shipping',
            ],
            'shipping_status' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The status of the shipping',
            ],
            'tracking_number' => [
                'type' => Type::string(),
                'description' => 'The tracking number for the shipment',
            ],
            'shipping_address' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The shipping address',
            ],
        ];
    }
}
