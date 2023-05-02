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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('client_name');
            $table->string('phone', 20);
            $table->string('email');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('currency');
            $table->string('address_1');
            $table->string('address_2');
            $table->string('city');
            $table->string('postal_code', 18);
            $table->string('country');
            $table->string('province');
            $table->string('website');
            $table->string('notes');
            $table->foreignId('user_id')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
