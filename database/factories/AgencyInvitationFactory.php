<?php

namespace Database\Factories;

use App\Models\AgencyInvitation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class AgencyInvitationFactory extends Factory
{
    protected $model = AgencyInvitation::class;

    public function definition(): array
    {
        $role = Role::where('name', '!=', 'owner')->first();

        return [
            'email' => $this->faker->safeEmail(),
            'role_id' => $role?->id ?? 1,
            'token' => Str::random(64),
            'expires_at' => now()->addDays(7),
            'created_by' => User::factory(),
            'accepted_at' => null,
            'cancelled_at' => null,
        ];
    }

    public function expired(): static
    {
        return $this->state(fn (array $attributes) => [
            'expires_at' => now()->subDay(),
        ]);
    }

    public function accepted(): static
    {
        return $this->state(fn (array $attributes) => [
            'accepted_at' => now(),
        ]);
    }

    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'cancelled_at' => now(),
        ]);
    }
}
