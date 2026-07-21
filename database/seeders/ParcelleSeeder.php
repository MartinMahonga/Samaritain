<?php

namespace Database\Seeders;

use App\Models\Arrondissement;
use App\Models\Parcelle;
use App\Models\ParcelleImage;
use App\Models\User;
use Illuminate\Database\Seeder;

class ParcelleSeeder extends Seeder
{
    public function run(): void
    {
        $owner = User::find(1);

        if (! $owner) {
            return;
        }

        $brazzavilleArrondissements = Arrondissement::whereHas('city', fn ($q) => $q->where('name', 'Brazzaville'))->pluck('id', 'name');
        $pointeNoireArrondissements = Arrondissement::whereHas('city', fn ($q) => $q->where('name', 'Pointe-Noire'))->pluck('id', 'name');

        $parcelles = [
            // === Brazzaville (15 parcelles) ===
            // Makélékélé
            [
                'titre' => 'Terrain résidentiel Makélékélé',
                'description' => 'Magnifique terrain plat situé dans le quartier résidentiel de Makélékélé. Idéal pour construction villa avec jardin. Proche des commerces et écoles.',
                'localisation' => 'Quartier Résidentiel, Rue Mavouzi',
                'ville' => 'Brazzaville',
                'arrondissement' => 'Makélékélé',
                'superficie' => 500,
                'prix' => 15000000,
                'statut' => 'disponible',
                'viabilisee' => true,
                'titre_foncier' => 'TF-BZV-2024-001',
            ],
            [
                'titre' => 'Terrain commercial Makélékélé',
                'description' => "Terrain stratégique en plein centre de Makélékélé, face à l'axe principal. Parfait pour activité commerciale ou immeuble de bureaux.",
                'localisation' => 'Axe Principal, Face marché Makélékélé',
                'ville' => 'Brazzaville',
                'arrondissement' => 'Makélékélé',
                'superficie' => 350,
                'prix' => 25000000,
                'statut' => 'disponible',
                'viabilisee' => true,
                'titre_foncier' => 'TF-BZV-2024-002',
            ],
            // Bacongo
            [
                'titre' => 'Villa avec piscine Bacongo',
                'description' => 'Superbe villa de 3 chambres avec piscine, grand salon, cuisine équipée et terrasse. Quartier calme et sécurisé. Accès 24h/24.',
                'localisation' => 'Quartier Plateau, Rue des Manguiers',
                'ville' => 'Brazzaville',
                'arrondissement' => 'Bacongo',
                'superficie' => 800,
                'prix' => 85000000,
                'statut' => 'disponible',
                'viabilisee' => true,
                'titre_foncier' => 'TF-BZV-2023-015',
            ],
            [
                'titre' => 'Terrain constructible Bacongo',
                'description' => 'Terrain viabilisé dans un lotissement récent à Bacongo. Eau, électricité et voirie aux abords. Idéal pour maison individuelle.',
                'localisation' => 'Lotissement Mpila, Bacongo',
                'ville' => 'Brazzaville',
                'arrondissement' => 'Bacongo',
                'superficie' => 400,
                'prix' => 18000000,
                'statut' => 'réservé',
                'viabilisee' => true,
                'titre_foncier' => 'TF-BZV-2025-003',
            ],
            // Poto-Poto
            [
                'titre' => 'Immeuble rapport Poto-Poto',
                'description' => 'Immeuble de 4 appartements (2 F2 et 2 F3) en plein centre de Poto-Poto. Fort potentiel locatif. Tous loués actuellement.',
                'localisation' => 'Avenue de la Liberté, Poto-Poto Centre',
                'ville' => 'Brazzaville',
                'arrondissement' => 'Poto-Poto',
                'superficie' => 600,
                'prix' => 120000000,
                'statut' => 'disponible',
                'viabilisee' => true,
                'titre_foncier' => 'TF-BZV-2022-008',
            ],
            [
                'titre' => 'Terrain pour projet immobilier',
                'description' => 'Grand terrain de 2500 m² idéal pour promoteur immobilier. Zone en plein développement avec tous les raccordements disponibles.',
                'localisation' => 'Zone Mpissa, Poto-Poto',
                'ville' => 'Brazzaville',
                'arrondissement' => 'Poto-Poto',
                'superficie' => 2500,
                'prix' => 65000000,
                'statut' => 'disponible',
                'viabilisee' => false,
                'titre_foncier' => 'TF-BZV-2024-010',
            ],
            // Moungali
            [
                'titre' => 'Parcelle d\'angle Moungali',
                'description' => "Parcelle d'angle idéale pour commerce ou habitation. Grande visibilité, accès facile. Proche du marché total et des arrêts de bus.",
                'localisation' => 'Carrefour Moungali, Rue de la Paix',
                'ville' => 'Brazzaville',
                'arrondissement' => 'Moungali',
                'superficie' => 300,
                'prix' => 22000000,
                'statut' => 'disponible',
                'viabilisee' => true,
                'titre_foncier' => 'TF-BZV-2024-005',
            ],
            [
                'titre' => 'Terrain viabilisé Moungali',
                'description' => 'Terrain bien situé dans un lotissement calme de Moungali. Viabilisé (eau, électricité). À seulement 5 minutes du centre-ville.',
                'localisation' => 'Lotissement Les Jardins, Moungali',
                'ville' => 'Brazzaville',
                'arrondissement' => 'Moungali',
                'superficie' => 450,
                'prix' => 20000000,
                'statut' => 'vendu',
                'viabilisee' => true,
                'titre_foncier' => 'TF-BZV-2023-020',
            ],
            // Ouenzé
            [
                'titre' => 'Grand terrain Ouenzé',
                'description' => 'Très grand terrain plat de 3000 m² à Ouenzé. Parfait pour projet immobilier d\'envergure ou complexe sportif. Zone calme et accessible.',
                'localisation' => 'Quartier Industriel, Ouenzé',
                'ville' => 'Brazzaville',
                'arrondissement' => 'Ouenzé',
                'superficie' => 3000,
                'prix' => 75000000,
                'statut' => 'disponible',
                'viabilisee' => false,
                'titre_foncier' => 'TF-BZV-2024-007',
            ],
            // Talangaï
            [
                'titre' => 'Terrain Talangaï vue fleuve',
                'description' => 'Magnifique terrain avec vue imprenable sur le fleuve Congo. Zone résidentielle huppée. Parfait pour villa de standing.',
                'localisation' => 'Corniche, Talangaï',
                'ville' => 'Brazzaville',
                'arrondissement' => 'Talangaï',
                'superficie' => 600,
                'prix' => 45000000,
                'statut' => 'disponible',
                'viabilisee' => true,
                'titre_foncier' => 'TF-BZV-2024-008',
            ],
            // Mfilou
            [
                'titre' => 'Terrain économique Mfilou',
                'description' => 'Terrain abordable dans un nouveau lotissement à Mfilou. Idéal pour primo-accédants. Proche des commodités.',
                'localisation' => 'Lotissement Tsiémé, Mfilou',
                'ville' => 'Brazzaville',
                'arrondissement' => 'Mfilou',
                'superficie' => 350,
                'prix' => 9000000,
                'statut' => 'disponible',
                'viabilisee' => false,
                'titre_foncier' => 'TF-BZV-2025-009',
            ],
            // Madibou
            [
                'titre' => 'Parcelle Madibou zone tranquille',
                'description' => 'Parcelle calme et verdoyante à Madibou. Parfaite pour maison de famille avec jardin potager. Eau et électricité à proximité.',
                'localisation' => 'Route de Kimpila, Madibou',
                'ville' => 'Brazzaville',
                'arrondissement' => 'Madibou',
                'superficie' => 700,
                'prix' => 12000000,
                'statut' => 'réservé',
                'viabilisee' => false,
                'titre_foncier' => 'TF-BZV-2025-011',
            ],
            // Djiri
            [
                'titre' => 'Terrain agricole Djiri',
                'description' => 'Grand terrain à vocation agricole à Djiri. Terre fertile, idéal pour maraîchage ou plantation. Source d\'eau naturelle sur la parcelle.',
                'localisation' => 'Zone Rurale, Djiri Nord',
                'ville' => 'Brazzaville',
                'arrondissement' => 'Djiri',
                'superficie' => 5000,
                'prix' => 35000000,
                'statut' => 'disponible',
                'viabilisee' => false,
                'titre_foncier' => null,
            ],
            // Kintélé
            [
                'titre' => 'Terrain pour entrepôt Kintélé',
                'description' => 'Terrain plat et facilement accessible à Kintélé, à proximité du stade. Idéal pour construction d\'entrepôt ou de dépôt.',
                'localisation' => 'Zone Industrielle, Kintélé',
                'ville' => 'Brazzaville',
                'arrondissement' => 'Kintélé',
                'superficie' => 2000,
                'prix' => 40000000,
                'statut' => 'disponible',
                'viabilisee' => true,
                'titre_foncier' => 'TF-BZV-2024-013',
            ],
            // === Pointe-Noire (10 parcelles) ===
            // Patrice Emery Lumumba
            [
                'titre' => 'Terrain Centre Patrice Lumumba',
                'description' => 'Terrain en plein centre-ville de Pointe-Noire. Zone très prisée, forte activité commerciale. Parfait pour immeuble de rapport ou commerce.',
                'localisation' => 'Avenue Charles de Gaulle, Centre-Ville',
                'ville' => 'Pointe-Noire',
                'arrondissement' => 'Patrice Emery Lumumba',
                'superficie' => 400,
                'prix' => 55000000,
                'statut' => 'disponible',
                'viabilisee' => true,
                'titre_foncier' => 'TF-PNR-2024-001',
            ],
            [
                'titre' => 'Villa de standing centre-ville',
                'description' => 'Magnifique villa 4 chambres avec piscine et jardin tropical au cœur de Pointe-Noire. Finitions haut de gamme.',
                'localisation' => 'Quartier Cité, Patrice Lumumba',
                'ville' => 'Pointe-Noire',
                'arrondissement' => 'Patrice Emery Lumumba',
                'superficie' => 900,
                'prix' => 120000000,
                'statut' => 'disponible',
                'viabilisee' => true,
                'titre_foncier' => 'TF-PNR-2023-002',
            ],
            // Mvou-Mvou
            [
                'titre' => 'Terrain résidentiel Mvou-Mvou',
                'description' => 'Beau terrain résidentiel dans le quartier calme de Mvou-Mvou. Proche des écoles et du marché. Idéal pour famille.',
                'localisation' => 'Quartier Résidentiel, Mvou-Mvou',
                'ville' => 'Pointe-Noire',
                'arrondissement' => 'Mvou-Mvou',
                'superficie' => 500,
                'prix' => 18000000,
                'statut' => 'disponible',
                'viabilisee' => true,
                'titre_foncier' => 'TF-PNR-2024-003',
            ],
            // Tié-Tié
            [
                'titre' => 'Parcelle commerciale Tié-Tié',
                'description' => 'Parcelle à usage commercial dans la zone très animée de Tié-Tié. Passage piéton intense. Idéal pour boutique ou restaurant.',
                'localisation' => 'Marché Tié-Tié, Rue principale',
                'ville' => 'Pointe-Noire',
                'arrondissement' => 'Tié-Tié',
                'superficie' => 250,
                'prix' => 30000000,
                'statut' => 'disponible',
                'viabilisee' => true,
                'titre_foncier' => 'TF-PNR-2024-004',
            ],
            [
                'titre' => 'Terrain constructible Tié-Tié',
                'description' => 'Terrain constructible dans un lotissement calme à Tié-Tié. Bonne exposition, viabilisé. À 10 minutes de la plage.',
                'localisation' => 'Lotissement Soleil, Tié-Tié',
                'ville' => 'Pointe-Noire',
                'arrondissement' => 'Tié-Tié',
                'superficie' => 450,
                'prix' => 22000000,
                'statut' => 'disponible',
                'viabilisee' => true,
                'titre_foncier' => 'TF-PNR-2025-005',
            ],
            // Loandjili
            [
                'titre' => 'Terrain vue mer Loandjili',
                'description' => 'Terrain exceptionnel avec vue panoramique sur l\'océan Atlantique à Loandjili. Zone touristique en plein essor.',
                'localisation' => 'Corniche Loandjili, Bord de mer',
                'ville' => 'Pointe-Noire',
                'arrondissement' => 'Loandjili',
                'superficie' => 800,
                'prix' => 85000000,
                'statut' => 'disponible',
                'viabilisee' => true,
                'titre_foncier' => 'TF-PNR-2023-006',
            ],
            // Ngoyo
            [
                'titre' => 'Terrain agricole Ngoyo',
                'description' => 'Grand terrain plat à Ngoyo propice à l\'agriculture ou à un projet d\'élevage. Proche de la route nationale.',
                'localisation' => 'Zone Rurale, Ngoyo',
                'ville' => 'Pointe-Noire',
                'arrondissement' => 'Ngoyo',
                'superficie' => 4000,
                'prix' => 28000000,
                'statut' => 'disponible',
                'viabilisee' => false,
                'titre_foncier' => null,
            ],
            // Mongo-Port
            [
                'titre' => 'Terrain pour dépôt Mongo-Port',
                'description' => 'Terrain situé à proximité du port de Pointe-Noire. Idéal pour dépôt de marchandises, logistique ou entrepôt.',
                'localisation' => 'Zone Portuaire, Mongo-Port',
                'ville' => 'Pointe-Noire',
                'arrondissement' => 'Mongo-Port',
                'superficie' => 1500,
                'prix' => 50000000,
                'statut' => 'vendu',
                'viabilisee' => false,
                'titre_foncier' => 'TF-PNR-2024-008',
            ],
            // Ngombe
            [
                'titre' => 'Terrain calme Ngombe',
                'description' => 'Parcelle tranquille dans un quartier résidentiel de Ngombe. Idéal pour construction de maison individuelle. Voisinage agréable.',
                'localisation' => 'Quartier Résidentiel, Ngombe',
                'ville' => 'Pointe-Noire',
                'arrondissement' => 'Ngombe',
                'superficie' => 400,
                'prix' => 15000000,
                'statut' => 'réservé',
                'viabilisee' => true,
                'titre_foncier' => 'TF-PNR-2025-009',
            ],
            // Indiana
            [
                'titre' => 'Terrain lotissement Indiana',
                'description' => 'Terrain viabilisé dans le lotissement moderne d\'Indiana. Eau, électricité et voirie goudronnée. Quartier sécurisé.',
                'localisation' => 'Lotissement Indiana, Zone Ouest',
                'ville' => 'Pointe-Noire',
                'arrondissement' => 'Indiana',
                'superficie' => 550,
                'prix' => 28000000,
                'statut' => 'disponible',
                'viabilisee' => true,
                'titre_foncier' => 'TF-PNR-2024-010',
            ],
        ];

        foreach ($parcelles as $index => $data) {
            $arrondissementName = $data['arrondissement'];
            unset($data['arrondissement']);

            if ($data['ville'] === 'Brazzaville') {
                $arrondissementId = $brazzavilleArrondissements[$arrondissementName] ?? null;
            } else {
                $arrondissementId = $pointeNoireArrondissements[$arrondissementName] ?? null;
            }

            $numero = str_pad($index + 1, 3, '0', STR_PAD_LEFT);
            $reference = "PAR-2026-{$numero}";

            $parcelle = Parcelle::create(array_merge($data, [
                'reference' => $reference,
                'arrondissement_id' => $arrondissementId,
                'created_by' => $owner->id,
            ]));

            // Créer les images de démonstration
            $this->createDemoImages($parcelle, $index);
        }
    }

    private function createDemoImages(Parcelle $parcelle, int $index): void
    {
        // Image principale
        ParcelleImage::create([
            'parcelle_id' => $parcelle->id,
            'path' => "/images/parcelles/parcelle-{$index}-main.jpg",
            'url' => 'https://images.unsplash.com/photo-1582407947304-fd86f028f716?w=800&q=80',
            'principale' => true,
        ]);

        // Image secondaire 1 - terrain/construction
        ParcelleImage::create([
            'parcelle_id' => $parcelle->id,
            'path' => "/images/parcelles/parcelle-{$index}-1.jpg",
            'url' => 'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?w=800&q=80',
            'principale' => false,
        ]);

        // Image secondaire 2 - vue/quartier
        ParcelleImage::create([
            'parcelle_id' => $parcelle->id,
            'path' => "/images/parcelles/parcelle-{$index}-2.jpg",
            'url' => 'https://images.unsplash.com/photo-1500382017468-9049fed747ef?w=800&q=80',
            'principale' => false,
        ]);
    }
}
