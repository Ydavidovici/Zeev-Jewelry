// database/migrations/2024_05_29_008_create_shipping_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShippingTable extends Migration
{
public function up()
{
Schema::create('shipping', function (Blueprint $table) {
$table->id('shipping_id');
$table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
$table->string('shipping_type')->nullable();
$table->decimal('shipping_cost', 10, 2)->nullable();
$table->enum('shipping_status', ['shipped', 'pending', 'delivered'])->default('pending');
$table->timestamps();
});
}

public function down()
{
Schema::dropIfExists('shipping');
}
}
