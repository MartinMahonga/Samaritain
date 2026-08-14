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
        Schema::table('transactions', function (Blueprint $table) {
            // A transaction can optionally originate from a rent payment (tenant
            // paying their monthly rent through pawaPay).
            $table->foreignId('rent_payment_id')
                ->nullable()
                ->after('visit_pass_id')
                ->constrained('rent_payments')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropConstrainedForeignId('rent_payment_id');
        });
    }
};
