<?php

namespace App\GraphQL\Mutations;

use App\Models\Role;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Mutation;

class CreateRoleMutation extends Mutation
{
    protected $attributes = [
        'name' => 'createRole',
        'description' => 'Create a new role',
    ];

    public function type(): Type
    {
        return GraphQL::type('Role');
    }

    public function args(): array
    {
        return [
            'input' => [
                'type' => GraphQL::type('RoleInput'),
                'description' => 'Input for role',
            ],
        ];
    }

    public function resolve($root, $args)
    {
        $input = $args['input'];
        $role = new Role();
        $role->role_name = $input['role_name'];
        $role->save();

        return $role;
    }
}
