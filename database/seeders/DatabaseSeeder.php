<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Alert;
use App\Models\ColRelat;
use App\Models\Company;
use App\Models\Flat;
use App\Models\FlatOwner;
use App\Models\FlatProperty;
use App\Models\Image;
use App\Models\ImageRelat;
use App\Models\MainBanner;
use App\Models\Post;
use App\Models\Recruitment;
use App\Models\RecruitmentFlat;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

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
            CollectionSeeder::class,
            ImageSeeder::class,
        ]);

        Image::factory(50)->create();

        Post::factory(10)
            ->has(ImageRelat::factory(5), 'images')
            ->create();

        User::factory()
            // ->has(FlatOwner::factory(2), 'flat_owners')
            // ->has(ColRelat::factory(1, [
            //     'collection_id' => 1
            // ]), 'collection_relats')
            ->create([
                'email' => 'adel@gmail.com',
                'password' => Hash::make('adel@gmail.com'),
                'role' => 'agency',
                'company_id' => 1
            ]);

        User::factory()
            // ->has(FlatOwner::factory(2), 'flat_owners')
            // ->has(ColRelat::factory(1, [
            //     'collection_id' => 1
            // ]), 'collection_relats')
            ->create([
                'email' => 'gena@gmail.com',
                'password' => Hash::make('gena@gmail.com'),
                'role' => 'builder',
                'company_id' => 2
            ]);

        User::factory()
            // ->has(FlatOwner::factory(2), 'flat_owners')
            // ->has(ColRelat::factory(1, [
            //     'collection_id' => 1
            // ]), 'collection_relats')
            ->create([
                'email' => 'dasha@gmail.com',
                'password' => Hash::make('dasha@gmail.com'),
                'role' => 'realtor',
            ]);

        User::factory()
            // ->has(FlatOwner::factory(2), 'flat_owners')
            // ->has(ColRelat::factory(1, [
            //     'collection_id' => 1
            // ]), 'collection_relats')
            ->create([
                'email' => 'alena@gmail.com',
                'password' => Hash::make('alena@gmail.com'),
                'role' => 'client',
                'work_experience' => null
            ]);

        Company::factory(10)
            ->has(
                User::factory(3, [
                    'role' => 'realtor'
                ]),
                'staffs'
            )
            ->create();

        for ($i = 3; $i <= 10; $i++) {
            User::factory()
                // ->has(FlatOwner::factory(2), 'flat_owners')
                ->has(ColRelat::factory(1, [
                    'collection_id' => 1
                ]), 'collection_relats')->create([
                    'company_id' => $i
                ]);
        }

        Flat::factory(100)
            ->has(ImageRelat::factory(5), 'images')
            ->has(FlatProperty::factory(5), 'flat_properties')
            ->create();


        Recruitment::factory(10)
            ->has(RecruitmentFlat::factory(3), 'recruitment_flats')
            ->create();

        User::factory(10)
            ->has(ColRelat::factory(1, [
                'collection_id' => 1
            ]), 'collection_relats')
            ->create([
                'role' => 'client',
                'work_experience' => null
            ]);

        Alert::factory(30)->create();

        MainBanner::factory(10)->create();
    }
}
