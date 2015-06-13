<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePointsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('points', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';		
			$table->increments('id');
			$table->unsignedInteger('trail_id');			
			$table->string('title');
			$table->text('description');
			$table->string('point');
			$table->decimal('lat', 10, 8);
			$table->decimal('lon', 11, 8);
			$table->boolean('primary');			
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
		Schema::drop('points');
	}

}
