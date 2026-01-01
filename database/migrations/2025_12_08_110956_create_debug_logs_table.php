<?php

// tafeld/database/migrations/2025_12_08_110956_create_debug_logs_table.php

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
        Schema::create('debug_logs', function (Blueprint $table) {

            // Primary Key (ULID)
            $table->ulid('id')->primary();

            // Core Log Fields
            $table->string('scope');
            $table->string('channel')->nullable();
            $table->string('level');
            $table->text('message');

            // JSON Context
            $table->jsonb('context')->nullable();

            // Optional User reference (nullable, FK wird später ergänzt)
            $table->ulid('user_id')->nullable();

            // Original Log Timestamp
            $table->timestamp('created_at');

            // Indexes for performance
            $table->index('scope');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('debug_logs');
    }
};
