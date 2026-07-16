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
        Schema::table('visit_passes', function (Blueprint $table) {
            $table->timestamp('expires_at')->nullable()->after('paid_at');
        });

        Schema::table('pass_scans', function (Blueprint $table) {
            $table->foreignId('visit_pass_id')->nullable()->after('pass_id')->constrained('visit_passes')->onDelete('cascade');
            $table->unsignedBigInteger('pass_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pass_scans', function (Blueprint $table) {
            $table->dropConstrainedForeignId('visit_pass_id');
            $table->unsignedBigInteger('pass_id')->nullable(false)->change();
        });

        Schema::table('visit_passes', function (Blueprint $table) {
            $table->dropColumn('expires_at');
        });
    }
};
