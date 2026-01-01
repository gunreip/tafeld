<?php

// tafeld/database/migrations/2025_11_29_063606_add_display_date_and_confession_to_holidays_table.php

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
        Schema::table('holidays', function (Blueprint $table) {
            $table->boolean('display_date')
                ->default(true)
                ->after('is_static');

            $table->string('confession', 32)
                ->nullable()
                ->after('display_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('holidays', function (Blueprint $table) {
            $table->dropColumn('confession');
            $table->dropColumn('display_date');
        });
    }
};
