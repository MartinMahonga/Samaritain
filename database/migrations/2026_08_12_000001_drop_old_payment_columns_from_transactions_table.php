<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Drop the dead columns left behind by the old payment implementation on the
     * ``transactions`` table. None of these columns are referenced anywhere in the
     * application code — they were part of the previous KPAY/USSD gateway that has
     * been fully replaced by the pawaPay integration.
     *
     * We guard with ``Schema::hasColumn`` because these columns were added to the
     * live database manually (no migration created them), so a fresh database will
     * not contain them.
     */
    public function up(): void
    {
        $deadColumns = [
            'kpay_payment_id',
            'external_id',
            'net_amount',
            'fee_amount',
            'payment_method',
        ];

        Schema::table('transactions', function (Blueprint $table) use ($deadColumns) {
            foreach ($deadColumns as $column) {
                if (Schema::hasColumn('transactions', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            if (! Schema::hasColumn('transactions', 'kpay_payment_id')) {
                $table->string('kpay_payment_id')->nullable();
            }
            if (! Schema::hasColumn('transactions', 'external_id')) {
                $table->string('external_id')->nullable();
            }
            if (! Schema::hasColumn('transactions', 'net_amount')) {
                $table->integer('net_amount')->nullable();
            }
            if (! Schema::hasColumn('transactions', 'fee_amount')) {
                $table->integer('fee_amount')->nullable();
            }
            if (! Schema::hasColumn('transactions', 'payment_method')) {
                $table->string('payment_method')->nullable();
            }
        });
    }
};
