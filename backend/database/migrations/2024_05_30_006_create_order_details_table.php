<?php

// database/migrations/2024_05_30_006_create_order_details_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderDetailsTable extends Migration
{
    public function up()
    {
        /**
         * Run the migrations.
         *
         * @return void
         */
        Schema::create('order_details', function (Blueprint $table) {
            $table->id('order_details_id');
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('product_id');
            $table->integer('quantity');
            $table->decimal('price', 10, 2);
            $table->timestamps();

            $table->foreign('order_id')->references('order_id')->on('orders')->onDelete('cascade');
            $table->foreign('product_id')->references('product_id')->on('products')->onDelete('cascade');

            $table->index('order_id');
            $table->index('product_id');
        });
    }

    public function down()
    {
        /**
         * Reverse the migrations.
         *
         * @return void
         */
        Schema::dropIfExists('order_details');
    }
}
