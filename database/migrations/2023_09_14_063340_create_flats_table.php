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
            $table->foreignId('object_id')->references('id')->on('object_flats')->onDelete('cascade');
            $table->foreignId('type_id')->references('id')->on('flat_types')->onDelete('cascade');
            $table->foreignId('country_id')->nullable()->constrained();
            $table->foreignId('district_id')->references('id')->on('districts')->onDelete('cascade');
            $table->string('district')->nullable();
            $table->string('address')->nullable();
            $table->string('longitude');
            $table->string('latitude');
            $table->foreignId('currency_id')->references('id')->on('currencies')->onDelete('cascade');
            $table->float('price')->nullable();
            $table->float('price_per_meter')->nullable();
            $table->float('price_day')->nullable();
            $table->float('price_week')->nullable();
            $table->float('price_month')->nullable();
            $table->boolean('not_show_price')->nullable();
            $table->integer('rooms');
            $table->integer('bedrooms');
            $table->integer('bathrooms');
            $table->float('square');
            $table->float('square_land');
            $table->foreignId('square_land_unit')->references('id')->on('square_land_units')->onDelete('cascade');
            $table->integer('floor');
            $table->integer('total_floor');
            $table->foreignId('building_type')->references('id')->on('building_types')->onDelete('cascade');
            $table->string('building_date')->nullable();
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
