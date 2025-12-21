<?php

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
        Schema::create('debug_scopes', function (Blueprint $table) {

            $table->ulid('id')->primary();

            $table->string('scope_key')->unique();
            $table->boolean('enabled')->default(true);

            $table->string('file_path')->nullable();

            // spätere Erweiterungen: z. B. {channels: […], color: “…”, retention: 30}
            $table->jsonb('options')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('debug_scopes');
    }
};
