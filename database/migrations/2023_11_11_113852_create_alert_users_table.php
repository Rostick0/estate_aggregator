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
        Schema::create('alert_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alert_id')->references('id')->on('alerts')->onDelete('cascade');
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->boolean('is_read')->default(0);
            $table->timestamp('send_at');
            $table->enum('status', ['active', 'hidden'])->default('hidden');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alert_users');
    }
};
