<?php

// tafeld/database/migrations/2025_12_08_122623_add_user_fk_to_debug_logs_table.php

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

            // Foreign Key auf users.ulid nachträglich ergänzen
            $table->foreign('user_id')
                ->references('ulid')
                ->on('users')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('debug_logs', function (Blueprint $table) {

            // Foreign Key wieder entfernen
            $table->dropForeign(['user_id']);
        });
    }
};
