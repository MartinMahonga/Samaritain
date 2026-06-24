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
        Schema::create('artisans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('business_name');
            $table->string('slug')->unique();
            $table->string('profession');
            $table->text('bio');
            $table->string('phone');
            $table->string('whatsapp')->nullable();
            $table->string('website')->nullable();
            $table->string('avatar')->nullable();
            $table->string('cover')->nullable();
            $table->string('city')->nullable();
            // $table->foreignId('city_id')->constrained()->cascadeOnDelete();
            $table->integer('experience')->nullable();
            $table->boolean('verified')->nullable();
            $table->boolean('is_active')->nullable();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('artisans');
    }
};
