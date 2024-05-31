<?php

// database/seeders/CategoriesTableSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Category;

class CategoriesTableSeeder extends Seeder
{
    /**
 * Run the database seeds.
 *
 * @return void
 */
    public function run()
    {
        Category::insert([
            ['category_name' => 'Necklaces'],
            ['category_name' => 'Rings'],
            ['category_name' => 'Earrings'],
            ['category_name' => 'Watches'],
            ['category_name' => 'Bracelets'],
            ['category_name' => 'Sets'],
        ]);
    }
}
