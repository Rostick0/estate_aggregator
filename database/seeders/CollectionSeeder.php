<?php

namespace Database\Seeders;

use App\Models\Collection;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CollectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $collections = [
            [
                'value' => 'Русский',
                'type' => 'language',
            ],
            [
                'value' => 'Английский',
                'type' => 'language',
            ],
            [
                'value' => 'Татарский',
                'type' => 'language',
            ]
        ];

        Collection::insert($collections);
    }
}
