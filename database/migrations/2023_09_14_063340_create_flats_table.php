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
            $table->string('title');
            $table->foreignId('object_id')->references('id')->on('object_flats')->onDelete('cascade');
            $table->foreignId('type_id')->references('id')->on('flat_types')->onDelete('cascade');
            $table->integer('country_id')->nullable();
            $table->foreignId('district_id')->references('id')->on('districts')->onDelete('cascade');
            $table->string('district_string')->nullable();
            $table->string('address')->nullable();
            $table->string('longitude')->nullable();
            $table->string('latitude')->nullable();
            $table->foreignId('currency_id')->references('id')->on('currencies')->onDelete('cascade');
            $table->decimal('price', 14);
            $table->float('price_per_meter')->nullable();
            $table->decimal('price_day', 12)->nullable();
            $table->decimal('price_week', 12)->nullable();
            $table->decimal('price_month', 12)->nullable();
            $table->boolean('not_show_price')->nullable();
            $table->integer('rooms')->nullable();
            $table->integer('bedrooms')->nullable();
            $table->integer('bathrooms')->nullable();
            $table->float('square')->nullable();
            $table->float('square_land')->nullable();
            $table->integer('square_land_unit')->nullable();
            $table->integer('floor')->nullable();
            $table->integer('total_floor')->nullable();
            $table->foreignId('building_type')->references('id')->on('building_types')->onDelete('cascade');
            $table->string('building_date')->nullable();
            $table->foreignId('contact_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('specialtxt')->nullable();
            $table->text('description')->nullable();
            $table->string('filename')->nullable();
            $table->string('tour_link')->nullable();
            $table->enum('status', ['active', 'archive']);
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
