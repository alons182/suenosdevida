<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('ads', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('name');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->string('province')->default('Guanacaste');
            $table->string('canton')->default('Liberia');
            $table->string('video')->nullable();
            $table->string('image')->nullable();
            $table->boolean('published')->default(1);
            $table->boolean('featured')->default(0);
            $table->dateTime('publish_date')->nullable();
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
        Schema::drop('ads');
	}

}
