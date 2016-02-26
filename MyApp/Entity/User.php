<?php

class User
{
    private $id;
    private $name;
    private $email;
    private $passwordHash;

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

    public function getPasswordHash()
    {
        return $this->passwordHash;
    }

    public function activateAccount()
    {
        // Some business rules
    }
}