<?php
class LoginUseCaseTest extends PHPUnit_Framework_TestCase
{
	public function setUp()
    {
        $this->session 		= $this->getMock( 'SessionInterface' );
        $this->user_repo 		= $this->getMock( 'UserRepository' );
        $this->password_encoder = $this->getMock( 'PasswordEncoder' );
        $this->use_case 		= new LoginUseCase( $this->session, $this->user_repo, $this->password_encoder );

        $this->sample_user 		= new \MyApp\Entity\User( 'fiunchinho', 'Jose', 'jose@armesto.net', 'encoded password' );
        $this->correct_password = 'password';
    }

	public function testItShouldReturnUserLoginResponse()
    {
        $request = new UserLoginRequest($this->sample_user->getEmail(), $this->sample_user->getPassword() );

        $response = $this->use_case->execute( $request );

        $this->assertInstanceOf( 'UserLoginResponse', $response );
    }

    public function testItShouldThrowOnIncorrectPassword()
    {
        $request = new UserLoginRequest($this->sample_user->getEmail(), "Some wrong password");

        $this->setExpectedException( 'IncorrectPasswordException' );
        $this->use_case->execute($request);
    }

    public function testItShouldThrowIfUserNotFound()
    {
        $request = new UserLoginRequest('unknown@example.com', $this->correct_password);

        $this->setExpectedException( 'UserNotFoundException' );
        $response = $this->use_case->execute( $request );
    }
}