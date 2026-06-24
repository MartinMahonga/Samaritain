<?php

namespace Database\Seeders;

use App\Models\ArtisanCategory;
use Illuminate\Database\Seeder;

class ArtisanCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Plomberie',
            'Électricité',
            'Maçonnerie',
            'Peinture',
            'Menuiserie',
            'Carrelage',
            'Toiture',
            'Jardinage',
            'Déménagement',
            'Serrurerie',
            'Climatisation',
            'Architecture',
            'Décoration intérieure',
            'Rénovation',
            'Isolation',
        ];

        foreach ($categories as $category) {
            ArtisanCategory::create(['name' => $category]);
        }
    }
}
