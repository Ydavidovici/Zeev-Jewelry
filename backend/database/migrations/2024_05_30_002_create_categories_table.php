<?php

// database/migrations/2024_05_30_002_create_categories_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    public function up()
    {
        /**
         * Run the migrations.
         *
         * @return void
         */
        Schema::create('categories', function (Blueprint $table) {
            $table->id('category_id');
            $table->string('category_name',255);
            $table->timestamps();

            $table->index('category_name');
        });
    }

    public function down()
    {
        /**
         * Reverse the migrations.
         *
         * @return void
         */
        Schema::dropIfExists('categories');
    }
}
