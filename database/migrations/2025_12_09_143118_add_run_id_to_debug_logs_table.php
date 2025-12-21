<?php

// tafeld/database/migrations/2025_12_09_143118_add_run_id_to_debug_logs_table.php

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
        Schema::table('debug_logs', function (Blueprint $table) {
            // Run-ID für zusammengehörige Debug-Läufe
            $table->ulid('run_id')->index()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('debug_logs', function (Blueprint $table) {
            // Run-ID wieder entfernen
            $table->dropColumn('run_id');
        });
    }
};
