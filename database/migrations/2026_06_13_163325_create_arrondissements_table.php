<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('arrondissements', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('city_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            // Optionnel : empêcher un doublon dans la même ville
            $table->unique(['city_id', 'name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('arrondissements');
    }
};
