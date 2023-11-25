<?php

namespace Database\Factories;

use App\Utils\RandomUtil;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FlatProperty>
 */
class FlatPropertyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $random_filter = random_int(1, 4);

        if ($random_filter == 1) { // input
            $type_ids = [190, 169, 170, 171, 173, 198, 177, 211];

            return [
                'value' => random_int(1, 200),
                'property_id' =>  RandomUtil::array($type_ids),
            ];
        } else if ($random_filter == 2) { // checkbox
            $type_ids = [171, 95, 142, 172, 173, 175, 177, 211, 160, 140, 139, 78, 79, 80, 81, 82, 83];

            return [
                'property_value_id' => RandomUtil::array($type_ids),
            ];
        } else if ($random_filter == 3) { // select with value
            $type_ids = [1, 2, 3, 5, 6, 7, 10, 13, 14, 15, 16, 17, 18, 19, 20, 21];

            return [
                'value' => random_int(100, 3000),
                'property_value_id' => RandomUtil::array($type_ids),
            ];
        }

        $type_ids = [40, 41, 42, 43, 44, 45, 46, 48, 49, 51, 52, 53, 55, 56, 58, 59, 60, 61, 63, 64, 65, 153, 154, 155, 67, 68, 156, 70, 71, 72, 73, 75, 76, 97, 98, 99, 100, 102, 103, 104, 145, 113, 114, 115];

        // select
        return [
            'value' => random_int(100, 3000),
            'property_value_id' => RandomUtil::array($type_ids),
        ];
    }
}
