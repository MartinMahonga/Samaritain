<?php

namespace Database\Seeders;

use App\Models\Parcelle;
use App\Models\ParcelleCategory;
use Illuminate\Database\Seeder;

class ParcelleCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = Parcelle::TYPES;

        foreach ($categories as $slug => $name) {
            $category = ParcelleCategory::updateOrCreate(
                ['slug' => $slug],
                [
                    'name' => $name,
                    'is_active' => true,
                ]
            );

            // Mapper les parcelles existantes qui utilisent le type string vers la nouvelle catégorie
            Parcelle::where('type', $slug)
                ->whereNull('parcelle_category_id')
                ->update(['parcelle_category_id' => $category->id]);
        }

        // Assurer un sort_order par défaut (par ordre de nom si vide)
        $order = 0;
        foreach (ParcelleCategory::orderBy('sort_order')->orderBy('name')->get() as $category) {
            $category->update(['sort_order' => $order]);
            $order++;
        }
    }
}
