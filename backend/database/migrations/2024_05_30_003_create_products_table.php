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
            $table->id('product_id');
            $table->string('product_name');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->unsignedBigInteger('category_id');
            $table->string('image_url')->nullable();
            $table->timestamps();

            $table->foreign('category_id')->references('category_id')->on('categories')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
}
