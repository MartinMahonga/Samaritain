<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->string('status')->default('draft')->change();
            $table->unsignedInteger('contract_version')->default(1);
            $table->string('content_hash')->nullable();
            $table->timestamp('owner_signed_at')->nullable();
            $table->timestamp('tenant_signed_at')->nullable();
            $table->timestamp('activated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->string('status')->default('active')->change();
            $table->dropColumn([
                'contract_version',
                'content_hash',
                'owner_signed_at',
                'tenant_signed_at',
                'activated_at',
            ]);
        });
    }
};
