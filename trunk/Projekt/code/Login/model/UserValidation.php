<?php

class UserValidation {
	/**
	 * @var int
	 */
	private static $maxUsername = 16;
	
	/**
	 * @var int
	 */
	private static $minUsername = 3;
	
	/**
	 * @var int
	 */
	private static $maxPassword = 16;
	
	/**
	 * @var int
	 */
	private static $minPassword = 6;
	
	/**
	 * @var UserDAL object
	 */
	private $userDAL;
	
	/**
	 * @var array of user objects
	 */
	private $userList;
	
	/**
	 * @param UserDAL object
	 */
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
		$savedPassword = $this->user->getPassword();
		if ($password == $savedPassword) {
			return true;
		}
		return false;
	}
	
	/**
	 * @param string username
	 * @return boolean true if username is too long
	 */
	public function usernameTooLong($username) {
		if (strlen($username) > self::$maxUsername) {
			return true;
		}
		return false;
	}
	
	/**
	 * @param string username
	 * @return boolean true if username is too short
	 */
	public function usernameTooShort($username) {
		if (strlen($username) < self::$minUsername) {
			return true;
		}
		return false;
	}
	
	/**
	 * @param string 
	 * @return boolean true if password is too short
	 */
	public function passwordTooShort($password) {
		if (strlen($password) < self::$minPassword) {
			return true;
		}
		return false;
	}
	
	/**
	 * @param string
	 * @return boolean true if password is too long
	 */
	public function passwordTooLong($password) {
		if (strlen($password) > self::$maxPassword) {
			return true;
		}
		return false;
	}
	
	/**
	 * @param string input
	 * @return string trimmed string
	 */
	public static function cleanString($inputString) {
		$inputString = strip_tags($inputString);
		$inputString = ltrim($inputString);
		$inputString = rtrim($inputString);
		
		return $inputString;
	}
	
}
