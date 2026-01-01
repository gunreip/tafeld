<?php

// tafeld/database/migrations/2025_11_29_135111_create_country_regions_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('country_regions', function (Blueprint $table) {

            // ULID Primärschlüssel
            $table->ulid('id')->primary();

            // FK → countries.id
            $table->ulid('country_id');
            $table
                ->foreign('country_id')
                ->references('id')
                ->on('countries')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            // ISO-Codes für Regionen (Bundesländer, Kantone, Provinzen …)
            // jetzt vollständig gemäß ISO 3166-2 / 3166-3
            $table->string('iso_3166_2', 16)->nullable();  // z.B. "DE-NW"
            $table->string('iso_3166_3', 32)->nullable();  // z.B. "DE-NRW"

            // Anzeige-Name & Sortierung
            $table->string('name_de', 128);
            $table->string('name_en', 128)->nullable();
            $table->string('fullname_de', 256)->nullable();
            $table->string('fullname_en', 256)->nullable();

            $table->string('translit_de', 128)->nullable();
            $table->string('translit_en', 128)->nullable();

            $table->string('sort_key_de', 128)->nullable()->index();
            $table->string('sort_key_en', 128)->nullable()->index();

            $table->timestamps();

            // ISO-Regionalidentifier müssen pro Land eindeutig sein
            $table->unique(['country_id', 'iso_3166_2']);
            $table->unique(['country_id', 'iso_3166_3']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('country_regions');
    }
};
