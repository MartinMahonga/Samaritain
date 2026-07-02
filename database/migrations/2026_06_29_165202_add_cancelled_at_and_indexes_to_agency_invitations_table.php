<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('agency_invitations', function (Blueprint $table) {
            // Ajout de la colonne cancelled_at
            $table->timestamp('cancelled_at')->nullable()->after('accepted_at');

            // Index sur l'email pour les requêtes
            $table->index('email');

            // Index sur expires_at pour les requêtes de scope pending/expired
            $table->index('expires_at');
        });
    }

    public function down(): void
    {
        Schema::table('agency_invitations', function (Blueprint $table) {
            $table->dropColumn('cancelled_at');
            $table->dropIndex(['email']);
            $table->dropIndex(['expires_at']);
        });
    }
};
