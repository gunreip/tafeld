<?php

// tafeld/database/migrations/2025_11_11_143327_create_people_table.php

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
        Schema::create('people', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('first_name');
            $table->string('last_name');

            // Normalisierte Such- & Sortierspalten
            $table->string('first_name_sort_key')->nullable();
            $table->string('first_name_translit')->nullable();
            $table->string('last_name_sort_key')->nullable();
            $table->string('last_name_translit')->nullable();

            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('people');
    }
};
