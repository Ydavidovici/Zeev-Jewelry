// database/migrations/2024_05_29_005_create_orders_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
public function up()
{
Schema::create('orders', function (Blueprint $table) {
$table->id('order_id');
$table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
$table->dateTime('order_date')->default(DB::raw('CURRENT_TIMESTAMP'));
$table->decimal('total_amount', 10, 2);
$table->boolean('is_guest')->default(false);
$table->enum('status', ['pending', 'completed', 'shipped', 'canceled'])->default('pending');
$table->timestamps();
});
}

public function down()
{
Schema::dropIfExists('orders');
}
}
