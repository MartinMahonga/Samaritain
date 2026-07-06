<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visit_passes', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('property_id')->constrained()->cascadeOnDelete();
            $table->uuid('transaction_id')->nullable()->unique();
            $table->foreign('transaction_id')->references('transaction_id')->on('transactions')->nullOnDelete();
            $table->string('holder_name');
            $table->string('phone');
            $table->string('email')->nullable();
            $table->text('comment')->nullable();
            $table->string('reference')->unique();
            $table->integer('amount');
            $table->string('payment_status')->default('pending'); // pending, paid, failed
            $table->string('status')->default('pending_payment'); // pending_payment, active, expired, cancelled, payment_failed
            $table->timestamp('paid_at')->nullable();
            $table->string('qr_code_path')->nullable();
            $table->string('pdf_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visit_passes');
    }
};
