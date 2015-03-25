<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProductsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('products', function(Blueprint $table)
		{
            $table->increments('id');
            $table->string('name');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->double('price', 15, 2)->default(0);
            $table->double('promo_price', 15, 2)->default(0);
            $table->float('discount')->default(0);
            $table->string('image')->nullable();
            $table->text('sizes')->nullable();
            $table->text('colors')->nullable();
            $table->text('related')->nullable();
            $table->boolean('published')->default(1);
            $table->boolean('featured')->default(0);
			$table->timestamps();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('products');
	}

}
