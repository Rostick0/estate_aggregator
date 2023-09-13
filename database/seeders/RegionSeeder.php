<?php

namespace Database\Seeders;

use App\Models\Region;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use SimpleXMLElement;

class RegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $xmlData = file_get_contents(
            public_path('xmls/prian.ru_files_xml_regions.xml')
        );

        $data = [];

        foreach (new SimpleXMLElement($xmlData) as $item) {
            $data[] = [
                'id' => $item?->region_id,
                'name' => $item?->name_rus,
                'country_id' => $item?->country_id
            ];
        }

        Region::insert($data);
    }
}
