<?php

// database/migrations/2024_05_30_009_create_reviews_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReviewsTable extends Migration
{
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id('review_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('customer_id');
            $table->text('review_text')->nullable();
            $table->integer('rating');
            $table->timestamp('review_date')->useCurrent();
            $table->timestamps();

            $table->foreign('product_id')->references('product_id')->on('products')->onDelete('cascade');
            $table->foreign('customer_id')->references('customer_id')->on('customers')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('reviews');
    }
}
