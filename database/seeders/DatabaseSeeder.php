<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
            CitySeeder::class,
            AmenitySeeder::class,
            ArrondissementSeeder::class,
            ArtisanCategorySeeder::class,
            ParcelleSeeder::class,
            PropertySeeder::class,
            PropertyCategorySeeder::class,
            OwnerPortalSeeder::class,
        ]);
    }
}
