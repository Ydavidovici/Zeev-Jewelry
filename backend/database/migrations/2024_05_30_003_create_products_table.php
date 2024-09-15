<?php

// database/migrations/2024_05_30_003_create_products_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('seller_id');
            $table->string('name');
            $table->text('description');
            $table->decimal('price', 8, 2);
            $table->string('image_url')->default('path/to/default-image.jpg');
            $table->integer('stock_quantity')->default(0);
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('seller_id')->references('id')->on('users')->onDelete('cascade'); // Add this line
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
}
