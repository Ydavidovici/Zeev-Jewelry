<?php

namespace App\GraphQL\Mutations;
use Rebing\GraphQL\Support\Facades\GraphQL;

use Rebing\GraphQL\Support\Mutation;
use GraphQL\Type\Definition\Type;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

class UpdateRoleMutation extends Mutation
{
    protected $attributes = [
        'name' => 'updateRole',
        'description' => 'Update an existing role'
    ];

    public function type(): Type
    {
        return GraphQL::type('Role');
    }

    public function args(): array
    {
        return [
            'id' => ['type' => Type::nonNull(Type::int())],
            'input' => ['type' => GraphQL::type('RoleInput')],
        ];
    }

    public function resolve($root, $args)
    {
        // Rate limiting
        $user = auth()->user();
        $key = 'update-role:' . $user->id;

        if (RateLimiter::tooManyAttempts($key, 5)) {
            throw new \Exception('Too many attempts. Please try again later.');
        }

        RateLimiter::hit($key, 60);

        // Authorization
        if (Gate::denies('update-role', $user)) {
            throw new \Exception('Unauthorized');
        }

        // Validate and update the role
        $role = Role::findById($args['id']);
        if (!$role) {
            throw new \Exception('Role not found');
        }

        $validator = Validator::make($args['input'], [
            'name' => 'required|string|max:255',
            // Add other validation rules as necessary
        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }

        $role->update($args['input']);

        // Logging
        Log::info('Role updated', ['user_id' => $user->id, 'role_id' => $args['id']]);

        return $role;
    }
}
