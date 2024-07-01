<?php

namespace App\GraphQL\Mutations;
use Rebing\GraphQL\Support\Mutation;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use App\Models\Category;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class DeleteCategoryMutation extends Mutation
{
    protected $attributes = [
        'name' => 'deleteCategory',
        'description' => 'Delete a category',
    ];

    public function type(): Type
    {
        return Type::boolean();
    }

    public function args(): array
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::id()),
                'description' => 'The ID of the category to delete',
            ],
        ];
    }

    public function resolve($root, $args)
    {
        // Authorization
        $user = auth()->user();
        if (Gate::denies('delete-category', $user)) {
            throw new \Exception('Unauthorized');
        }

        // Find and delete the category
        $category = Category::find($args['id']);
        if (!$category) {
            throw new \Exception('Category not found');
        }

        $category->delete();

        // Logging
        Log::info('Category deleted', ['user_id' => $user->id, 'category_id' => $args['id']]);

        return true;
    }
}
