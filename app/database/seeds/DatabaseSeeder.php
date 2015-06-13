<?php

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();

		// $this->call('UserTableSeeder');
		// call our class and run our seeds
		$this->call('TrailsAppSeeder');
		$this->command->info('Trails app seeds finished.'); 
	}

}

class TrailsAppSeeder extends Seeder {
	
	public function run() {

		// clear our database ------------------------------------------
		DB::table('trails')->delete();
		DB::table('amenity')->delete();
		DB::table('activity')->delete();
		DB::table('trails_activity')->delete();
		DB::table('trails_amenity')->delete();		
		DB::table('media')->delete();
		DB::table('trails_media')->delete();

		

	}

}		