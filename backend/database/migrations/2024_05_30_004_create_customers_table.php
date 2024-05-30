// database/migrations/2024_05_29_004_create_customers_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
public function up()
{
Schema::create('customers', function (Blueprint $table) {
$table->id('customer_id');
$table->foreignId('user_id')->constrained('users')->onDelete('cascade');
$table->text('address');
$table->string('phone_number', 15)->nullable();
$table->string('email')->unique();
$table->boolean('is_guest')->default(false);
$table->timestamps();
});
}

public function down()
{
Schema::dropIfExists('customers');
}
}
