<?php

namespace Database\Factories;

use App\Models\Contract;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContractSignatureFactory extends Factory
{
    public function definition(): array
    {
        return [
            'contract_id' => Contract::factory(),
            'user_id' => User::factory(),
            'role' => fake()->randomElement(['owner', 'tenant']),
            'signed_at' => now(),
            'ip_address' => fake()->ipv4(),
            'user_agent' => fake()->userAgent(),
            'signature_image' => 'contracts/signatures/sig_'.fake()->uuid().'.png',
            'signature_hash' => hash('sha256', fake()->text()),
            'contract_hash' => hash('sha256', fake()->text()),
            'contract_version' => '1',
        ];
    }

    public function owner(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'owner',
        ]);
    }

    public function tenant(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'tenant',
        ]);
    }
}
