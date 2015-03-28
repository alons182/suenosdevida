<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLevelsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('levels', function(Blueprint $table)
		{
			$table->increments('id');
            $table->tinyInteger('level');
            $table->double('payment', 15, 2)->default(0);
            $table->double('gain', 15, 2)->default(0);
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
		Schema::drop('levels');
	}

}
