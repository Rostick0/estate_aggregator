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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('email')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('name');
            $table->string('phone')->unique();
            $table->string('avatar')->nullable();
            $table->enum('role', ['client', 'realtor', 'agency', 'builder', 'admin'])->default('client');
            $table->integer('country_id')->nullable();
            $table->boolean('is_confirm')->default(false);
            $table->enum('type_social', ['whatsapp', 'viber', 'telegram'])->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
