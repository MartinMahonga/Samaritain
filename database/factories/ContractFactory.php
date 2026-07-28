<?php

namespace Database\Factories;

use App\Models\Property;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContractFactory extends Factory
{
    public function definition(): array
    {
        return [
            'property_id' => Property::factory(),
            'tenant_name' => fake()->name(),
            'tenant_email' => fake()->unique()->safeEmail(),
            'tenant_phone' => fake()->phoneNumber(),
            'start_date' => fake()->date(),
            'end_date' => fake()->optional()->date(),
            'monthly_rent' => fake()->numberBetween(50000, 500000),
            'deposit' => fake()->optional()->numberBetween(50000, 500000),
            'status' => 'draft',
            'created_by' => 1,
        ];
    }
}
