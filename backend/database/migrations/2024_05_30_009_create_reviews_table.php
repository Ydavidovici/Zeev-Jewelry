// database/migrations/2024_05_29_009_create_reviews_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReviewsTable extends Migration
{
public function up()
{
Schema::create('reviews', function (Blueprint $table) {
$table->id('review_id');
$table->foreignId('product_id')->constrained('products')->onDelete('cascade');
$table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
$table->text('review_text')->nullable();
$table->integer('rating')->checkBetween(1, 5);
$table->dateTime('review_date')->default(DB::raw('CURRENT_TIMESTAMP'));
$table->timestamps();
});
}

public function down()
{
Schema::dropIfExists('reviews');
}
}
