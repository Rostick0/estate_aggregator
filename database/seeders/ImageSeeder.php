<?php

namespace Database\Seeders;

use App\Models\Image;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $images = [
            [
                'name' => 'Default image',
                'width' => 2600,
                'height' => 2600,
                'path' => 'http://92.63.179.235/storage/upload/image/default.jpg',
                'type' => 'image/jpg',
                'user_id' => 1,
            ]
        ];

        Image::insert($images);
    }
}
