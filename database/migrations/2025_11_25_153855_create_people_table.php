<?php

// tafeld/database/migrations/2025_11_25_153855_create_people_table.php

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
        Schema::create('people', function (Blueprint $table) {

            // Primary Key (ULID)
            $table->ulid('id')->primary();

            // Personal names
            $table->string('first_name');
            $table->string('last_name');
            $table->string('first_name_sort_key')->nullable();
            $table->string('first_name_translit')->nullable();
            $table->string('last_name_sort_key')->nullable();
            $table->string('last_name_translit')->nullable();

            // Address
            $table->string('street')->nullable();
            $table->string('house_number')->nullable();
            $table->ulid('country_id')->nullable()->constrained('countries')->restrictOnDelete();
            $table->string('zipcode')->nullable();
            $table->string('city')->nullable();

            // Nationality (countries.id)
            $table->ulid('nationality_id')->nullable()->constrained('countries')->restrictOnDelete();

            // Birthdate
            $table->date('birthdate')->nullable();

            // Employment
            $table->date('employment_start')->nullable();
            $table->date('employment_end')->nullable();

            // Phone: mobile
            $table->ulid('mobile_country_id')->nullable()->constrained('countries')->restrictOnDelete();
            $table->string('mobile_area')->nullable();
            $table->string('mobile_number')->nullable(); // encrypted cast in model

            // Phone: landline
            $table->ulid('phone_country_id')->nullable()->constrained('countries')->restrictOnDelete();
            $table->string('phone_area')->nullable();
            $table->string('phone_number')->nullable(); // encrypted cast in model

            // Email (split)
            $table->string('email_local')->nullable();
            $table->string('email_domain')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('people');
    }
};
