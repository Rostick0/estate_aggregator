<?php

namespace Database\Seeders;

use App\Models\SquareLandUnit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SquareLandUnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'id' => 129,
                'name' => 'кв.м.'
            ],
            [
                'id' => 128,
                'name' => 'сотки'
            ],
            [
                'id' => 130,
                'name' => 'гектары'
            ],
            [
                'id' => 131,
                'name' => 'акры'
            ],
        ];

        SquareLandUnit::insert($data);
    }
}
