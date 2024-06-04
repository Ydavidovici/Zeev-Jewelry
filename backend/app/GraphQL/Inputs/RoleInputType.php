<?php

namespace App\GraphQL\Inputs;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\InputType;

class RoleInputType extends InputType
{
    protected $attributes = [
        'name' => 'RoleInput',
        'description' => 'Input type for role',
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
