<?php

// database/seeders/RolesTableSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RolesTableSeeder extends Seeder
{
    public function run()
    {
        Role::insert([
            ['name' => 'Admin'],
            ['name' => 'Customer'],
            ['name' => 'Guest'],
            ['name' => 'Seller'],
        ]);
    }
}
