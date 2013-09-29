<?php

$app->post('/login', function() use ($app) {
	try
	{
		// Create LoginRequest with the required parameters
		$authorization_header 			= $this->getHeader( 'Authorization' );
		$decoded_header 				= base64_decode( $authorization_header );
		list( $username, $password ) 	= explode( ':', $decoded_header );

		$request 	= new \MyApp\UseCase\LoginRequest(
			array(
				'email' 	=> $username,
				'password' 	=> $password
			)
		);

		// Create our Use Case, that transforms the LoginRequest into a LoginResponse
		$user_repo 	= $app['repositories.user'];
		$use_case 	= new \MyApp\UseCase\Login( $user_repo, $app['password_encoder'] );
		$response 	= $use_case->execute( $request );

		return $app->json( $response['user'] );
	}
	catch( \Exception $e )
	{
        return $app->json( array( 'error' => $e->getMessage() ), 403 );
	}
});