<?php

// tafeld/database/migrations/2025_11_13_135023_create_countries_table.php

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
        Schema::create('countries', function (Blueprint $table) {

            // ULID primary key (projektweit Standard)
            $table->ulid('id')->primary();

            $table->string('iso_3166_2', 2)->unique();
            $table->string('iso_3166_3', 3)->unique();
            $table->string('name_en');
            $table->string('name_de')->nullable();
            $table->string('region')->nullable();
            $table->string('subregion')->nullable();
            $table->string('currency_code', 3)->nullable();
            $table->string('phone_code', 10)->nullable();

            // Sortier- & Transkriptionsspalten
            // Sprachspezifische Sortierschlüssel (projektweite Pflicht)
            $table->string('sort_key_en')->nullable()->index();
            $table->string('sort_key_de')->nullable()->index();
            $table->string('translit_en')->nullable();
            $table->string('translit_de')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('countries');
    }
};
