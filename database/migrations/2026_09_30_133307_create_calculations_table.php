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
        Schema::create('calculations', function (Blueprint $table) {
            $table->id();
            $table->string('reference_no')->unique();
            $table->decimal('weight', 10, 2);
            $table->unsignedInteger('hours');
            $table->unsignedInteger('minutes');
            $table->unsignedInteger('total_minutes');
            $table->decimal('ratio', 10, 4);
            $table->string('method');
            $table->decimal('base_price', 15, 2);
            $table->decimal('fee', 15, 2);
            $table->decimal('fixed_cost', 15, 2);
            $table->decimal('price_per_item', 15, 2);
            // $table->unsignedInteger('quantity')->default(1);
            $table->decimal('total_price', 15, 2);
            $table->enum('status', ['pending', 'fixed', 'cancelled'])->default('pending');
            $table->timestamp('fixed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calculations');
    }
};
