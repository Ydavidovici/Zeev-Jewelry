<?php

// database/migrations/2024_05_30_007_create_payments_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id('payment_id');
            $table->unsignedBigInteger('order_id');
            $table->string('payment_type');
            $table->enum('payment_status', ['processed', 'failed', 'pending'])->default('pending');
            $table->timestamps();

            $table->foreign('order_id')->references('order_id')->on('orders')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
