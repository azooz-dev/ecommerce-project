<?php

use App\Models\Product;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('quantity')->unsigned();
            $table->decimal('price', 10, 2);
            $table->decimal('price_discount', 10, 2)->nullable();
            $table->foreignId('category_id')->references('id')->on('categories');
            $table->enum('status', [Product::AVAILABLE_PRODUCT, Product::UNAVAILABLE_PRODUCT])->default(Product::UNAVAILABLE_PRODUCT);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
