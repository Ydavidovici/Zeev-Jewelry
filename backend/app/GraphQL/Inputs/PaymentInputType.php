<?php

namespace App\GraphQL\Inputs;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\InputType as GraphQLInputType;

class PaymentInputType extends GraphQLInputType
{
    protected $attributes = [
        'name' => 'PaymentInput',
        'description' => 'An input type for payments',
    ];

    public function fields(): array
    {
        return [
            'order_id' => [
                'type' => Type::nonNull(Type::id()),
                'description' => 'The ID of the order associated with the payment',
            ],
            'payment_type' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The type of the payment',
            ],
            'payment_status' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The status of the payment',
            ],
        ];
    }
}
