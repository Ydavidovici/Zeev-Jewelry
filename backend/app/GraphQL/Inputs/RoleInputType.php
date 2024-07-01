<?php

namespace App\GraphQL\Inputs;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\InputType as GraphQLInputType;

class RoleInputType extends GraphQLInputType
{
    protected $attributes = [
        'name' => 'RoleInput',
        'description' => 'An input type for roles',
    ];

    public function fields(): array
    {
        return [
            'role_name' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The name of the role',
            ],
        ];
    }
}
