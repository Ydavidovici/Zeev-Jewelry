<?php

namespace App\GraphQL\Mutations;
use Rebing\GraphQL\Support\Facades\GraphQL;

use Rebing\GraphQL\Support\Mutation;
use GraphQL\Type\Definition\Type;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

class DeleteRoleMutation extends Mutation
{
    protected $attributes = [
        'name' => 'deleteRole',
        'description' => 'Delete an existing role'
    ];

    public function type(): Type
    {
        return Type::nonNull(Type::boolean());
    }

    public function args(): array
    {
        return [
            'id' => ['type' => Type::nonNull(Type::int())],
        ];
    }

    public function resolve($root, $args)
    {
        // Rate limiting
        $user = auth()->user();
        $key = 'delete-role:' . $user->id;

        if (RateLimiter::tooManyAttempts($key, 5)) {
            throw new \Exception('Too many attempts. Please try again later.');
        }

        RateLimiter::hit($key, 60);

        // Authorization
        if (Gate::denies('delete-role', $user)) {
            throw new \Exception('Unauthorized');
        }

        // Delete the role
        $role = Role::findById($args['id']);
        if (!$role) {
            throw new \Exception('Role not found');
        }

        $role->delete();

        // Logging
        Log::info('Role deleted', ['user_id' => $user->id, 'role_id' => $args['id']]);

        return true;
    }
}
