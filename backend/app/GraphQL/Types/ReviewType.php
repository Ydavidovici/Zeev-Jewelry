<?php

namespace App\GraphQL\Types;

use App\Models\Review;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;
use Rebing\GraphQL\Support\Facades\GraphQL;

class ReviewType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Review',
        'description' => 'A review',
        'model' => Review::class,
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::id()),
                'description' => 'The ID of the review',
            ],
            'product' => [
                'type' => GraphQL::type('Product'),
                'description' => 'The product being reviewed',
            ],
            'customer' => [
                'type' => GraphQL::type('Customer'),
                'description' => 'The customer who wrote the review',
            ],
            'review_text' => [
                'type' => Type::string(),
                'description' => 'The text of the review',
            ],
            'rating' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'The rating given by the customer',
            ],
            'review_date' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The date the review was written',
            ],
        ];
    }
}
