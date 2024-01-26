<?php

namespace Database\Seeders;

use App\Models\SitePage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SitePageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'title' => 'Главная',
                'path' => 'index',
            ],
            [
                'title' => 'Новости',
                'path' => 'blog',
            ],
            [
                'title' => 'Новость',
                'path' => 'blog-id',
            ],
            [
                'title' => 'Все объявления',
                'path' => 'products',
            ],
            [
                'title' => 'Конкретное объявление',
                'path' => 'products-id',
            ]
        ];

        SitePage::insert($data);
    }
}
