<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivityTrailTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('activity_trail', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->increments('id');
			$table->integer('activity_id')->unsigned()->index();
			$table->integer('trail_id')->unsigned()->index();  
			$table->foreign('activity_id')->references('id')->on('activities')->onDelete('cascade');
			$table->foreign('trail_id')->references('id')->on('trails')->onDelete('cascade');
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
		Schema::drop('trails_activity');
	}

}
