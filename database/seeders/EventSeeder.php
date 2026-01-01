<?php

// tafeld/database/seeders/EventSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Services\Events\EventImporter;
use App\Enums\EventType;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /**
         * Import school and business events (vacation-type events).
         */
        EventImporter::importTypes([
            EventType::School,
            EventType::Business,
        ]);
    }
}
