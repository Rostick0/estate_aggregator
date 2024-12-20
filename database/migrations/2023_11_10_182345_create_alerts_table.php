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
        Schema::create('alerts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->integer('country_id')->nullable();
            $table->integer('recipient_id')->nullable();
            $table->enum('role', ['client', 'realtor', 'agency', 'builder'])->nullable();
            $table->enum('type', ['system', 'flat', 'recruitment', 'news'])->default('news');
            $table->enum('status', ['new', 'archive', 'processing', 'sended'])->default('processing');
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alerts');
    }
};
