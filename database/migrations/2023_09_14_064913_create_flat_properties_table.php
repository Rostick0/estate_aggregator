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
        Schema::create('flat_properties', function (Blueprint $table) {
            $table->id();
            $table->string('value_enum')->nullable();
            $table->string('value')->nullable();
            $table->foreignId('flat_id')->references('id')->on('flats')->onDelete('cascade');
            $table->integer('property_id')->nullable();
            $table->integer('property_value_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flat_properties');
    }
};
