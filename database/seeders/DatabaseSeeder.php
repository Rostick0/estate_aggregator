<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Flat;
use App\Models\FlatOwner;
use App\Models\FlatProperty;
use App\Models\Image;
use App\Models\ImageRelat;
use App\Models\Post;
use App\Models\User;
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
            ->has(ImageRelat::factory(5), 'images')
            ->create();

        Flat::factory(100)
            ->has(ImageRelat::factory(5), 'images')
            ->has(FlatProperty::factory(20), 'flat_properties')
            ->create();

        User::factory(40)
            ->has(FlatOwner::factory(2), 'flat_owners')
            ->create();

        User::factory(10)->create([
            'role' => 'client'
        ]);
    }
}
