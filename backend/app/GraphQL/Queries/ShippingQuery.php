<?php

namespace App\GraphQL\Queries;

use App\Models\Shipping;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;

class ShippingQuery extends Query
{
    protected $attributes = [
        'name' => 'shipping',
    ];

    public function type(): Type
    {
        return GraphQL::type('Shipping');
    }

    public function args(): array
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::id()),
                'description' => 'The id of the shipping record',
            ],
        ];
    }

    public function resolve($root, $args)
    {
        return Shipping::find($args['id']);
    }
}
