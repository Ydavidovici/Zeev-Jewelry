<?php

// app/GraphQL/Queries/ReviewQuery.php
namespace App\GraphQL\Queries;

use App\Models\Review;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;

class ReviewQuery extends Query
{
    protected $attributes = [
        'name' => 'review',
    ];

    public function type(): Type
    {
        return GraphQL::type('Review');
    }

    public function args(): array
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::id()),
                'description' => 'The id of the review',
            ],
        ];
    }

    public function resolve($root, $args)
    {
        return Review::find($args['id']);
    }
}
