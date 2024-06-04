<?php

namespace App\GraphQL\Inputs;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\InputType;

class PaymentInputType extends InputType
{
    protected $attributes = [
        'name' => 'PaymentInput',
        'description' => 'Input type for payment',
    ];

    public function fields(): array
    {
        return [
            'order_id' => [
                'type' => Type::nonNull(Type::id()),
                'description' => 'The order ID associated with the payment',
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
