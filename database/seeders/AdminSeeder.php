<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Falls schon ein Admin existiert: Keine Duplikate
        if (User::where('email', 'admin@example.com')->exists()) {
            return;
        }

        $admin = User::create([
            'name'     => 'System Admin',
            'email'    => 'admin@example.com',
            'password' => bcrypt('ChangeMe123!'),
        ]);

        $admin->assignRole('admin');
    }
}
