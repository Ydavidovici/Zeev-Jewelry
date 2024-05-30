<?php

// database/seeders/RolesTableSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('roles')->insert([
            ['role_name' => 'Admin'],
            ['role_name' => 'Customer'],
            ['role_name' => 'Guest'],
        ]);
    }
}
