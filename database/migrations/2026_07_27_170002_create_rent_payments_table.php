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
        Schema::create('rent_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contract_id')->constrained('contracts')->cascadeOnDelete();
            $table->integer('month');
            $table->integer('year');
            $table->integer('amount_due');
            $table->integer('amount_paid')->default(0);
            $table->date('due_date');
            $table->date('paid_at')->nullable();
            $table->string('status')->default('unpaid'); // unpaid, partial, paid, late
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rent_payments');
    }
};
