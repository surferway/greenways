<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMediaTrailTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('media_trail', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';			
			$table->increments('id');
			$table->integer('trail_id')->unsigned()->index();
			$table->foreign('trail_id')->references('id')->on('trails')->onDelete('cascade');
			$table->integer('media_id')->unsigned()->index();
			$table->foreign('media_id')->references('id')->on('media')->onDelete('cascade');			
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
		Schema::drop('media_trail');
	}

}
