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
        Schema::create('accepted_orders', function (Blueprint $table) {
            $table->id();
            $table->integer('userOrderNumber')->unique(); // Unique order number
            $table->integer('userID'); // User's name
            $table->decimal('userRequiredAmount', 10, 2); // Required amount with 2 decimal precision
            $table->decimal('userPercentageAcceptance', 5, 2); // Percentage acceptance (e.g., 95.00%)
            $table->enum('userOrderStatus', ['معلق', 'مقبول', 'مرفوض'])->default('مقبول'); // Order status
            $table->dateTime('userOrderDate'); // Date of the order
            $table->boolean('userTransferOrder')->default(false); // Indicates if the order is transferred 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accepted_orders');
    }
};
