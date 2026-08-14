<?php

namespace Database\Factories;

use App\Models\RentPayment;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Transaction>
 */
class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'visit_pass_id' => null,
            'rent_payment_id' => null,
            'status' => 'pending',
            'amount' => fake()->numberBetween(1000, 10000),
            'deposit_id' => null,
            'provider' => 'MTN_MOMO_COG',
            'currency' => 'XAF',
            'raw_response' => null,
        ];
    }

    /**
     * Link the transaction to a rent payment.
     */
    public function forRentPayment(): static
    {
        return $this->state(fn () => [
            'rent_payment_id' => RentPayment::factory(),
        ]);
    }
}
