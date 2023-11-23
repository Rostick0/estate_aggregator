<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [ // id 1
                'name' => 'USD'
            ],
            [ // id 2
                'name' => 'EUR'
            ],
            [ // id 3
                'name' => 'RUR' // рубль
            ]
        ];

        Currency::insert($data);
    }
}
