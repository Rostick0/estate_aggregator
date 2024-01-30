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
                "created_at" => "2024-01-17T20:43:19.000000Z",
                "updated_at" => "2024-01-30T06:54:08.000000Z",
                "type" => null
            ],
            [
                "id" => 3,
                "title" => "Скрыть авторизацию",
                "text" => "false",
                "key" => "hide_auth",
                "created_at" => "2024-01-28T14:30:37.000000Z",
                "updated_at" => "2024-01-30T17:07:24.000000Z",
                "type" => 'checkbox'
            ],
            [
                "id" => 4,
                "title" => "Электронная почта",
                "text" => "test@email.com",
                "key" => "site_email",
                "created_at" => "2024-01-28T14:39:54.000000Z",
                "updated_at" => "2024-01-28T14:39:54.000000Z",
                "type" => null
            ],
            [
                "id" => 5,
                "title" => "Название сайте",
                "text" => "Agregator.ru",
                "key" => "site_name",
                "created_at" => "2024-01-30T06:51:50.000000Z",
                "updated_at" => "2024-01-30T06:51:50.000000Z",
                "type" => null
            ],
            [
                "id" => 6,
                "title" => "Текст главного баннера",
                "text" => "Агрегатор - лучше всех по поиску недвижимости",
                "key" => "site_bannertext",
                "created_at" => "2024-01-30T07:45:51.000000Z",
                "updated_at" => "2024-01-30T07:49:02.000000Z",
                "type" => null
            ]
        ];

        SiteInfo::insert($site_info);
    }
}
