// database/migrations/2024_05_29_011_create_inventory_movements_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryMovementsTable extends Migration
{
public function up()
{
Schema::create('inventory_movements', function (Blueprint $table) {
$table->id('movement_id');
$table->foreignId('inventory_id')->constrained('inventory')->onDelete('cascade');
$table->enum('type', ['addition', 'subtraction']);
$table->integer('quantity_change');
$table->dateTime('movement_date')->default(DB::raw('CURRENT_TIMESTAMP'));
$table->timestamps();
});
}

public function down()
{
Schema::dropIfExists('inventory_movements');
}
}
