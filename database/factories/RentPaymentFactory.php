<?php

namespace Database\Factories;

use App\Models\Contract;
use App\Models\RentPayment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RentPayment>
 */
class RentPaymentFactory extends Factory
{
    protected $model = RentPayment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'contract_id' => Contract::factory(),
            'month' => fake()->numberBetween(1, 12),
            'year' => now()->year,
            'amount_due' => fake()->numberBetween(50000, 500000),
            'amount_paid' => 0,
            'due_date' => fake()->date(),
            'status' => 'unpaid',
        ];
    }

    /**
     * Mark the rent payment as already paid.
     */
    public function paid(): static
    {
        return $this->state(fn () => [
            'status' => 'paid',
            'amount_paid' => $this->faker->numberBetween(50000, 500000),
            'paid_at' => now(),
        ]);
    }
}
