<?php

class User {
	private $username;
	private $password;
	
	// TODO: Encrypt password before adding to database
	
	public function __construct($username, $password) {
		$this->username = $username;
		$this->password = $password;//$this->encryptPassword($password);
		
	}
	
	public function getUsername() {
		return $this->username;
	}
	public function getPassword() {
		return $this->password;
	}
	public static function encryptPassword($password) {
		return crypt($password, "PASSWORD_SALT");
	}
}
