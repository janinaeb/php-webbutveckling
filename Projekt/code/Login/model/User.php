<?php

class User {
	/**
	 * @var string
	 */
	private $username;
	/**
	 * @var string
	 */
	private $password;
	
	/**
	 * @param string username
	 * @param string password
	 */
	private function __construct($username, $password) {
		$this->username = $username;
		$this->password = $password;
	}

	/**
	 * @param string username
	 * @param string password clear text
	 * @return User object
	 */
	public static function createNewUser($username, $clearTextPassword) {
		return new User($username, self::encryptPassword($clearTextPassword));
	}
	
	/**
	 * @param string username
	 * @param string password encrypted
	 * @return User object
	 */
	public static function createUser($username, $password) {
		return new User($username, $password);
	}
	
	/**
	 * @return string username
	 */
	public function getUsername() {
		return $this->username;
	}
	
	/**
	 * @return string password
	 */
	public function getPassword() {
		return $this->password;
	}
	
	/**
	 * @param string password
	 * @return string encrypted with password salt
	 */
	private static function encryptPassword($password) {
		return sha1($password . PASSWORD_SALT);
	}
}
