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
        Schema::table('rent_payments', function (Blueprint $table) {
            // The transaction that funded this rent payment. Points to the UUID
            // primary key of the transactions table.
            $table->uuid('transaction_id')
                ->nullable()
                ->after('status')
                ->index();

            $table->foreign('transaction_id')
                ->references('transaction_id')
                ->on('transactions')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rent_payments', function (Blueprint $table) {
            $table->dropForeign(['transaction_id']);
            $table->dropColumn('transaction_id');
        });
    }
};
