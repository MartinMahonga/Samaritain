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
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->longText('description')->nullable();
            $table->integer('price');
            $table->integer('surface');
            $table->integer('rooms')->default(1);
            $table->integer('bedrooms')->default(0);
            $table->integer('floor')->default(0);
            $table->boolean('furnished')->default(false);
            $table->string('address');
            $table->string('status')->default('available');
            $table->boolean('verified')->default(false);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(Blueprint $table): void
    {   
        $table->dropSoftDeletes();
        Schema::dropIfExists('properties');
    }
};
