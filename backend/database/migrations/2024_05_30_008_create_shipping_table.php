<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShippingTable extends Migration
{
    public function up()
    {
        Schema::create('shipping', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('seller_id');
            $table->string('shipping_type');
            $table->decimal('shipping_cost', 8, 2);
            $table->string('shipping_status');
            $table->string('tracking_number');
            $table->string('shipping_address');
            $table->string('city');
            $table->string('state');
            $table->string('postal_code');
            $table->string('country');
            $table->string('shipping_carrier');
            $table->string('shipping_method');
            $table->string('recipient_name');
            $table->timestamp('estimated_delivery_date');
            $table->text('additional_notes')->nullable();
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('shipping');
    }
}
