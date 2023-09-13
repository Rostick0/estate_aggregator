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
                'name' => 'Москва',
                'country_id' => 1
            ],
            [
                'name' => 'Санкт-Петербург',
                'country_id' => 1
            ],
            [
                'name' => 'Казань',
                'country_id' => 1
            ]
        ];

        City::insert($cities);
    }
}
