<?php

namespace Database\Factories;

use App\Utils\RandomUtil;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // 'realtor',
        $roles = ['agency', 'builder'];
        $type_socials = ['whatsapp', 'viber', 'telegram'];
        $email = fake()->unique()->safeEmail();

        return [
            'name' => fake()->name(),
            'email' => $email,
            'email_verified_at' => now(),
            'password' => Hash::make($email), // password
            'phone' => fake()->phoneNumber(),
            // 'image_id' => random_int(1, 50),
            'role' => RandomUtil::array($roles),
            'country_id' => 233,
            'is_confirm' => 1,
            'type_social' => RandomUtil::array($type_socials),
            'about' => fake()->text(random_int(200, 300)),
            'work_experience' => random_int(1, 30),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
