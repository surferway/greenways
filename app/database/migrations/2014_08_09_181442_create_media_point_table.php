<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMediaPointTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('media_point', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';			
			$table->string('id');
			$table->integer('point_id')->unsigned()->index();
			$table->integer('media_id')->unsigned()->index();    
			$table->foreign('point_id')->references('id')->on('points')->onDelete('cascade');
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
		Schema::drop('media_point');
	}

}
