<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shops', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('name');
            $table->string('slug');
            $table->string('canton');
            $table->text('information')->nullable();
            $table->text('details')->nullable();
            $table->string('logo')->nullable();
            $table->string('image')->nullable();
            $table->boolean('published')->default(1);
            $table->integer('responsable_id')->unsigned()->index();
            $table->timestamps();
        });

        Schema::table('products', function(Blueprint $table)
        {
            $table->integer('shop_id')->unsigned()->index();
        });
        Schema::table('categories', function(Blueprint $table)
        {
            $table->integer('shop_id')->unsigned()->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('shops');

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('shop_id');
        });
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('shop_id');
        });


    }
}
