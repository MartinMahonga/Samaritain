<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * These columns were introduced by an earlier pawaPay implementation on the
     * existing ``transactions`` table. We guard with ``Schema::hasColumn`` so the
     * migration works both on fresh databases and on databases that already carry
     * the old columns.
     */
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            if (! Schema::hasColumn('transactions', 'deposit_id')) {
                $table->string('deposit_id')->nullable()->comment('pawaPay UUIDv4 idempotency key');
            }

            if (! Schema::hasColumn('transactions', 'provider')) {
                $table->string('provider')->nullable()->comment('pawaPay provider, e.g. MTN_MOMO_COG');
            }

            if (! Schema::hasColumn('transactions', 'currency')) {
                $table->string('currency', 3)->default('XAF');
            }

            if (! Schema::hasColumn('transactions', 'raw_response')) {
                $table->json('raw_response')->nullable()->comment('Raw pawaPay API response for reconciliation');
            }
        });

        // Ensure the deposit_id is unique for idempotency
        if (! Schema::hasIndex('transactions', 'transactions_deposit_id_unique')) {
            Schema::table('transactions', function (Blueprint $table) {
                $table->unique('deposit_id', 'transactions_deposit_id_unique');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            if (Schema::hasIndex('transactions', 'transactions_deposit_id_unique')) {
                $table->dropUnique('transactions_deposit_id_unique');
            }

            if (Schema::hasColumn('transactions', 'raw_response')) {
                $table->dropColumn('raw_response');
            }
        });
    }
};
