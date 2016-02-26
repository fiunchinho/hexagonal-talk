<?php
class LoginResponse
{
	private $id;
	private $name;
	private $email;

	public function __construct( User $user )
	{
		$this->id 		= $user->getId();
		$this->name 	= $user->getName();
		$this->email 	= $user->getEmail();
	}

	public function getId()
	{
		return $this->id;
	}

	public function getName()
	{
		return $this->name;
	}

	public function getEmail()
	{
		return $this->email;
	}
}