<?php

namespace App\GraphQL\Inputs;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\InputType;

class UserInputType extends InputType
{
    protected $attributes = [
        'name' => 'UserInput',
        'description' => 'Input type for user',
    ];

    public function fields(): array
    {
        return [
            'username' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The username of the user',
            ],
            'email' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The email of the user',
            ],
            'password' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The password of the user',
            ],
            'role_id' => [
                'type' => Type::nonNull(Type::id()),
                'description' => 'The role ID of the user',
            ],
        ];
    }
}
