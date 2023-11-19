<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Image>
 */
class ImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $random_width = random_int(200, 800);
        $random_height = random_int(200, 800);

        return [
            'name' => fake()->lastName() . '.png',
            'width' => $random_width,
            'height' => $random_height,
            'path' => fake()->imageUrl($random_width, $random_height),
            'type' => 'image/png',
            'user_id' => 1,
        ];
    }
}
