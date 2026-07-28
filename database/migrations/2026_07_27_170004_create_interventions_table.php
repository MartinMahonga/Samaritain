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
        Schema::create('interventions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained('properties')->cascadeOnDelete();
            $table->foreignId('artisan_id')->nullable()->constrained('artisans')->nullOnDelete();
            $table->string('title');
            $table->text('description');
            $table->string('category'); // plumbing, painting, roofing, locksmith, garden, heating, appliances, other
            $table->string('urgency'); // low, medium, high, emergency
            $table->string('status')->default('pending'); // pending, approved, in_progress, completed, cancelled
            $table->integer('cost')->default(0);
            $table->boolean('is_renovation')->default(false);
            $table->json('photos')->nullable();
            $table->dateTime('scheduled_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interventions');
    }
};
