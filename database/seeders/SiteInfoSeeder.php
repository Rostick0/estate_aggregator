<?php

namespace Database\Seeders;

use App\Models\SiteInfo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SiteInfoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $site_info = [
            [
                "id" => 1,
                "title" => "Номер телефона",
                "text" => "",
                "key" => "site_phone",
                "type" => null
            ],
            [
                "id" => 3,
                "title" => "Скрыть авторизацию",
                "text" => "false",
                "key" => "hide_auth",
                "type" => 'checkbox'
            ],
            [
                "id" => 4,
                "title" => "Электронная почта",
                "text" => "",
                "key" => "site_email",
                "type" => null
            ],
            [
                "id" => 5,
                "title" => "Название сайте",
                "text" => "",
                "key" => "site_name",
                "type" => null
            ],
            [
                "id" => 6,
                "title" => "Текст главного баннера",
                "text" => "",
                "key" => "site_bannertext",
                "type" => null
            ]
        ];

        SiteInfo::insert($site_info);
    }
}
