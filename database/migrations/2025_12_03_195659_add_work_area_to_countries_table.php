<?php

// tafeld/database/migrations/2025_12_03_195659_add_work_area_to_countries_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\CountryWorkArea;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('countries', function (Blueprint $table) {
            $table->enum(
                'work_area',
                array_column(CountryWorkArea::cases(), 'value')
            )
                ->default(CountryWorkArea::THIRD_COUNTRY->value)
                ->after('sub_region');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('countries', function (Blueprint $table) {
            $table->dropColumn('work_area');
        });
    }
};
