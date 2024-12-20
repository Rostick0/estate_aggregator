<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use SimpleXMLElement;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $xmlData = file_get_contents(
            public_path('xmls/prian.ru_files_xml_countries.xml')
        );

        $data = [];

        foreach(new SimpleXMLElement($xmlData) as $item) {
            $data[] = [
                'id' => $item?->country_id,
                'name' => $item?->name_rus
            ];
        }

        Country::insert($data);
    }
}
