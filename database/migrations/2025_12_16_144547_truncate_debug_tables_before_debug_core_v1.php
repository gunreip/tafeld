<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('debug_logs')->truncate();
        DB::table('debug_settings')->truncate();
        DB::table('debug_scopes')->truncate();
    }

    public function down(): void
    {
        // bewusst leer (einmaliger Reset technischer Debug-Daten)
    }
};
