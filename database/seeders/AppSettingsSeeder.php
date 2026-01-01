<?php

// tafeld/database/seeders/AppSettingsSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AppSetting;

class AppSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AppSetting::updateOrCreate(
            ['key' => 'debug.levels'],
            ['value' => [
                [
                    'value'     => '',
                    'label'     => 'Level (alle)',
                    'class'     => 'text-muted',
                    'semantic'  => 'all',
                    'icon-name' => 'bars-3',
                ],
                [
                    'value'     => 'debug',
                    'label'     => 'debug',
                    'class'     => 'text-info',
                    'icon-name' => 'bug-ant',
                ],
                [
                    'value'     => 'info',
                    'label'     => 'info',
                    'class'     => 'text-success',
                    'icon-name' => 'information-circle',
                ],
                [
                    'value'     => 'warning',
                    'label'     => 'warning',
                    'class'     => 'text-warning',
                    'icon-name' => 'exclamation-triangle',
                ],
                [
                    'value'     => 'error',
                    'label'     => 'error',
                    'class'     => 'text-danger',
                    'icon-name' => 'x-circle',
                ],
                [
                    'value'     => 'critical',
                    'label'     => 'critical',
                    'class'     => 'text-critical',
                    'icon-name' => 'fire',
                ],
            ]]
        );
    }
}
