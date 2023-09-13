<?php

namespace Database\Seeders;

use App\Models\Property;
use App\Models\PropertyValue;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use SimpleXMLElement;

class PropertyAndPropertyValueSeeder extends Seeder
{
    public function run(): void
    {
        $xmlData = file_get_contents(
            public_path('xmls/prian.ru_files_xml_properties.xml')
        );

        $properties = [];
        $property_values = [];

        foreach (new SimpleXMLElement($xmlData) as $item) {
            $properties[] = [
                'id' => (int) $item->property_id,
                'name' => (string) $item->name,
            ];

            if (empty($item?->property_values?->property_value)) continue;

            foreach ($item?->property_values?->property_value as $elem) {
                $property_values[] = [
                    'id' => (int) $elem->property_value_id,
                    'name' => (string) $elem->name,
                    'property_id' => (int) $item->property_id
                ];
            }
        }

        Property::insert($properties);
        PropertyValue::insert($property_values);
    }
}
