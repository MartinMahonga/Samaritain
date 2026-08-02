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
        Schema::create('inspections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained('properties')->cascadeOnDelete();
            $table->foreignId('contract_id')->nullable()->constrained('contracts')->cascadeOnDelete();
            $table->string('type'); // check_in, check_out
            $table->date('date');
            $table->string('inspector_name');
            $table->json('rooms_data'); // rooms, status, notes
            $table->text('notes')->nullable();
            $table->json('photos')->nullable();
            $table->string('tenant_signature')->nullable();
            $table->string('owner_signature')->nullable();
            $table->string('pdf_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inspections');
    }
};
