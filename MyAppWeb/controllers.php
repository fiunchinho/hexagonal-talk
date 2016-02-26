<?php

$app->get('/login', function() use ($app) {
	return $app['twig']->render('login.twig', $params);
});

$app->post('/login', function() use ($app) {
	try
	{
		// Create LoginRequest with the required parameters
		$request 	= new LoginRequest($app['request']->request->get('email'), $app['request']->request->get('password'));
		$use_case 	= new \MyApp\UseCase\Login( $app['session'], $app['repositories.user'], $app['password_encoder'] );
		$response 	= $use_case->execute( $request );

		$user_id 	= $response->getId();
		$app['session']
				->getFlashBag()
					->set( 'current_user', array( $response->getId() ) );

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
		$app['session']
			->getFlashBag()
					->set( 'errors', array( $e->getMessage() ) );
		return $app->redirect( $app['url_generator']->generate('login') );
	}
});