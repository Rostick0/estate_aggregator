<?php

namespace Database\Factories;

use App\Models\District;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->name(),
            'content' => fake()->text(random_int(150, 300)),
            'main_image_id' => random_int(1, 50),
            'user_id' => 1,
            'district_id' => random_int(1702, 1711),
            'rubric_id' => random_int(1, 4),
            'source' => fake()->url(),
            'count_view' => random_int(0, 5000),
        ];
    }
}
