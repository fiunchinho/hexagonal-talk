<?php
class LoginUseCaseTest extends PHPUnit_Framework_TestCase
{
	public function setUp()
    {
        $this->user_repo 		= $this->getMock( 'UserRepository' );
        $this->password_encoder = $this->getMock( 'PasswordEncoder' );
        $this->use_case 		= new LoginUseCase( $this->user_repo, $this->password_encoder );

        $this->sample_user 		= new \MyApp\Entity\User( 'fiunchinho', 'Jose', 'jose@armesto.net', 'encoded password' );
        $this->correct_password = 'password';
    }

	public function testItShouldReturnUserLoginResponse()
    {
        // Arrange
    	$request_parameters = array(
            'email' 	=> $this->sample_user->getEmail(),
            'password' 	=> $this->correct_password,
        );
        $request = new UserLoginRequest( $request_parameters );

        // Act
        $response = $this->use_case->execute( $request );

        // Assert
        $this->assertInstanceOf( 'UserLoginResponse', $response );
    }

    public function testItShouldIncludeUserInUserLoginResponse()
    {
        // Arrange
        $request_parameters = array(
            'email' 	=> $this->sample_user->getEmail(),
            'password' 	=> $this->correct_password,
        );
        $request = new UserLoginRequest( $request_parameters );

        // Act
        $response = $this->use_case->execute( $request );

        // Assert
        $this->assertInstanceOf( '\MyApp\Entity\User', $response->user );
    }

    public function testItShouldThrowOnIncorrectPassword()
    {
        $request_parameters = array(
            'email' 	=> $this->sample_user->getEmail(),
            'password' 	=> "Some wrong password",
        );
        $request = new UserLoginRequest( $request_parameters );

        $this->setExpectedException( 'IncorrectPasswordException' );
        $response = $this->use_case->execute( $request );
    }

    public function testItShouldThrowIfUserNotFound()
    {
        $request_parameters = array(
            'email' => 'unknown@example.com',
            'password' => $this->correct_password,
        );
        $request = new UserLoginRequest( $request_parameters );

        $this->setExpectedException( 'UserNotFoundException' );
        $response = $this->use_case->execute( $request );
    }
}