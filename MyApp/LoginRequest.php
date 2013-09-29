<?php
class LoginRequest implements Request
{
    protected $container = array();

    public function __construct( array $params = array() )
    {
        $this->container = $params;
    }
}