<?php

use App\Models\Order;
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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->references('id')->on('users');
            $table->foreignId('coupon_id')->references('id')->on('coupons')->nullable();
            $table->string('order_number');
            $table->decimal('total_amount');
            $table->string('payment_method');
            $table->string('address');
            $table->enum('status', [Order::PENDING_ORDER, Order::COMPLETED_ORDER, Order::CANCELED_ORDER])->default(Order::PENDING_ORDER);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
