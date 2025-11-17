<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Person;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class PeopleSeeder extends Seeder
{
    public function run(): void
    {
        // Tabelle leeren, damit die Seeder sauber funktionieren
        DB::table('people')->truncate();

        // Alle verfügbaren Länder holen
        $countryIds = DB::table('countries')->pluck('id')->toArray();

        // Falls keine Länder existieren, Seeder abbrechen
        if (empty($countryIds)) {
            throw new \RuntimeException('Countries table has no entries.');
        }

        $people = [
            ['John', 'Doe', 'john.doe@example.com', '+1 202 555 0199'],
            ['Jane', 'Smith', 'jane.smith@example.com', '+44 20 7946 0958'],
            ['Michael', 'Müller', 'm.mueller@example.com', '+49 30 123456'],
            ['Sarah', 'Connor', 's.connor@example.com', '+1 310 555 2333'],
            ['Laura', 'Schneider', 'laura.schneider@example.com', '+49 40 987654'],
            ['Tom', 'Bauer', 'tom.bauer@example.com', '+43 1 3422123'],
            ['Anna', 'Kowalski', 'anna.kowalski@example.com', '+48 22 654321'],
            ['David', 'Fischer', 'd.fischer@example.com', '+41 44 998877'],
            ['Maria', 'Gomez', 'maria.gomez@example.com', '+34 91 123456'],
            ['Alex', 'Anderson', 'alex.anderson@example.com', '+1 503 555 9988'],
            ['Emily', 'Brown', 'emily.brown@example.com', '+1 646 555 7788'],
            ['Liam', 'Johnson', 'liam.johnson@example.com', '+1 213 555 1122'],
            ['Olivia', 'Wilson', 'olivia.wilson@example.com', '+44 161 555 2233'],
            ['Noah', 'Taylor', 'noah.taylor@example.com', '+61 2 5550 3344'],
            ['Emma', 'Anderson', 'emma.anderson@example.com', '+1 503 555 4433'],
            ['Ava', 'Thomas', 'ava.thomas@example.com', '+1 720 555 9911'],
            ['Sophia', 'Jackson', 'sophia.jackson@example.com', '+34 91 555 8877'],
            ['Mia', 'White', 'mia.white@example.com', '+45 32 445566'],
            ['Ethan', 'Harris', 'ethan.harris@example.com', '+49 89 321654'],
            ['James', 'Martin', 'james.martin@example.com', '+33 1 44557788'],
            ['Benjamin', 'Thompson', 'ben.thompson@example.com', '+1 415 555 2299'],
            ['Lucas', 'Garcia', 'lucas.garcia@example.com', '+34 93 889977'],
            ['Henry', 'Martinez', 'henry.martinez@example.com', '+1 903 555 8822'],
            ['Alexander', 'Robinson', 'alex.robinson@example.com', '+1 707 555 9090'],
            ['Michael', 'Clarkson', 'michael.clarkson@example.com', '+31 20 445566'],
            ['Daniel', 'Rodriguez', 'daniel.rodriguez@example.com', '+351 21 998877'],
            ['Matthew', 'Lewis', 'matthew.lewis@example.com', '+1 206 555 6144'],
            ['Sebastian', 'Lee', 'sebastian.lee@example.com', '+81 3 556677'],
            ['Jack', 'Walker', 'jack.walker@example.com', '+1 702 555 4433'],
            ['Logan', 'Hall', 'logan.hall@example.com', '+1 505 555 7722'],
            ['Jacob', 'Allen', 'jacob.allen@example.com', '+1 503 555 8811'],
            ['Jayden', 'Young', 'jayden.young@example.com', '+32 2 556677'],
            ['Oscar', 'King', 'oscar.king@example.com', '+1 312 555 1991'],
            ['Aiden', 'Wright', 'aiden.wright@example.com', '+1 210 555 7711'],
            ['Samuel', 'Scott', 'samuel.scott@example.com', '+1 616 555 3334'],
            ['Leo', 'Torres', 'leo.torres@example.com', '+34 95 554433'],
            ['David', 'Nguyen', 'david.nguyen@example.com', '+84 24 448822'],
            ['Wyatt', 'Hill', 'wyatt.hill@example.com', '+1 602 555 4455'],
            ['Carter', 'Green', 'carter.green@example.com', '+1 904 555 8899'],
            ['Julian', 'Adams', 'julian.adams@example.com', '+1 209 555 6211'],
        ];

        foreach ($people as $p) {
            Person::create([
                'id'         => Str::ulid()->toBase32(), // ULID in 26-stelliger Form
                'first_name' => $p[0],
                'last_name'  => $p[1],
                'email'      => $p[2],
                'phone'      => $p[3],
                'country_id' => $countryIds[array_rand($countryIds)],
            ]);
        }
    }
}
