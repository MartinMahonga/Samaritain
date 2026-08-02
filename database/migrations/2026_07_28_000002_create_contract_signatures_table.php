<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contract_signatures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contract_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('role', ['owner', 'tenant']);
            $table->timestamp('signed_at');
            $table->string('ip_address');
            $table->string('user_agent');
            $table->string('signature_image'); // path to image file
            $table->string('signature_hash')->nullable(); // hash of signature itself
            $table->string('contract_hash'); // hash of contract content at signing time
            $table->string('contract_version'); // version at signing time
            $table->timestamps();

            $table->unique(['contract_id', 'user_id']);
            $table->index(['contract_id', 'role']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contract_signatures');
    }
};
