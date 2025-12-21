<?php

// tafeld/database/migrations/2025_12_09_181425_add_updated_at_to_debug_logs_table.php

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
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('debug_logs', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
    }
};
