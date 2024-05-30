<?php

// database/seeders/CategoriesTableSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('categories')->insert([
            ['category_name' => 'Necklaces'],
            ['category_name' => 'Rings'],
            ['category_name' => 'Earrings'],
        ]);
    }
}
