<?php

namespace Database\Seeders;

use App\Enums\PropertyStatus;
use App\Models\Amenity;
use App\Models\Arrondissement;
use App\Models\Category;
use App\Models\City;
use App\Models\Property;
use App\Models\PropertyImage;
use App\Models\User;
use Illuminate\Database\Seeder;

class PropertySeeder extends Seeder
{
    public function run(): void
    {
        $owner = User::find(1);

        if (! $owner) {
            return;
        }

        $categories = Category::pluck('id', 'name');
        $cities = City::pluck('id', 'name');
        $brazzavilleArrondissements = Arrondissement::whereHas('city', fn ($q) => $q->where('name', 'Brazzaville'))->pluck('id', 'name');
        $pointeNoireArrondissements = Arrondissement::whereHas('city', fn ($q) => $q->where('name', 'Pointe-Noire'))->pluck('id', 'name');
        $amenities = Amenity::pluck('id', 'name');

        $properties = [
            // === Brazzaville — Studios ===
            [
                'title' => 'Studio simple Moungali',
                'description' => 'Studio simple et lumineux à Moungali. Idéal pour étudiant ou jeune travailleur. Cuisineette, salle d\'eau, Internet inclus.',
                'address' => 'Rue de la Paix, Moungali',
                'city' => 'Brazzaville',
                'arrondissement' => 'Moungali',
                'category' => 'Studio simple',
                'price' => 180000,
                'surface' => 22,
                'rooms' => 1,
                'bedrooms' => 0,
                'bathrooms' => 1,
                'floor' => 1,
                'furnished' => false,
                'latitude' => -4.2582,
                'longitude' => 15.2539,
                'status' => PropertyStatus::AVAILABLE,
                'is_verify' => true,
                'is_active' => true,
                'amenities' => ['Internet'],
            ],
            [
                'title' => 'Studio moderne Ouenzé',
                'description' => 'Studio moderne avec kitchenette équipée, climatisation et balcon dans une résidence sécurisée à Ouenzé. Parfait pour jeune actif.',
                'address' => 'Rue des Palmiers, Ouenzé',
                'city' => 'Brazzaville',
                'arrondissement' => 'Ouenzé',
                'category' => 'Studio moderne',
                'price' => 280000,
                'surface' => 28,
                'rooms' => 1,
                'bedrooms' => 0,
                'bathrooms' => 1,
                'floor' => 3,
                'furnished' => true,
                'latitude' => -4.2465,
                'longitude' => 15.2605,
                'status' => PropertyStatus::AVAILABLE,
                'is_verify' => true,
                'is_active' => true,
                'amenities' => ['Climatisation', 'Internet', 'Meublé'],
            ],

            // === Brazzaville — Chambres ===
            [
                'title' => 'Chambre simple Poto-Poto',
                'description' => 'Chambre simple dans une colocation calme à Poto-Poto. Salle de bain partagée, cuisine commune, Wi-Fi. Proche transports.',
                'address' => 'Avenue de la Liberté, Poto-Poto Centre',
                'city' => 'Brazzaville',
                'arrondissement' => 'Poto-Poto',
                'category' => 'Chambre simple',
                'price' => 85000,
                'surface' => 12,
                'rooms' => 1,
                'bedrooms' => 1,
                'bathrooms' => 0,
                'floor' => 2,
                'furnished' => false,
                'latitude' => -4.2634,
                'longitude' => 15.2429,
                'status' => PropertyStatus::AVAILABLE,
                'is_verify' => true,
                'is_active' => true,
                'amenities' => ['Internet'],
            ],
            [
                'title' => 'Chambre moderne Makélékélé',
                'description' => 'Chambre moderne meublée avec salle d\'eau privative dans une maison sécurisée à Makélékélé. Cuisine équipée partagée.',
                'address' => 'Quartier Plateau, Makélékélé',
                'city' => 'Brazzaville',
                'arrondissement' => 'Makélékélé',
                'category' => 'Chambre moderne',
                'price' => 150000,
                'surface' => 16,
                'rooms' => 1,
                'bedrooms' => 1,
                'bathrooms' => 1,
                'floor' => 1,
                'furnished' => true,
                'latitude' => -4.2876,
                'longitude' => 15.2321,
                'status' => PropertyStatus::RENTED,
                'is_verify' => true,
                'is_active' => true,
                'amenities' => ['Climatisation', 'Internet', 'Meublé'],
            ],

            // === Brazzaville — Appartements ===
            [
                'title' => 'Appartement simple Bacongo',
                'description' => 'Appartement simple non meublé à Bacongo. 2 chambres, salon, cuisine américaine. Idéal pour petite famille.',
                'address' => 'Rue des Cocotiers, Bacongo',
                'city' => 'Brazzaville',
                'arrondissement' => 'Bacongo',
                'category' => 'Appartement simple',
                'price' => 320000,
                'surface' => 55,
                'rooms' => 3,
                'bedrooms' => 2,
                'bathrooms' => 1,
                'floor' => 1,
                'furnished' => false,
                'latitude' => -4.2781,
                'longitude' => 15.2484,
                'status' => PropertyStatus::AVAILABLE,
                'is_verify' => true,
                'is_active' => true,
                'amenities' => ['Internet'],
            ],
            [
                'title' => 'Appartement meublé Talangaï',
                'description' => 'Appartement meublé de standing à Talangaï. 2 chambres climatisées, salon, cuisine équipée, balcon. Vue fleuve.',
                'address' => 'Corniche, Talangaï',
                'city' => 'Brazzaville',
                'arrondissement' => 'Talangaï',
                'category' => 'Appartement meublé',
                'price' => 580000,
                'surface' => 72,
                'rooms' => 3,
                'bedrooms' => 2,
                'bathrooms' => 1,
                'floor' => 4,
                'furnished' => true,
                'latitude' => -4.2418,
                'longitude' => 15.2372,
                'status' => PropertyStatus::AVAILABLE,
                'is_verify' => true,
                'is_active' => true,
                'amenities' => ['Climatisation', 'Cuisine équipée', 'Balcon', 'Parking', 'Internet'],
            ],
            [
                'title' => 'Appartement journalier Poto-Poto',
                'description' => 'Appartement journalier meublé en centre-ville. Idéal pour courts séjours. 2 chambres, cuisine, climatisation.',
                'address' => 'Avenue Matsoua, Poto-Poto',
                'city' => 'Brazzaville',
                'arrondissement' => 'Poto-Poto',
                'category' => 'Appartement journalier',
                'price' => 25000,
                'surface' => 50,
                'rooms' => 2,
                'bedrooms' => 1,
                'bathrooms' => 1,
                'floor' => 2,
                'furnished' => true,
                'latitude' => -4.2648,
                'longitude' => 15.2441,
                'status' => PropertyStatus::AVAILABLE,
                'is_verify' => true,
                'is_active' => true,
                'amenities' => ['Climatisation', 'Internet', 'Meublé'],
            ],

            // === Pointe-Noire — Studios ===
            [
                'title' => 'Studio simple centre Pointe-Noire',
                'description' => 'Studio simple au cœur de Pointe-Noire. Cuisineette, accès Internet.À deux pas des commerces.',
                'address' => 'Avenue Charles de Gaulle, Centre',
                'city' => 'Pointe-Noire',
                'arrondissement' => 'Patrice Emery Lumumba',
                'category' => 'Studio simple',
                'price' => 120000,
                'surface' => 20,
                'rooms' => 1,
                'bedrooms' => 0,
                'bathrooms' => 1,
                'floor' => 2,
                'furnished' => false,
                'latitude' => -4.7698,
                'longitude' => 11.8732,
                'status' => PropertyStatus::AVAILABLE,
                'is_verify' => true,
                'is_active' => true,
                'amenities' => ['Internet'],
            ],
            [
                'title' => 'Studio moderne Loandjili',
                'description' => 'Studio moderne avec vue mer à Loandjili. Cuisine équipée, climatisation, balcon. Résidence avec parking.',
                'address' => 'Corniche Loandjili',
                'city' => 'Pointe-Noire',
                'arrondissement' => 'Loandjili',
                'category' => 'Studio moderne',
                'price' => 220000,
                'surface' => 26,
                'rooms' => 1,
                'bedrooms' => 0,
                'bathrooms' => 1,
                'floor' => 5,
                'furnished' => true,
                'latitude' => -4.7782,
                'longitude' => 11.8634,
                'status' => PropertyStatus::AVAILABLE,
                'is_verify' => true,
                'is_active' => true,
                'amenities' => ['Climatisation', 'Internet', 'Meublé', 'Parking'],
            ],

            // === Pointe-Noire — Appartements ===
            [
                'title' => 'Appartement simple Mvou-Mvou',
                'description' => 'Appartement simple non meublé à Mvou-Mvou. 2 chambres, salon, cuisine. Quartier calme et familial.',
                'address' => 'Cité Résidentielle, Mvou-Mvou',
                'city' => 'Pointe-Noire',
                'arrondissement' => 'Mvou-Mvou',
                'category' => 'Appartement simple',
                'price' => 210000,
                'surface' => 48,
                'rooms' => 3,
                'bedrooms' => 2,
                'bathrooms' => 1,
                'floor' => 1,
                'furnished' => false,
                'latitude' => -4.7808,
                'longitude' => 11.8825,
                'status' => PropertyStatus::AVAILABLE,
                'is_verify' => true,
                'is_active' => true,
                'amenities' => ['Internet'],
            ],
            [
                'title' => 'Appartement meublé centre Pointe-Noire',
                'description' => 'Appartement meublé moderne en centre-ville. 2 chambres, salon, cuisine équipée, climatisation. Idéal pour exécutif.',
                'address' => 'Avenue Charles de Gaulle, Centre',
                'city' => 'Pointe-Noire',
                'arrondissement' => 'Patrice Emery Lumumba',
                'category' => 'Appartement meublé',
                'price' => 420000,
                'surface' => 58,
                'rooms' => 3,
                'bedrooms' => 2,
                'bathrooms' => 1,
                'floor' => 4,
                'furnished' => true,
                'latitude' => -4.7702,
                'longitude' => 11.8739,
                'status' => PropertyStatus::AVAILABLE,
                'is_verify' => true,
                'is_active' => true,
                'amenities' => ['Climatisation', 'Cuisine équipée', 'Internet', 'Meublé', 'Ascenseur'],
            ],
        ];

        foreach ($properties as $index => $data) {
            $cityName = $data['city'];
            $arrondissementName = $data['arrondissement'];
            $categoryName = $data['category'];
            $amenityNames = $data['amenities'];
            unset($data['city'], $data['arrondissement'], $data['category'], $data['amenities']);

            $cityId = $cities[$cityName] ?? null;
            $categoryId = $categories[$categoryName] ?? null;

            if ($cityName === 'Brazzaville') {
                $arrondissementId = $brazzavilleArrondissements[$arrondissementName] ?? null;
            } else {
                $arrondissementId = $pointeNoireArrondissements[$arrondissementName] ?? null;
            }

            $data['city_id'] = $cityId;
            $data['category_id'] = $categoryId;
            $data['arrondissement_id'] = $arrondissementId;
            $data['created_by'] = $owner->id;

            $property = Property::create($data);

            // Lier les amenities
            foreach ($amenityNames as $amenityName) {
                if (isset($amenities[$amenityName])) {
                    $property->amenities()->attach($amenities[$amenityName]);
                }
            }

            // Créer les images de démonstration
            $this->createDemoImages($property, $index);
        }
    }

    private function createDemoImages(Property $property, int $index): void
    {
        $images = [
            [
                'url' => 'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=800&q=80',
                'cover_image' => true,
            ],
            [
                'url' => 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=800&q=80',
                'cover_image' => false,
            ],
            [
                'url' => 'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?w=800&q=80',
                'cover_image' => false,
            ],
        ];

        foreach ($images as $image) {
            PropertyImage::create([
                'property_id' => $property->id,
                'image_url' => $image['url'],
                'cover_image' => $image['cover_image'],
            ]);
        }
    }
}
