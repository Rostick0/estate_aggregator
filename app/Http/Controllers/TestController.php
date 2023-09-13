<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SimpleXMLElement;

class TestController extends Controller
{
    public function test()
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

        dd($data);
    }
}
