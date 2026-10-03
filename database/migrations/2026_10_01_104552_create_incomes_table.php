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
        Schema::create('incomes', function (Blueprint $table) {
            $table->id();
            $table->string('reference_no')->unique();
            $table->string('source', 20);
            $table->string('external_order_no')->nullable();

            $table->date('income_date');
            $table->date('order_date')->nullable();
            $table->date('released_at')->nullable();

            $table->string('customer_name')->nullable();
            $table->string('description')->nullable();

            $table->decimal('gross_amount', 15, 2)->default(0);
            $table->decimal('amount', 15, 2);

            $table->string('payment_method')->nullable();
            $table->string('shipping_service')->nullable();
            $table->string('courier')->nullable();

            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['source', 'external_order_no']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incomes');
    }
};
