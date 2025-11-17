<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        Permission::firstOrCreate(['name' => 'view persons']);
        Permission::firstOrCreate(['name' => 'edit persons']);
        Permission::firstOrCreate(['name' => 'delete persons']);
    }
}
