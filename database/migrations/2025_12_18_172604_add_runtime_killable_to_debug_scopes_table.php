<?php

// tafeld/database/migrations/2025_12_18_172604_add_runtime_killable_to_debug_scopes_table.php

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
        Schema::table('debug_scopes', function (Blueprint $table) {
            $table
                ->boolean('runtime_killable')
                ->default(true)
                ->after('enabled');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('debug_scopes', function (Blueprint $table) {
            $table->dropColumn('runtime_killable');
        });
    }
};
