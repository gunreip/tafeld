<?php

// tafeld/database/seeders/UiPreferenceDefaultsSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UiPreference;

class UiPreferenceDefaultsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaults = [
            // Debug / Admin UI
            [
                'scope' => 'debug',
                'key'   => 'keyboard.enter_behavior',
                'value' => 'stay',
            ],
            [
                'scope' => 'debug',
                'key'   => 'keyboard.arrow_wrap',
                'value' => 'false',
            ],

            // Forms
            [
                'scope' => 'forms',
                'key'   => 'keyboard.enter_behavior',
                'value' => 'next',
            ],

            // Tables
            [
                'scope' => 'tables',
                'key'   => 'keyboard.arrow_wrap',
                'value' => 'true',
            ],
        ];

        foreach ($defaults as $pref) {
            UiPreference::firstOrCreate(
                [
                    'user_id' => null,
                    'scope'   => $pref['scope'],
                    'key'     => $pref['key'],
                ],
                [
                    'value' => $pref['value'],
                ]
            );
        }
    }
}
