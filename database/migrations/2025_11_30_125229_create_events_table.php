<?php

// tafeld/database/migrations/2025_11_30_125229_create_events_table.php

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
        Schema::create('events', function (Blueprint $table) {

            // ULID primary key
            $table->ulid('id')->primary();

            // Region reference
            $table->ulid('country_region_id');
            $table
                ->foreign('country_region_id')
                ->references('id')
                ->on('country_regions')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            // Event type
            $table->string('event_type', 32)->index();

            // Names
            $table->string('name_de', 128);
            $table->string('name_en', 128)->nullable();

            // Transliteration
            $table->string('translit_de', 128)->nullable();
            $table->string('translit_en', 128)->nullable();

            // Sort keys
            $table->string('sort_key_de', 128)->nullable()->index();
            $table->string('sort_key_en', 128)->nullable()->index();

            // Date range
            $table->date('start_date');
            $table->date('end_date');

            // Optional description
            $table->text('description')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
