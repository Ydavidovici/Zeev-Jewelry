<?php

// database/migrations/2024_05_29_008_create_shipping_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShippingTable extends Migration
{
    public function up()
    {
        Schema::create('shipping', function (Blueprint $table) {
            $table->id('shipping_id');
            $table->unsignedBigInteger('order_id');
            $table->string('shipping_type');
            $table->decimal('shipping_cost', 10, 2);
            $table->enum('shipping_status', ['shipped', 'pending', 'delivered'])->default('pending');
            $table->timestamps();

            $table->foreign('order_id')->references('order_id')->on('orders')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('shipping');
    }
}
