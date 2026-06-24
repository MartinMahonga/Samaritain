<?php

namespace Database\Seeders;

use App\Models\Arrondissement;
use App\Models\City;
use Illuminate\Database\Seeder;

class ArrondissementSeeder extends Seeder
{
    public function run(): void
    {
        // Arrondissements de Brazzaville
        $brazzaville = City::where('name', 'Brazzaville')->first();

        if ($brazzaville) {
            $brazzavilleArrondissements = [
                'Makélékélé',
                'Bacongo',
                'Poto-Poto',
                'Moungali',
                'Ouenzé',
                'Talangaï',
                'Mfilou',
                'Madibou',
                'Djiri',
                'Kintélé',
                'Ngamaba',
                'Massina',
            ];

            foreach ($brazzavilleArrondissements as $arrondissement) {
                Arrondissement::create([
                    'name' => $arrondissement,
                    'city_id' => $brazzaville->id,
                ]);
            }
        }

        // Arrondissements de Pointe-Noire
        $pointeNoire = City::where('name', 'Pointe-Noire')->first();

        if ($pointeNoire) {
            $pointeNoireArrondissements = [
                'Patrice Emery Lumumba',
                'Mvou-Mvou',
                'Tié-Tié',
                'Loandjili',
                'Ngoyo',
                'Mongo-Port',
                'Ngombe',
                'Indiana',
            ];

            foreach ($pointeNoireArrondissements as $arrondissement) {
                Arrondissement::create([
                    'name' => $arrondissement,
                    'city_id' => $pointeNoire->id,
                ]);
            }
        }
    }
}
