<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('artisan_projects', function (Blueprint $table) {
            $table->unsignedBigInteger('views')->default(0)->after('image');
        });
    }

    public function down(): void
    {
        Schema::table('artisan_projects', function (Blueprint $table) {
            $table->dropColumn('views');
        });
    }
};
