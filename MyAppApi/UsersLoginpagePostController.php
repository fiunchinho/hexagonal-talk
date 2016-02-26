<?php

$app->post('/login', function() use ($app) {
	try
	{
		// Create LoginRequest with the required parameters
		$authorization_header 			= $this->getHeader( 'Authorization' );
		$decoded_header 				= base64_decode( $authorization_header );
		list( $username, $password ) 	= explode( ':', $decoded_header );

		$request 	= new LoginRequest($username, $password);
		$use_case 	= new \MyApp\UseCase\Login( $app['password_encoder'], $app['repositories.user'], $app['password_encoder'] );
		$response 	= $use_case->execute( $request );

		return $app->json( $response['user'] );
	}
	catch( \Exception $e )
	{
        return $app->json( array( 'error' => $e->getMessage() ), 403 );
	}
});