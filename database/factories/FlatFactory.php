<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Flat>
 */
class FlatFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // $table->string('title');
            // $table->foreignId('object_id')->references('id')->on('object_flats')->onDelete('cascade');
            // $table->foreignId('type_id')->references('id')->on('flat_types')->onDelete('cascade');
            // $table->integer('country_id')->nullable();
            // $table->foreignId('district_id')->references('id')->on('districts')->onDelete('cascade');
            // $table->string('district')->nullable();
            // $table->string('address')->nullable();
            // $table->string('longitude')->nullable();
            // $table->string('latitude')->nullable();
            // $table->foreignId('currency_id')->references('id')->on('currencies')->onDelete('cascade');
            // $table->decimal('price', 10);
            // $table->float('price_per_meter')->nullable();
            // $table->float('price_day')->nullable();
            // $table->float('price_week')->nullable();
            // $table->float('price_month')->nullable();
            // $table->boolean('not_show_price')->nullable();
            // $table->integer('rooms')->nullable();
            // $table->integer('bedrooms')->nullable();
            // $table->integer('bathrooms')->nullable();
            // $table->float('square')->nullable();
            // $table->float('square_land')->nullable();
            // $table->integer('square_land_unit')->nullable();
            // $table->integer('floor')->nullable();
            // $table->integer('total_floor')->nullable();
            // $table->foreignId('building_type')->references('id')->on('building_types')->onDelete('cascade');
            // $table->string('building_date')->nullable();
            // $table->foreignId('contact_id')->references('id')->on('users')->onDelete('cascade');
            // $table->string('specialtxt')->nullable();
            // $table->text('description')->nullable();
            // $table->string('filename')->nullable();
            // $table->string('tour_link')->nullable();
        ];
    }
}
