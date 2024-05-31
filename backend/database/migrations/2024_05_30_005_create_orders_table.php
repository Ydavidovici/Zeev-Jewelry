<?php

// database/migrations/2024_05_30_005_create_orders_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    public function up()
    {
        /**
         * Run the migrations.
         *
         * @return void
         */
        Schema::create('orders', function (Blueprint $table) {
            $table->id('order_id');
            $table->unsignedBigInteger('customer_id');
            $table->timestamp('order_date')->useCurrent();
            $table->decimal('total_amount', 10, 2);
            $table->boolean('is_guest')->default(false);
            $table->enum('status', ['pending', 'completed', 'shipped', 'canceled'])->default('pending');
            $table->timestamps();

            $table->foreign('customer_id')->references('customer_id')->on('customers')->onDelete('cascade');

            $table->index('customer_id');
            $table->index('status');
        });
    }

    public function down()
    {
        /**
         * Reverse the migrations.
         *
         * @return void
         */
        Schema::dropIfExists('orders');
    }
}
