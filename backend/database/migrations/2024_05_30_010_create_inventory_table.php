<?php

// database/migrations/2024_05_30_010_create_inventory_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryTable extends Migration
{
    public function up()
    {
        Schema::create('inventory', function (Blueprint $table) {
            $table->id('inventory_id');
            $table->unsignedBigInteger('product_id');
            $table->integer('quantity');
            $table->string('location');
            $table->timestamps();

            $table->foreign('product_id')->references('product_id')->on('products')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('inventory');
    }
}
