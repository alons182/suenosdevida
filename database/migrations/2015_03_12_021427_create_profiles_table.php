<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfilesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('profiles', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('ide')->nullable();
            $table->string('address')->nullable();
            $table->string('code_zip')->nullable();
            $table->string('telephone')->nullable();
            $table->string('country')->default('Costa Rica')->nullable();
            $table->string('province')->default('Guanacaste');
            $table->string('canton')->default('Liberia');
            $table->string('city')->nullable();
            $table->string('bank')->nullable();
            $table->string('type_account')->nullable();
            $table->string('number_account')->nullable();
            $table->string('skype')->nullable();
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
        Schema::drop('profiles');
	}

}
