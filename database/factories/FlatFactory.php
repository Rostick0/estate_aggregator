<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Flat>
 */
class FlatFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $coords = fake()->localCoordinates();

        return [
            'title' => fake()->word(),
            'object_id' => random_int(5, 8),
            'type_id' => random_int(1, 2),
            'country_id' => 233,
            'district_id' => random_int(9561, 9562),
            'district_string' => fake()->city(),
            'address' => fake()->address(),
            'longitude' => $coords['longitude'],
            'latitude' => $coords['latitude'],
            'currency_id' => random_int(1, 3),
            'price' => random_int(3000000, 15000000),
            'price_per_meter' => random_int(1000, 15000),
            'price_day' => random_int(4000, 15000),
            'price_week' => random_int(9000, 25000),
            'price_month' => random_int(20000, 90000),
            'not_show_price' => random_int(0, 1),
            'rooms' => random_int(1, 3),
            'bedrooms' => random_int(1, 3),
            'bathrooms' => random_int(1, 2),
            'square' => random_int(50, 120),
            'floor' => random_int(1, 5),
            'total_floor' => random_int(5, 10),
            'building_type' => random_int(117, 119),
            'building_date' => random_int(2020, 2027),
            'contact_id' => random_int(2, 30),
            'specialtxt'  => fake()->text(random_int(100, 200)),
            'description' => fake()->text(random_int(200, 400)),
        ];
    }
}
