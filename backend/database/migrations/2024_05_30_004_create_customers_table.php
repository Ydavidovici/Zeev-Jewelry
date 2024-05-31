<?php

// database/migrations/2024_05_30_004_create_customers_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    public function up()
    {
        /**
         * Run the migrations.
         *
         * @return void
         */
        Schema::create('customers', function (Blueprint $table) {
            $table->id('customer_id');
            $table->unsignedBigInteger('user_id');
            $table->text('address');
            $table->string('phone_number', 15)->nullable();
            $table->string('email',255)->unique();
            $table->boolean('is_guest')->default(false);
            $table->timestamps();

            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');

            $table->index('email');
        });
    }

    public function down()
    {
        /**
     * Reverse the migrations.
     *
     * @return void
     */
        Schema::dropIfExists('customers');
    }
}
