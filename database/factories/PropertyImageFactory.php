<?php

namespace Database\Factories;

use App\Models\PropertyImage;
use Illuminate\Database\Eloquent\Factories\Factory;

class PropertyImageFactory extends Factory
{
    protected $model = PropertyImage::class;

    public function definition(): array
    {
        return [
            'image_url' => 'properties/'.$this->faker->randomNumber().'/'.$this->faker->slug().'.jpg',
            'cover_image' => false,
        ];
    }
}
