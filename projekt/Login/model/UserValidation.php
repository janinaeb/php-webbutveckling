<?php

class UserValidation {
	private static $maxUsername = 16;
	private static $minUsername = 3;
	private static $maxPassword = 16;
	private static $minPassword = 6;
	
	private $userDAL;
	private $userList;
	private $user;
	
	public function __construct(UserDAL $userDAL) {
		$this->userDAL = $userDAL;
		$this->userList = $this->userDAL->getUsers();
	}
	
	/**
	 * @param String
	 * @return Boolean true if username is set
	 */
	public function usernameNotEmpty($username) {
		if ($username != "") {
			return true;
		}
		return false;
	}
	
	/**
	 * @param String
	 * @return Boolean true if username is valid
	 */
	public function usernameExists($username) {
		for ($i = 0; $i < count($this->userList); $i++) {
			
			if ($this->userList[$i]->getUsername() == $username) {
				$this->user = $this->userList[$i];
				return true;
			}
		}
		return false;
	}
	
	/**
	 * @param String
	 * @return Boolean true if password is set
	 */
	public function passwordNotEmpty($password) {
		if ($password != "") {
			return true;
		}
		return false;
	}
	
	/**
	 * @param String
	 * @return Boolean true if password is valid
	 */
	public function passwordExists($password) {
		if ($password == $this->user->getPassword()) {
			return true;
		}
		return false;
	}
	
	
	
	public function usernameTooLong($username) {
		if (strlen($username) > self::$maxUsername) {
			return true;
		}
		return false;
	}
	public function usernameTooShort($username) {
		if (strlen($username) < self::$minUsername) {
			return true;
		}
		return false;
	}
	public function usernameContainsTags($username) {
		$cleanString = strip_tags($username);
		if ($cleanString != $username) {
			return true;
		}
		return false;
	}
	public function passwordTooShort($password) {
		if (strlen($password) < self::$minPassword) {
			return true;
		}
		return false;
	}
	public function passwordTooLong($password) {
		if (strlen($password) > self::$maxPassword) {
			return true;
		}
		return false;
	}
	
	public static function cleanString($inputString) {
		$inputString = strip_tags($inputString);
		$inputString = ltrim($inputString);
		$inputString = rtrim($inputString);
		
		return $inputString;
	}
	
}
