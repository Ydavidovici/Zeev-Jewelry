// database/migrations/2024_05_29_000_create_roles_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolesTable extends Migration
{
public function up()
{
Schema::create('roles', function (Blueprint $table) {
$table->id('role_id');
$table->string('role_name');
$table->timestamps();
});
}

public function down()
{
Schema::dropIfExists('roles');
}
}
