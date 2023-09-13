<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PropertySeeder extends Seeder
{
    public function run(): void
    {
        $xmlData = file_get_contents(
            public_path('xmls/prian.ru_files_xml_properties.xml')
        );

        $data = [];
    }
}
