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
        Schema::create('recruitment_flats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recruitment_id')->references('id')->on('recruitments')->onDelete('cascade');
            $table->foreignId('flat_id')->references('id')->on('flats')->onDelete('cascade');
            $table->string('comment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recruitment_flats');
    }
};
