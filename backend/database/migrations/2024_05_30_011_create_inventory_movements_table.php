<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryMovementsTable extends Migration
{
    public function up()
    {
        Schema::create('inventory_movements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('inventory_id');
            $table->string('type');
            $table->integer('quantity_change');
            $table->timestamp('movement_date');
            $table->timestamps();

            $table->foreign('inventory_id')->references('id')->on('inventory')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('inventory_movements');
    }
}
