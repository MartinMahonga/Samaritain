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
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained('properties')->cascadeOnDelete();
            $table->string('tenant_name');
            $table->string('tenant_email')->nullable();
            $table->string('tenant_phone')->nullable();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->integer('monthly_rent');
            $table->integer('deposit')->nullable();
            $table->string('status')->default('active'); // active, terminated, pending
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
