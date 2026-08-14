<?php

namespace Database\Seeders;

use App\Models\ArtisanCategory;
use Illuminate\Database\Seeder;

class ArtisanCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Plombier',
            'Électricien',
            'Maçon',
            'Peintre',
            'Menuisier',
            'Carreleur',
            'Toiture',
            'Jardinier',
            'Déménagement',
            'Serrurier',
            'Climatisation',
            'Architecte',
            'Décorateur intérieur',
            'Rénovation',
            'Isolation',
        ];

        foreach ($categories as $index => $category) {
            ArtisanCategory::updateOrCreate(
                ['name' => $category],
                [
                    'is_active' => true,
                    'sort_order' => $index,
                ]
            );
        }
    }
}
