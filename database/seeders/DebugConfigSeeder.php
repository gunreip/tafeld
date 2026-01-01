<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DebugSetting;

class DebugConfigSeeder extends Seeder
{
    /**
     * Seed the application's debug configuration.
     */
    public function run(): void
    {
        DebugSetting::firstOrCreate(
            [], // wir wollen nur EINEN Datensatz
            [
                'enabled'      => true,
                'reset_on_run' => false,

                // globale Default-Channels
                'channels' => [
                    'console'     => true,
                    'file'        => true,
                    'db'          => true,
                    'laravel_log' => true,
                ],
            ]
        );
    }
}
