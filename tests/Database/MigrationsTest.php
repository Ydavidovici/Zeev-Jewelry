<?php

namespace Tests\Unit\Migrations;

use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class MigrationTest extends TestCase
{
    /** @test */
    public function users_table_has_expected_columns()
    {
        $this->assertTrue(
            Schema::hasColumns('users', [
                'id', 'username', 'email', 'password', 'role_id'
            ])
        );
    }

    /** @test */
    public function products_table_has_expected_columns()
    {
        $this->assertTrue(
            Schema::hasColumns('products', [
                'id', 'category_id', 'name', 'description', 'price', 'stock_quantity', 'is_featured'
            ])
        );
    }

    /** @test */
    public function orders_table_has_expected_columns()
    {
        $this->assertTrue(
            Schema::hasColumns('orders', [
                'id', 'customer_id', 'order_date', 'total_amount', 'is_guest', 'status'
            ])
        );
    }
}
