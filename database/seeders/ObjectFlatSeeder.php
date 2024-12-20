<?php

namespace Database\Seeders;

use App\Models\ObjectFlat;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ObjectFlatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $objects = [
            [
                'id' => 1,
                'name' => 'апартаменты',
                'type' => 'квартира'
            ],
            [
                'id' => 2,
                'name' => 'таунхаус',
                'type' => 'квартира'
            ], [
                'id' => 11,
                'name' => 'таунхаус',
                'type' => 'квартира'
            ], [
                'id' => 14,
                'name' => 'пентхаус',
                'type' => 'квартира'
            ], [
                'id' => 20,
                'name' => 'мезонет',
                'type' => 'квартира'
            ], [
                'id' => 21,
                'name' => 'студия',
                'type' => 'квартира'
            ], [
                'id' => 22,
                'name' => 'лофт',
                'type' => 'квартира'
            ], [
                'id' => 5,
                'name' => 'кафе, ресторан',
                'type' => 'коммерческая недвижимост'
            ], [
                'id' => 6,
                'name' => 'магазин',
                'type' => 'коммерческая недвижимост'
            ], [
                'id' => 7,
                'name' => 'офис',
                'type' => 'коммерческая недвижимост'
            ], [
                'id' => 8,
                'name' => 'производство',
                'type' => 'коммерческая недвижимост'
            ], [
                'id' => 12,
                'name' => 'отель, гостиница',
                'type' => 'коммерческая недвижимост'
            ], [
                'id' => 13,
                'name' => 'иная коммерческая недвижимость',
                'type' => 'коммерческая недвижимост'
            ], [
                'id' => 18,
                'name' => 'доходный дом',
                'type' => 'коммерческая недвижимост'
            ], [
                'id' => 34,
                'name' => 'инвестиционный проект',
                'type' => 'коммерческая недвижимост'
            ], [
                'id' => 10,
                'name' => 'земельный участок',
                'type' => 'земля'
            ], [
                'id' => 19,
                'name' => 'частный остров',
                'type' => 'земля'
            ], [
                'id' => 3,
                'name' => 'дом',
                'type' => 'дом'
            ], [
                'id' => 24,
                'name' => 'коттедж',
                'type' => 'дом'
            ], [
                'id' => 25,
                'name' => 'вилла',
                'type' => 'дом'
            ], [
                'id' => 26,
                'name' => 'шале',
                'type' => 'дом'
            ], [
                'id' => 27,
                'name' => 'бунгало',
                'type' => 'дом'
            ], [
                'id' => 28,
                'name' => 'поместье',
                'type' => 'дом'
            ], [
                'id' => 29,
                'name' => 'замок',
                'type' => 'дом'
            ], [
                'id' => 30,
                'name' => 'ферма',
                'type' => 'дом'
            ], [
                'id' => 32,
                'name' => 'особняк',
                'type' => 'дом'
            ], [
                'id' => 33,
                'name' => 'дом под реконструкцию',
                'type' => 'дом'
            ],
        ];

        ObjectFlat::insert($objects);
    }
}
