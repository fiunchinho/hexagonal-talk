<?php
class LoginUseCase
{
	/**
	 * The use case has some dependencies. It asks for them in the constructor, because without them it can't work.
	 */
	public function __construct( UserRepository $user_repo, PasswordEncoder $password_encoder )
	{
		$this->user_repo	= $user_repo;
		$this->encoder 		= $password_encoder;
	}

	/**
	 * We execute the 'Use Case', transforming a request into a response.
	 * @param 	LoginRequest 	$request 	This request contains the needed parameters for the Use Case to work.
	 * @return 	LoginResponse 	$response 	The response contains the needed values to represent the result of this Use Case.
	 */
	public function execute( Request $request )
	{
		if ( $this->user_repo->isUserLogged() )
		{
			throw new AlreadyLoggedInException( 'User is already logged in' );
		}

		$user_entity = $this->user_repo->findOneByEmail( strtolower( $request['email'] ) );
        if ( !$user_entity )
        {
            throw new UserNotFoundException( $request['email']  );
        }

        if ( !$this->encoder->isPasswordValid( $user_entity->getPasswordHash(), $request['password'] ) )
        {
            throw new IncorrectPasswordException( $request['email']  );
        }

		// We may need to calculate some $params that the response needs.
		// For clarity, we leave it as an empty array in this example
		return new LoginResponse( $user_entity, $params = array() );
	}
}