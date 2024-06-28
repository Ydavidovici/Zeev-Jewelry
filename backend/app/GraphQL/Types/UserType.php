<?php

namespace App\GraphQL\Types;

use App\Models\User;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;

class UserType extends GraphQLType
{
    protected $attributes = [
        'name' => 'User',
        'description' => 'A type for users',
        'model' => User::class,
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::id()),
                'description' => 'The ID of the user',
            ],
            'username' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The username of the user',
            ],
            'email' => [
                'type' => Type::string(),
                'description' => 'The email of the user',
            ],
            'role' => [
                'type' => GraphQL::type('Role'),
                'description' => 'The role of the user',
            ],
            'created_at' => [
                'type' => Type::string(),
                'description' => 'The creation time of the user',
            ],
            'updated_at' => [
                'type' => Type::string(),
                'description' => 'The last update time of the user',
            ],
        ];
    }
}
