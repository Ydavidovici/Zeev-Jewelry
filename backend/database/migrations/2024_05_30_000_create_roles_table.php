<?php

// database/migrations/2024_05_30_000_create_roles_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id('role_id');
            $table->string('role_name', 255);
            $table->timestamps();

            $table->index('role_name');
        });
    }

    public function down()
    {
        /**
         * Reverse the migrations.
         *
         * @return void
         */
        Schema::dropIfExists('roles');
    }
}
