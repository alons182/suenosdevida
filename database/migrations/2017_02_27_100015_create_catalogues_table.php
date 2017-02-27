<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCataloguesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('catalogues', function(Blueprint $table)
        {
              $table->increments('id');
              $table->integer('shop_id')->unsigned()->index();
              $table->string('name');
              $table->string('url');
              $table->string('image')->nullable();
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
       Schema::drop('catalogues');
    }
}
