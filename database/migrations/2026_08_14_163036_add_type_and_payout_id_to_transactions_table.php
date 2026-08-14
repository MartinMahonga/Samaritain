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
            // Distinguish deposits (tenant paying rent/pass) from payouts (owner sending money)
            $table->string('type')->default('deposit')->after('user_id');

            // pawaPay payoutId — only set for type=payout transactions
            $table->string('payout_id')->nullable()->after('deposit_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['type', 'payout_id']);
        });
    }
};
