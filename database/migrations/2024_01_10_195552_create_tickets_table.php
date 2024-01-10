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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('email')->nullable();
            $table->string('phone');
            $table->string('full_name')->nullable();
            $table->text('text')->nullable();
            $table->string('communiction_method')->nullable();
            $table->string('purpose')->nullable();
            $table->string('link_from')->nullable();
            $table->foreignId('ticket_type_cid')->references('id')->on('collections')->onDelete('cascade');
            $table->integer('status_cid')->default(6);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
