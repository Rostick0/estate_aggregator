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
            [
                'name' => 'Продажа'
            ],
            [
                'name' => 'Аренда'
            ]
        ];

        FlatType::insert($data);
    }
}
