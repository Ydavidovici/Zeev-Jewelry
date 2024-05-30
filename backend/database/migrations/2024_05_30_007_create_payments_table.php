// database/migrations/2024_05_29_007_create_payments_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
public function up()
{
Schema::create('payments', function (Blueprint $table) {
$table->id('payment_id');
$table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
$table->string('payment_type');
$table->enum('payment_status', ['processed', 'failed', 'pending'])->default('pending');
$table->timestamps();
});
}

public function down()
{
Schema::dropIfExists('payments');
}
}
