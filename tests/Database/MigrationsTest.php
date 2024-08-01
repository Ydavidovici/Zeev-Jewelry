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
        $this->assertTrue(
            Schema::hasColumns('users', [
                'id', 'username', 'email', 'password', 'role_id'
            ])
        );
    }

    #[Test]
    public function products_table_has_expected_columns()
    {
        $this->assertTrue(
            Schema::hasColumns('products', [
                'id', 'category_id', 'name', 'description', 'price', 'stock_quantity', 'is_featured'
            ])
        );
    }

    #[Test]
    public function orders_table_has_expected_columns()
    {
        $this->assertTrue(
            Schema::hasColumns('orders', [
                'id', 'customer_id', 'order_date', 'total_amount', 'is_guest', 'status'
            ])
        );
    }
}
