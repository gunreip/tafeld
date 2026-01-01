<?php

// tafeld/database/migrations/2025_12_23_110152_create_ui_preferences_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ui_preferences', function (Blueprint $table) {
            // Primary Key (ULID)
            $table->ulid('id')->primary();

            // FK → users.ulid (kanonische Identität)
            $table->ulid('user_id')->nullable();

            // Kontext / Bereich (z. B. debug, forms, tables)
            $table->string('scope', 64);

            // Einstellungsschlüssel
            $table->string('key', 128);

            // Wert (bewusst string)
            $table->string('value', 255);

            $table->timestamps();

            // Eindeutigkeit: global + user-spezifisch
            $table->unique(
                ['user_id', 'scope', 'key'],
                'ui_preferences_user_scope_key_unique'
            );

            // FK-Constraint → users.ulid
            $table->foreign('user_id')
                ->references('ulid')
                ->on('users')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ui_preferences');
    }
};
