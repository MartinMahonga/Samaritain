<?php

use App\Models\Parcelle;
use App\Models\Property;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Add slug columns as nullable first
        Schema::table('properties', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('title');
        });

        Schema::table('parcelles', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('titre');
        });

        // 2. Generate slugs for existing properties
        Property::withTrashed()->each(function (Property $property) {
            $base = Str::slug($property->title);
            $slug = $base;
            $counter = 1;

            while (Property::withTrashed()->where('slug', $slug)->where('id', '!=', $property->id)->exists()) {
                $slug = $base.'-'.$counter;
                $counter++;
            }

            $property->slug = $slug;
            $property->saveQuietly();
        });

        // 3. Generate slugs for existing parcelles
        Parcelle::each(function (Parcelle $parcelle) {
            $base = Str::slug($parcelle->titre);
            $slug = $base;
            $counter = 1;

            while (Parcelle::where('slug', $slug)->where('id', '!=', $parcelle->id)->exists()) {
                $slug = $base.'-'.$counter;
                $counter++;
            }

            $parcelle->slug = $slug;
            $parcelle->saveQuietly();
        });

        // 4. Add unique index and make non-nullable
        Schema::table('properties', function (Blueprint $table) {
            $table->string('slug')->unique()->nullable(false)->change();
        });

        Schema::table('parcelles', function (Blueprint $table) {
            $table->string('slug')->unique()->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn('slug');
        });

        Schema::table('parcelles', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
