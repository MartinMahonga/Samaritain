<?php

namespace Database\Factories;

use App\Models\Property;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PropertyFactory extends Factory
{
    protected $model = Property::class;

    public function definition(): array
    {
        return [
            'created_by' => User::factory(),
            'title' => $this->faker->words(3, true),
            'description' => $this->faker->paragraph(),
            'price' => $this->faker->numberBetween(50000, 500000),
            'surface' => $this->faker->numberBetween(20, 300),
            'rooms' => $this->faker->numberBetween(1, 6),
            'bedrooms' => $this->faker->numberBetween(0, 5),
            'floor' => $this->faker->numberBetween(0, 10),
            'furnished' => $this->faker->boolean(),
            'address' => $this->faker->address(),
            'status' => 'available',
            'verified' => $this->faker->boolean(),
        ];
    }
}
