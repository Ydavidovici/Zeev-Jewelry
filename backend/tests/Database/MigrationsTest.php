<?php

namespace Tests\Database;

use Illuminate\Support\Facades\Schema;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class MigrationsTest extends TestCase
{
    #[Test]
    public function users_table_has_expected_columns()
    {
        $this->assertTrue(Schema::hasTable('users'));

        $expectedColumns = [
            'id',
            'username',
            'email',
            'password',
            'role',
            'remember_token',
            'created_at',
            'updated_at'
        ];

        foreach ($expectedColumns as $column) {
            $this->assertTrue(Schema::hasColumn('users', $column), "Missing column: {$column}");
        }
    }

    #[Test]
    public function products_table_has_expected_columns()
    {
        $this->assertTrue(Schema::hasTable('products'));

        $this->assertTrue(
            Schema::hasColumns('products', [
                'id',
                'category_id',
                'seller_id',
                'product_name',
                'description',
                'price',
                'image_url',
                'created_at',
                'updated_at'
            ])
        );
    }

    #[Test]
    public function orders_table_has_expected_columns()
    {
        $this->assertTrue(Schema::hasTable('orders'));

        $this->assertTrue(
            Schema::hasColumns('orders', [
                'id',
                'customer_id',
                'seller_id',
                'order_date',
                'total_amount',
                'is_guest',
                'status',
                'payment_intent_id',
                'created_at',
                'updated_at'
            ])
        );
    }
}
