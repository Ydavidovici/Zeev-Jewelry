<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesTableSeeder extends Seeder
{
    public function run()
    {
        $roles = ['admin', 'customer', 'seller'];

        foreach ($roles as $role) {
            if (!Role::where('name', $role)->where('guard_name', 'api')->exists()) {
                Role::create(['name' => $role, 'guard_name' => 'api']);
            }
        }
    }
}
