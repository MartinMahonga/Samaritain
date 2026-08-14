<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class PropertyCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Chambre simple', 'price_type' => 'monthly'],
            ['name' => 'Chambre moderne', 'price_type' => 'monthly'],
            ['name' => 'Studio simple', 'price_type' => 'monthly'],
            ['name' => 'Studio moderne', 'price_type' => 'monthly'],
            ['name' => 'Appartement simple', 'price_type' => 'monthly'],
            ['name' => 'Appartement meublé', 'price_type' => 'monthly'],
            ['name' => 'Appartement journalier', 'price_type' => 'daily'],
        ];

        foreach ($categories as $index => $category) {
            Category::updateOrCreate(
                ['name' => $category['name']],
                [
                    'price_type' => $category['price_type'],
                    'is_active' => true,
                    'sort_order' => $index,
                ]
            );
        }
    }
}
