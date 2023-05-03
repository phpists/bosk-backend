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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string("name")->nullable();
            $table->string("number");
            $table->string("provider_name")->nullable();
            $table->integer("provider_tax_id")->nullable();
            $table->string("provider_lines")->nullable();
            $table->date("issue_date");
            $table->date("due_date");
            $table->string("po_number")->nullable();
            $table->float("subtotal")->default(0);
            $table->float("tax")->default(0);
            $table->float("total")->default(0);
            $table->text("note")->nullable();
            $table->enum("status", ['draft', 'paid', 'unpaid', 'overdue', 'canceled']);
            $table->foreignId("customer_id")->constrained("customers");
            $table->foreignId('user_id')->constrained('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
