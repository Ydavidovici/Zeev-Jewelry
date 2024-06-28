<?php

namespace App\GraphQL\Inputs;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\InputType as GraphQLInputType;

class ShippingInputType extends GraphQLInputType
{
    protected $attributes = [
        'name' => 'ShippingInput',
        'description' => 'An input type for shipping',
    ];

    public function fields(): array
    {
        return [
            'order_id' => [
                'type' => Type::nonNull(Type::id()),
                'description' => 'The ID of the order associated with the shipping',
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
