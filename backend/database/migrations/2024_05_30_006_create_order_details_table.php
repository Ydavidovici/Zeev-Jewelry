// database/migrations/2024_05_29_006_create_order_details_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderDetailsTable extends Migration
{
public function up()
{
Schema::create('order_details', function (Blueprint $table) {
$table->id('order_details_id');
$table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
$table->foreignId('product_id')->constrained('products')->onDelete('cascade');
$table->integer('quantity');
$table->decimal('price', 10, 2);
$table->timestamps();
});
}

public function down()
{
Schema::dropIfExists('order_details');
}
}
