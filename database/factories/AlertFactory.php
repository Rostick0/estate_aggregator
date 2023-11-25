<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Alert>
 */
class AlertFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $roles = [null, 'client', 'realtor', 'agency', 'builder'];

        return [
            'title' => fake()->name(),
            'description' => fake()->text(random_int(200, 300)),
            'country_id' => 233,
            'role' => $roles[random_int(0, 4)],
            // 'type',
            'status' => 'active',
            'user_id' => random_int(6, 30),
        ];
    }
}
