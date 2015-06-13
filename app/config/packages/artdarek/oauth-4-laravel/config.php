<?php 

return array( 
	
	/*
	|--------------------------------------------------------------------------
	| oAuth Config
	|--------------------------------------------------------------------------
	*/

	/**
	 * Storage
	 */
	'storage' => 'Session', 

	/**
	 * Consumers
	 */
	'consumers' => array(

		/**
		 * Facebook
		 */
		'Facebook' => array(
			'client_id'     => '1005043472855281',
			'client_secret' => '27bb41249c0218cd1b59f8b99835b45f',
			'scope'         => array('email','read_friendlists','user_online_presence'),
		), 

		'Google' => array(
				'client_id'     => '922561287590-8hufbdvf7urup4r22hep93l76e0h226q.apps.googleusercontent.com',
				'client_secret' => 'CLqA-70cDDQyyW80aIgDo3mw',
				'scope'         => array('userinfo_email', 'userinfo_profile'),
		), 		

	)

);