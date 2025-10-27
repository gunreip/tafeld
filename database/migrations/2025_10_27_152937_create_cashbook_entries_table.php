<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cashbook_entries', function (Blueprint $table) {
            $table->id();                               // interner Primärschlüssel
            $table->ulid('ulid')->unique()->index();    // externe ULID
            $table->text('encrypted_data')->nullable(); // verschlüsselte Daten
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cashbook_entries');
    }
};
