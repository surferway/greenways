<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAmenityTrailTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('amenity_trail', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';	
			$table->increments('id');
			$table->integer('amenity_id')->unsigned()->index();
			$table->integer('trail_id')->unsigned()->index();    
			$table->foreign('amenity_id')->references('id')->on('amenities')->onDelete('cascade');
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
		Schema::drop('amenity_trail');
	}

}
