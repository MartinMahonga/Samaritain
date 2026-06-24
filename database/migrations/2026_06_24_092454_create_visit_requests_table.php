<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('visit_requests', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('phone');
            $table->string('city')->nullable();
            $table->string('property_category')->nullable();
            $table
                ->foreignId('property_id')
                ->nullable()
                ->constrained()
                ->cascadeOnDelete();
            // Mettre un datetime plutard
            $table->string('preferred_date');
            $table->text('message')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('visit_requests');
    }
};
