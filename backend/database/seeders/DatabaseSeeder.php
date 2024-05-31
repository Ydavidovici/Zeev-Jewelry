<?php

// database/seeders/DatabaseSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            RolesTableSeeder::class,
            UsersTableSeeder::class,
            CategoriesTableSeeder::class,
            ProductsTableSeeder::class,
            CustomersTableSeeder::class,
            OrdersTableSeeder::class,
            OrderDetailsTableSeeder::class,
            PaymentsTableSeeder::class,
            ShippingTableSeeder::class,
            ReviewsTableSeeder::class,
            InventoryTableSeeder::class,
            InventoryMovementsTableSeeder::class,
        ]);
    }
}
