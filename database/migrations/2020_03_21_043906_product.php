<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Product extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    if (!Schema::hasTable('products') ){
      Schema::create('products', function (Blueprint $table) {
        $table->increments('id');
        $table->string('url', 255);
        $table->string('title', 255)->nullable();
        $table->text('description')->nullable();
        $table->text('images')->nullable();
        $table->bigInteger('last_price');
        $table->timestamps();
        $table->unique('url');
      });
    }
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('products');
  }
}
