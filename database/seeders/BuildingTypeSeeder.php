<?php

namespace Database\Seeders;

use App\Models\BuildingType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BuildingTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'id' => 117,
                'name' => 'Новый дом'
            ],
            [
                'id' => 118,
                'name' => 'Строящийся объект'
            ],
            [
                'id' => 119,
                'name' => 'Вторичная недвижимость'
            ],
        ];

        BuildingType::insert($data);
    }
}
