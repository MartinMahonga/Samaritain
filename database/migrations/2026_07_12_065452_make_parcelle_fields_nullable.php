<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('parcelles', function (Blueprint $table) {
            $table->string('statut')->nullable()->default('disponible')->change();
            $table->string('titre_foncier')->nullable()->change();
            $table->boolean('viabilisee')->nullable()->default(false)->change();
        });
    }

    public function down()
    {
        Schema::table('parcelles', function (Blueprint $table) {
            $table->string('statut')->nullable(false)->change();
            $table->string('titre_foncier')->nullable(false)->change();
            $table->boolean('viabilisee')->nullable(false)->change();
        });
    }
};
