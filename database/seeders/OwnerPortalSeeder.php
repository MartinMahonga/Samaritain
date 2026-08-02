<?php

namespace Database\Seeders;

use App\Models\Contract;
use App\Models\Document;
use App\Models\Inspection;
use App\Models\Intervention;
use App\Models\Invoice;
use App\Models\Property;
use App\Models\RentPayment;
use App\Models\User;
use Illuminate\Database\Seeder;

class OwnerPortalSeeder extends Seeder
{
    public function run(): void
    {
        $owner = User::where('email', 'owner@entreprise.com')->first();

        if (! $owner) {
            $this->command->warn('Owner user not found. Run RolesAndPermissionsSeeder first.');

            return;
        }

        $properties = Property::where('created_by', $owner->id)->get();

        if ($properties->isEmpty()) {
            $this->command->warn('No properties found for owner. Run PropertySeeder first.');

            return;
        }

        // 1. Create contracts with rent payments
        foreach ($properties->take(3) as $property) {
            $contract = Contract::create([
                'property_id' => $property->id,
                'tenant_name' => fake()->name(),
                'tenant_email' => fake()->email(),
                'tenant_phone' => '+2420'.fake()->numerify('#########'),
                'start_date' => now()->subMonths(6),
                'end_date' => now()->addMonths(6),
                'monthly_rent' => $property->price,
                'deposit' => $property->price,
                'status' => 'active',
                'created_by' => $owner->id,
            ]);

            // Generate 6 months of rent payments (some paid, some unpaid)
            for ($i = 5; $i >= 0; $i--) {
                $dueDate = now()->subMonths($i);
                $isPaid = $i > 0 || fake()->boolean(70);

                RentPayment::create([
                    'contract_id' => $contract->id,
                    'month' => $dueDate->month,
                    'year' => $dueDate->year,
                    'amount_due' => $contract->monthly_rent,
                    'amount_paid' => $isPaid ? $contract->monthly_rent : 0,
                    'due_date' => $dueDate,
                    'paid_at' => $isPaid ? $dueDate->copy()->addDays(fake()->numberBetween(0, 5)) : null,
                    'status' => $isPaid ? 'paid' : 'unpaid',
                ]);
            }
        }

        // 2. Create invoices
        $invoiceTypes = ['water', 'electricity', 'taxes', 'garbage'];
        foreach ($properties->take(4) as $property) {
            foreach ($invoiceTypes as $type) {
                $isPaid = fake()->boolean(60);
                Invoice::create([
                    'property_id' => $property->id,
                    'type' => $type,
                    'amount' => fake()->numberBetween(15000, 85000),
                    'due_date' => now()->subDays(fake()->numberBetween(0, 60)),
                    'paid_at' => $isPaid ? now()->subDays(fake()->numberBetween(0, 30)) : null,
                    'status' => $isPaid ? 'paid' : 'unpaid',
                    'created_by' => $owner->id,
                ]);
            }
        }

        // 3. Create interventions
        $categories = ['plumbing', 'painting', 'roofing', 'locksmith', 'garden', 'heating', 'appliances'];
        $urgencies = ['low', 'medium', 'high', 'emergency'];
        $statuses = ['pending', 'approved', 'in_progress', 'completed', 'cancelled'];

        foreach ($properties->take(3) as $property) {
            $interventionCount = fake()->numberBetween(2, 4);
            for ($i = 0; $i < $interventionCount; $i++) {
                $isRenovation = fake()->boolean(20);
                Intervention::create([
                    'property_id' => $property->id,
                    'title' => $isRenovation
                        ? fake()->randomElement(['Rénovation salle de bain', 'Rénovation cuisine', 'Peinture complète', 'Nouvelle toiture'])
                        : fake()->randomElement(['Fuite d\'eau', 'Prise électrique défectueuse', 'Serrures à changer', 'Chauffage en panne', 'Jardin à entretenir']),
                    'description' => fake()->sentence(10),
                    'category' => fake()->randomElement($categories),
                    'urgency' => fake()->randomElement($urgencies),
                    'status' => fake()->randomElement($statuses),
                    'cost' => fake()->numberBetween(25000, 500000),
                    'is_renovation' => $isRenovation,
                    'scheduled_at' => fake()->boolean(50) ? now()->addDays(fake()->numberBetween(1, 30)) : null,
                ]);
            }
        }

        // 4. Create inspections
        foreach ($properties->take(2) as $property) {
            $contract = Contract::where('property_id', $property->id)->first();

            Inspection::create([
                'property_id' => $property->id,
                'contract_id' => $contract?->id,
                'type' => 'check_in',
                'date' => now()->subMonths(6),
                'inspector_name' => $owner->name,
                'rooms_data' => [
                    'Salon' => ['Murs' => 'good', 'Sols' => 'clean', 'Plafond' => 'good', 'Fenêtres' => 'good'],
                    'Chambre' => ['Murs' => 'clean', 'Sols' => 'clean', 'Placard' => 'good'],
                    'Cuisine' => ['Murs' => 'good', 'Sols' => 'clean', 'Plans de travail' => 'good', 'Évier' => 'good'],
                    'Salle de bain' => ['Murs' => 'good', 'Sols' => 'good', 'Douche' => 'clean', 'WC' => 'clean'],
                ],
                'notes' => 'Bien en bon état général. Quelques petites marques sur les murs du salon.',
                'tenant_signature' => fake()->name(),
                'owner_signature' => $owner->name,
            ]);
        }

        // 5. Create sample documents
        $documentCategories = ['invoice', 'receipt', 'quote', 'other'];
        foreach ($properties->take(3) as $property) {
            for ($i = 0; $i < 3; $i++) {
                Document::create([
                    'property_id' => $property->id,
                    'name' => fake()->randomElement([
                        'Facture électricité '.fake()->monthName(),
                        'Devis rénovation cuisine',
                        'Reçu de loyer '.fake()->monthName(),
                        'Attestation d\'assurance',
                        'Diagnostic performance',
                    ]),
                    'category' => fake()->randomElement($documentCategories),
                    'file_path' => 'documents/sample/placeholder.txt',
                    'file_size' => fake()->numberBetween(10240, 512000),
                    'created_by' => $owner->id,
                ]);
            }
        }

        $this->command->info('Owner portal seeded successfully!');
    }
}
