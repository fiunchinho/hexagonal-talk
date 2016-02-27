<?php
class LoginUseCase
{
    /**
	 * The use case has some dependencies.
     * It asks for them in the constructor,
     * because without them it can't work.
	 */
	public function __construct(
        SessionInterface $session,
        UserRepository $user_repo,
        PasswordEncoder $password_encoder ) {
		$this->session	  = $session;
		$this->user_repo  = $user_repo;
		$this->encoder 	  = $password_encoder;
	}

	/**
	 * We execute the 'Use Case', transforming a request into a response.
	 * @param 	LoginRequest 	$request 	This request contains the needed parameters for the Use Case to work.
	 * @return 	LoginResponse 	$response 	The response contains the needed values to represent the result of this Use Case.
	 */
	public function execute( LoginRequest $request )
	{
		if ( $this->session->isUserLogged() )
		{
			throw new AlreadyLoggedInException( 'User is already logged in' );
		}

        $request_email = $request->getEmail();

		$user_entity = $this->user_repo->findOneByEmail( strtolower( $request_email ) );
        
        if ( !$user_entity )
        {
            throw new UserNotFoundException( $request_email );
        }

        if ( !$this->encoder->isPasswordValid( $user_entity->getPasswordHash(), $request->getPassword() ) )
        {
            throw new IncorrectPasswordException( $request_email );
        }

		// We may need to calculate some $params that the response needs.
		// For clarity, we leave it as an empty array in this example
		return new LoginResponse( $user_entity, $params = array() );
	}
}