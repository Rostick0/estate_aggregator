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
        Schema::create('application_flats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('flat_id')->references('id')->on('flats')->onDelete('cascade');
            $table->boolean('is_information')->default(0);
            $table->boolean('is_viewing')->default(0);
            $table->string('name');
            $table->string('phone');
            $table->string('email')->nullable();
            $table->text('text');
            $table->string('messager_type')->nullable(); // telegram | whatsapp | viber
            $table->integer('status_id')->default(6);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_flats');
    }
};
