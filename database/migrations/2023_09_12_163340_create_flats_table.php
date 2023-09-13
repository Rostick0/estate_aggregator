<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('flats', function (Blueprint $table) {
            $table->id();
            $table->integer('object_id');
            $table->integer('type_id');
            $table->integer('country_id');
            $table->integer('district_id');
            $table->string('district')->nullable();
            $table->string('address');
            $table->string('longitude');
            $table->string('latitude');
            $table->integer('currency_id');
            $table->float('price');
            $table->float('price_per_meter');
            $table->float('price_day');
            $table->float('price_week');
            $table->float('price_month');
            $table->boolean('not_show_price');
            $table->integer('rooms');
            $table->integer('bedrooms');
            $table->integer('bathrooms');
            $table->float('square');
            $table->float('square_land');
            $table->float('square_land_unit');
            $table->integer('floor');
            $table->integer('total_floor');
            $table->integer('building_type');
            $table->string('building_date');
            $table->foreignId('contact_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('specialtxt')->nullable();
            $table->text('description')->nullable();
            $table->string('filename')->nullable();
            $table->string('tour_link')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flats');
    }
};
