<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        /**
         * Bestehende Einträge entfernen:
         * debug_settings war bisher global (1 Datensatz).
         * Für das neue per-Scope-Modell sind Alt-Daten semantisch wertlos.
         */
        DB::table('debug_settings')->truncate();

        Schema::table('debug_settings', function (Blueprint $table) {
            $table
                ->string('scope_key')
                ->after('id');

            $table
                ->unique('scope_key', 'debug_settings_scope_key_unique');
        });
    }

    public function down(): void
    {
        Schema::table('debug_settings', function (Blueprint $table) {
            $table->dropUnique('debug_settings_scope_key_unique');
            $table->dropColumn('scope_key');
        });
    }
};
