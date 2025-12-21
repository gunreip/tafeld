<?php

// tafeld/database/migrations/2025_11_28_123345_create_holidays_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('holidays', function (Blueprint $table) {
            // ULID Primärschlüssel
            $table->ulid('id')->primary();

            // Zugehöriges Land (z.B. DE)
            $table->ulid('country_id')
                ->constrained('countries')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            // NULL = bundesweit, Code = regionaler Feiertag (z.B. "NW", "BY")
            $table->string('region_code', 5)->nullable();

            // Datum des Feiertags
            $table->date('date');

            // Feiertagsbezeichnung
            $table->string('name_de');
            $table->string('name_en')->nullable();

            // Transliteration (z.B. für internationale Feiertage / ausländische Mitarbeiter)
            $table->string('translit_de')->nullable();
            $table->string('translit_en')->nullable();

            // Sortierschlüssel gemäß Projektregel (sort_key_xx Pflicht)
            $table->string('sort_key_de');
            $table->string('sort_key_en')->nullable();

            // Feste oder bewegliche Feiertage
            $table->boolean('is_static')->default(false);

            // Optional: Ist der Tag ein gesetzlicher „Arbeitsfrei“-Tag?
            $table->boolean('is_business_closed')->nullable();

            $table->timestamps();

            // Optional sinnvoll: Unique-Kombination
            $table->unique(['country_id', 'region_code', 'date'], 'unique_holiday_per_region');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('holidays');
    }
};
