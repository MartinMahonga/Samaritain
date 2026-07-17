<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('artisans', function (Blueprint $table) {
            $table->dropColumn('district');
            $table->foreignId('arrondissement_id')->nullable()->constrained()->nullOnDelete()->after('city');
        });
    }

    public function down(): void
    {
        Schema::table('artisans', function (Blueprint $table) {
            $table->dropForeign(['arrondissement_id']);
            $table->dropColumn('arrondissement_id');
            $table->string('district')->nullable()->after('city');
        });
    }
};
