<?php
class LoginRequest
{
    private $email;
    private $password;

    public function __construct( $email, $password )
    {
        $this->email = $email;
        $this->password = $password;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getPassword()
    {
        return $this->password;
    }
}