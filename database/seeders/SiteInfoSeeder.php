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
                "text" => "+ 7 (800) 555-35-35",
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
                "text" => "test@email.com",
                "key" => "site_email",
                "type" => null
            ],
            [
                "id" => 5,
                "title" => "Название сайте",
                "text" => "Agregator.ru",
                "key" => "site_name",
                "type" => null
            ],
            [
                "id" => 6,
                "title" => "Текст главного баннера",
                "text" => "Агрегатор - лучше всех по поиску недвижимости",
                "key" => "site_bannertext",
                "type" => null
            ]
        ];

        SiteInfo::insert($site_info);
    }
}
