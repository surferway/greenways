<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUsers extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		
		DB::table('users')->insert(array(
			'org_id'=>1,
			'username'=>'Surferway',
			'email'=>'philip@surferway.com',
			'password'=>Hash::make('greenways')
		));	
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::table('users')->where('username', '=', 'Surferway')->delete();
	}

}
