<?php

// tafeld/database/migrations/2025_12_25_201922_create_app_user_settings_table.php

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
        Schema::create('app_user_settings', function (Blueprint $table) {
            $table->ulid('ulid')->primary();

            $table->ulid('user_ulid');
            $table->string('key');
            $table->json('value');

            $table->timestamps();

            $table->unique(['user_ulid', 'key']);

            $table->foreign('user_ulid')
                ->references('ulid')
                ->on('users')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_user_settings');
    }
};
