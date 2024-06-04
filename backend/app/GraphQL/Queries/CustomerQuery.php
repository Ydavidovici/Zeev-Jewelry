<?php

namespace App\GraphQL\Queries;

use App\Models\Customer;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;

class CustomerQuery extends Query
{
    protected $attributes = [
        'name' => 'customer',
    ];

    public function type(): Type
    {
        return GraphQL::type('Customer');
    }

    public function args(): array
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::id()),
                'description' => 'The id of the customer',
            ],
        ];
    }

    public function resolve($root, $args)
    {
        return Customer::find($args['id']);
    }
}
