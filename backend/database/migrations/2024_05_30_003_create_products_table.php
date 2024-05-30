// database/migrations/2024_05_29_003_create_products_table.php
<?php

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
$table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
$table->string('image_url')->nullable();
$table->timestamps();
});
}

public function down()
{
Schema::dropIfExists('products');
}
}
