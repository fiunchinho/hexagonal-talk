<?php

$app->get('/login', function() use ($app) {
	return $app['twig']->render('login.twig', $params);
});

$app->post('/login', function() use ($app) {
	try
	{
		// Create LoginRequest with the required parameters
		$request 	= new \MyApp\UseCase\LoginRequest(
			array(
				'email' 	=> $app['request']->request->get( 'email' ),
				'password' 	=> $app['request']->request->get( 'password' )
			)
		);

		// Create our Use Case, that transforms the LoginRequest into a LoginResponse
		$user_repo 	= $app['repositories.user'];
		$use_case 	= new \MyApp\UseCase\Login( $user_repo, $app['password_encoder'] );
		$response 	= $use_case->execute( $request );

		$app['request']
			->getSession()
				->getFlashBag()
					->set( 'current_user', array( $response['user'] ) );

		$user_id 	= $response['user']->getId();
		$url 		= $app['url_generator']->generate( 'profile', array( 'id' => $user_id ) );

		return $app->redirect( $url );

	}
	catch( AlreadyLoggedInException $e )
	{
		return $app->redirect( $app['url_generator']->generate( 'profile', array( 'id' => $e->getUser()->getId() ) ) );
	}
	catch( UserNotFoundException $e )
	{
		return $app->redirect( $app['url_generator']->generate( 'register' ) );
	}
	catch( IncorrectPasswordException $e )
	{
		$app['request']
			->getSession()
				->getFlashBag()
					->set( 'errors', array( $e->getMessage() ) );
		return $app->redirect( $app['url_generator']->generate('login') );
	}
});