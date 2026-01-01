<?php

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
        Schema::create('debug_settings', function (Blueprint $table) {

            $table->ulid('id')->primary();

            $table->boolean('enabled')->default(true);
            $table->boolean('reset_on_run')->default(false);

            // Globale Channel-Definitionen { console: true, file: true, laravel_log: true }
            $table->jsonb('channels')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('debug_settings');
    }
};
