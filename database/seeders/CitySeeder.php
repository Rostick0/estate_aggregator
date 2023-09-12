<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cities = [
            [
                'name' => 'Москва'
            ],
            [
                'name' => 'Санкт-Петербург'
            ],
            [
                'name' => 'Казань'
            ]
        ];

        foreach ($cities as $city) {
            City::firstOrCreate($city);
        }
    }
}
