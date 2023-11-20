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
        Schema::create('col_relats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('collection_id')->references('id')->on('collections')->onDelete('cascade');
            $table->morphs('col_relatsable');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('col_relats');
    }
};
