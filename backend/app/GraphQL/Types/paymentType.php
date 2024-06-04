<?php

namespace App\GraphQL\Types;

use App\Models\Payment;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;
use Rebing\GraphQL\Support\Facades\GraphQL;

class PaymentType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Payment',
        'description' => 'A payment',
        'model' => Payment::class,
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::id()),
                'description' => 'The ID of the payment',
            ],
            'order' => [
                'type' => GraphQL::type('Order'),
                'description' => 'The order associated with the payment',
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
