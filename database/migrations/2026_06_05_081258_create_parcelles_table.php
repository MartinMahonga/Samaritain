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
        Schema::create('parcelles', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->text('description')->nullable();
            $table->string('localisation');
            $table->string('quartier');
            $table->string('ville');
            $table->decimal('superficie', 10, 2);
            $table->decimal('prix', 15, 2);
            $table->enum('statut', ['disponible', 'vendu', 'réservé'])->default('disponible');
            $table->string('reference')->unique();
            $table->boolean('viabilisee')->default(false);
            $table->string('titre_foncier')->nullable();
            $table->timestamps();
        });

        Schema::create('parcelle_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parcelle_id')->constrained()->onDelete('cascade');
            $table->string('path');         // chemin du fichier stocké
            $table->string('url');          // URL publique
            $table->boolean('principale')->default(false); // image de couverture
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parcelles');
    }
};
