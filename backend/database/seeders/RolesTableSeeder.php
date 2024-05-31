<?php

// database/seeders/RolesTableSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::insert([
            ['role_name' => 'Admin'],
            ['role_name' => 'Customer'],
            ['role_name' => 'Guest'],
            ['role_name' => 'Seller']
        ]);
    }
}
