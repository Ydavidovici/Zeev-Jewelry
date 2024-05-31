<?php

// database/migrations/2024_05_30_010_create_inventory_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryTable extends Migration
{
    public function up()
    {
        /**
         * Reverse the migrations.
         *
         * @return void
         */
        Schema::create('inventory', function (Blueprint $table) {
            $table->id('inventory_id');
            $table->unsignedBigInteger('product_id');
            $table->integer('quantity');
            $table->string('location',255);
            $table->timestamps();

            $table->foreign('product_id')->references('product_id')->on('products')->onDelete('cascade');

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
        Schema::dropIfExists('inventory');
    }
}
