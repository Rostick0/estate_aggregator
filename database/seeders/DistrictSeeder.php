<?php

namespace Database\Seeders;

use App\Models\District;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use SimpleXMLElement;

class DistrictSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $xmlData = file_get_contents(
            public_path('xmls/prian.ru_files_xml_districts.xml')
        );

        $data = [];

        foreach(new SimpleXMLElement($xmlData) as $item) {
            $data[] = [
                'id' => $item?->district_id,
                'name' => $item?->name_rus,
                'region_id' => $item?->region_id
            ];
        }

        District::insert($data);
    }
}
