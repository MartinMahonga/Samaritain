<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class PropertyCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Appartement'],
            ['name' => 'Maison'],
            ['name' => 'Villa'],
            ['name' => 'Terrain'],
            ['name' => 'Bureau'],
            ['name' => 'Commerce'],
            ['name' => 'Entrepôt'],
            ['name' => 'Studio'],
            ['name' => 'Duplex'],
            ['name' => 'Immeuble'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
