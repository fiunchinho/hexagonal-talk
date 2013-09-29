<?php
interface UserRepository
{
	public function isUserLogged();
	public function findOneByEmail();
}