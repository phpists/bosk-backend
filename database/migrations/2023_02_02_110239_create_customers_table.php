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
            $table->string('phone', 20)->nullable();
            $table->string('email')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('currency');
            $table->string('address_1');
            $table->string('address_2')->nullable();
            $table->string('city');
            $table->string('postal_code', 18);
            $table->string('country');
            $table->string('province')->nullable();
            $table->string('website')->nullable();
            $table->string('notes')->nullable();
            $table->text('footer')->nullable();
            $table->integer('VAT')->nullable();
            $table->foreignId('user_id')->constrained('users');
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
