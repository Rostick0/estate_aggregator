<?php

namespace Database\Seeders;

use App\Models\Rubric;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RubricSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rubrics = [
            [
                'name' => 'Инвестиции',
            ],
            [
                'name' => 'Рынки и цены',
            ],
            [
                'name' => 'Стиль жизни',
            ],
            [
                'name' => 'Туризм',
            ],
        ];

        Rubric::insert($rubrics);
    }
}
