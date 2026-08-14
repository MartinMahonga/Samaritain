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
        Schema::table('artisan_categories', function (Blueprint $table) {
            $table->text('description')->nullable()->after('slug');
            $table->boolean('is_active')->default(true)->after('description');
            $table->unsignedInteger('sort_order')->default(0)->after('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('artisan_categories', function (Blueprint $table) {
            $table->dropColumn(['description', 'is_active', 'sort_order']);
        });
    }
};
