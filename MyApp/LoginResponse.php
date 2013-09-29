<?php
class LoginResponse
{
	public 		$user;
	protected 	$params = array();

	public function __construct( \MyApp\Entity\User $user, array $params = array() )
	{
		$this->user 	= $user;
		$this->params 	= $params;
	}
}