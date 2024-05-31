<?php

// database/migrations/2024_05_30_011_create_inventory_movements_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryMovementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_movements', function (Blueprint $table) {
            $table->id('movement_id');
            $table->unsignedBigInteger('inventory_id');
            $table->enum('type', ['addition', 'subtraction']);
            $table->integer('quantity_change');
            $table->timestamp('movement_date')->useCurrent();
            $table->timestamps();

            $table->foreign('inventory_id')->references('inventory_id')->on('inventory')->onDelete('cascade');

            // Adding indexes to inventory_id and type for faster lookups
            $table->index('inventory_id');
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventory_movements');
    }
}
