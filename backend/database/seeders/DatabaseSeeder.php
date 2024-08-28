<?php

// database/seeders/DatabaseSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            RolesTableSeeder::class,
            UsersTableSeeder::class,
            CategoriesTableSeeder::class,
            ProductsTableSeeder::class,
            OrdersTableSeeder::class,
            OrderDetailsTableSeeder::class,
            PaymentsTableSeeder::class,
            ShippingTableSeeder::class,
            ReviewsTableSeeder::class,
            InventoryTableSeeder::class,
            RolesAndPermissionsSeeder::class
        ]);
    }
}
