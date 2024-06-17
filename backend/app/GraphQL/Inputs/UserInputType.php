<?php

namespace App\GraphQL\Inputs;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\InputType as GraphQLInputType;

class UserInputType extends GraphQLInputType
{
    protected $attributes = [
        'name' => 'UserInput',
        'description' => 'An input type for users',
    ];

    public function fields(): array
    {
        return [
            'username' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The username of the user',
            ],
            'email' => [
                'type' => Type::string(),
                'description' => 'The email of the user',
            ],
            'role_id' => [
                'type' => Type::nonNull(Type::id()),
                'description' => 'The ID of the role of the user',
            ],
        ];
    }
}
