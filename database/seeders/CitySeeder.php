<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    public function run(): void
    {
        $cities = [
            ['name' => 'Brazzaville'],
            ['name' => 'Pointe-Noire'],
        ];

        foreach ($cities as $city) {
            City::create($city);
        }
    }
}
