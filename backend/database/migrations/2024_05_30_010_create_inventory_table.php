// database/migrations/2024_05_29_010_create_inventory_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryTable extends Migration
{
public function up()
{
Schema::create('inventory', function (Blueprint $table) {
$table->id('inventory_id');
$table->foreignId('product_id')->constrained('products')->onDelete('cascade');
$table->integer('quantity');
$table->string('location')->nullable();
$table->timestamps();
});
}

public function down()
{
Schema::dropIfExists('inventory');
}
}
