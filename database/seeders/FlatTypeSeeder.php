<?php

namespace Database\Seeders;

use App\Models\FlatType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FlatTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [ // id 1
                'name' => 'Продажа'
            ],
            [ // id 2
                'name' => 'Аренда'
            ]
        ];

        FlatType::insert($data);
    }
}
