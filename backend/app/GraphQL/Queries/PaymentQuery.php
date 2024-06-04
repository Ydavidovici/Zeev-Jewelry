<?php

namespace App\GraphQL\Queries;

use App\Models\Payment;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;

class PaymentQuery extends Query
{
    protected $attributes = [
        'name' => 'payment',
    ];

    public function type(): Type
    {
        return GraphQL::type('Payment');
    }

    public function args(): array
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::id()),
                'description' => 'The id of the payment',
            ],
        ];
    }

    public function resolve($root, $args)
    {
        return Payment::find($args['id']);
    }
}
