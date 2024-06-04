<?php

namespace App\GraphQL\Queries;

use App\Models\Role;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;

class RoleQuery extends Query
{
    protected $attributes = [
        'name' => 'role',
    ];

    public function type(): Type
    {
        return GraphQL::type('Role');
    }

    public function args(): array
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::id()),
                'description' => 'The id of the role',
            ],
        ];
    }

    public function resolve($root, $args)
    {
        return Role::find($args['id']);
    }
}
