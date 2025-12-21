<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('debug_scopes', function (Blueprint $table) {
            // Auto-Discovery / Aktivität
            $table->timestamp('first_seen_at')->nullable();
            $table->timestamp('last_seen_at')->nullable();

            // Kontext-Metadaten (v1.1-fähig)
            $table->string('origin', 50)->nullable(); // route | component | field
            $table->string('route')->nullable();      // z. B. /persons/create
            $table->string('component')->nullable();  // Livewire-Komponente
            $table->string('field')->nullable();      // Formularfeld
            $table->text('description')->nullable();  // Pflege im Dashboard
        });
    }

    public function down(): void
    {
        Schema::table('debug_scopes', function (Blueprint $table) {
            $table->dropColumn([
                'first_seen_at',
                'last_seen_at',
                'origin',
                'route',
                'component',
                'field',
                'description',
            ]);
        });
    }
};
