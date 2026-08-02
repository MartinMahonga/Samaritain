<?php

use App\Models\Property;
use App\Models\VisitPass;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('visit_passes', function (Blueprint $table) {
            $table->string('visit_passable_type')->nullable()->after('property_id');
            $table->unsignedBigInteger('visit_passable_id')->nullable()->after('visit_passable_type');
            $table->index(['visit_passable_type', 'visit_passable_id']);
        });

        // Migrer les données existantes vers le polymorphisme
        VisitPass::query()
            ->whereNotNull('property_id')
            ->each(function (VisitPass $visitPass) {
                $visitPass->updateQuietly([
                    'visit_passable_type' => Property::class,
                    'visit_passable_id' => $visitPass->property_id,
                ]);
            });

        Schema::table('visit_passes', function (Blueprint $table) {
            $table->dropForeign(['property_id']);
            $table->dropColumn('property_id');
        });
    }

    public function down(): void
    {
        Schema::table('visit_passes', function (Blueprint $table) {
            $table->foreignId('property_id')->nullable()->after('id')->constrained()->nullOnDelete();
        });

        // Restaurer les données
        VisitPass::query()
            ->where('visit_passable_type', Property::class)
            ->each(function (VisitPass $visitPass) {
                $visitPass->updateQuietly([
                    'property_id' => $visitPass->visit_passable_id,
                ]);
            });

        Schema::table('visit_passes', function (Blueprint $table) {
            $table->dropIndex(['visit_passable_type', 'visit_passable_id']);
            $table->dropColumn(['visit_passable_type', 'visit_passable_id']);
        });
    }
};