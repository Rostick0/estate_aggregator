<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Flat;
use App\Models\Image;
use App\Models\ImageRelat;
use App\Models\Post;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            CountrySeeder::class,
            RegionSeeder::class,
            DistrictSeeder::class,
            RubricSeeder::class,
            UserSeeder::class,
            FlatTypeSeeder::class,
            ObjectFlatSeeder::class,
            CurrencySeeder::class,
            BuildingTypeSeeder::class,
            SquareLandUnitSeeder::class,
            PropertyAndPropertyValueSeeder::class,
        ]);

        Image::factory(50)->create();

        Post::factory(10)
            ->has(ImageRelat::factory(2), 'images')
            ->create();

        Flat::factory(50)
            ->has(ImageRelat::factory(2), 'images')
            ->create();
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
