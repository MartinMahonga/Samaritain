<?php

namespace Database\Seeders;

use App\Models\Amenity;
use Illuminate\Database\Seeder;

class AmenitySeeder extends Seeder
{
    public function run(): void
    {
        $amenities = [
            ['name' => 'Climatisation'],
            ['name' => 'Chauffage'],
            ['name' => 'Cuisine équipée'],
            ['name' => 'Réfrigérateur'],
            ['name' => 'Machine à laver'],
            ['name' => 'Parking'],
            ['name' => 'Garage'],
            ['name' => 'Jardin'],
            ['name' => 'Balcon'],
            ['name' => 'Terrasse'],
            ['name' => 'Piscine'],
            ['name' => 'Ascenseur'],
            ['name' => 'Internet'],
            ['name' => 'Wifi'],
            ['name' => 'Sécurité 24/7'],
            ['name' => 'Caméras'],
            ['name' => 'Groupe électrogène'],
            ['name' => 'Forage d\'eau'],
            ['name' => 'Salle de sport'],
            ['name' => 'Meublé'],
        ];

        foreach ($amenities as $amenity) {
            Amenity::create($amenity);
        }
    }
}
