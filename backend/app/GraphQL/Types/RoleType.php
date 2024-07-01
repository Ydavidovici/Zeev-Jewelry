<?php

namespace App\GraphQL\Types;

use App\Models\Role;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class RoleType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Role',
        'description' => 'A type for roles',
        'model' => Role::class,
    ];

    public function fields(): array
    {
        return [
            'role_id' => [
                'type' => Type::nonNull(Type::id()),
                'description' => 'The ID of the role',
            ],
            'role_name' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The name of the role',
            ],
            'created_at' => [
                'type' => Type::string(),
                'description' => 'The creation time of the role',
            ],
            'updated_at' => [
                'type' => Type::string(),
                'description' => 'The last update time of the role',
            ],
        ];
    }
}
