<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('trails', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';	
			$table->increments('id');
			$table->unsignedInteger('user_id');		
			$table->unsignedInteger('org_id');				
			$table->string('name');
			$table->string('location');			
			$table->string('kml_url');
			$table->text('kml_content');			
			$table->string('distance');	
			$table->string('duration');				
			$table->text('description');
			$table->text('directions');
			$table->enum('difficulty', array('Easiest','Easy','More Difficult','Very Difficult','Extremely Difficult'));
			$table->text('hazards');
			$table->text('surface');
			$table->text('land_access');
			$table->text('maintenance');
			$table->boolean('open');
			$table->boolean('active');
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
		Schema::drop('trails');
	}

}
