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
        Schema::table('parcelles', function (Blueprint $table) {
            $table->foreignId('parcelle_category_id')
                ->nullable()
                ->after('type')
                ->constrained('parcelle_categories')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('parcelles', function (Blueprint $table) {
            $table->dropForeign(['parcelle_category_id']);
            $table->dropColumn('parcelle_category_id');
        });
    }
};
