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
        Schema::create('user_addresses', function (Blueprint $table) {
            $table->id();
            $table->string('type', 45);
            $table->string('address_1', 200);
            $table->string('address_2', 200)->nullable();
            $table->string('city', 200);
            $table->string('state', 200)->nullable();
            $table->string('postal_code', 45);
            $table->string('country_code', 5);
            $table->string('phone', 45);
            $table->foreignId('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_addresses');
    }
};
