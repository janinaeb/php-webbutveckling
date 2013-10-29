<?php

class Session {
	/**
	 * @var String - where the username is stored in session
	 */
	private static $usernameHolder = "username";
	
	/**
	 * @var String - where the message is stored in session
	 */
	private static $messageHolder = "message";
	
	/**
	 * @var String - where the user agent is stored in session
	 */
	private static $userAgentHolder = "user-agent";
	
	/**
	 * @var String - where the ip is stored in session
	 */
	private static $IPHolder = "ip";
	
	/**
	 * @var CookieHandler object
	 */
	private $cookieHandler;
	
	/**
	 * @Param CookieHandler object
	 */
	public function __construct(CookieHandler $cookieHandler) {
		$this->cookieHandler = $cookieHandler;
		$this->setMessage("");
	}

	/**
	 * Saves the user agent and the ip in session variables
	 */
	public function saveSession() {
		$_SESSION[self::$userAgentHolder] = $_SERVER["HTTP_USER_AGENT"];
		$_SESSION[self::$IPHolder] = $_SERVER["REMOTE_ADDR"];
	}
	
	/**
	 * @return Boolean true if user agent and IP is the same 
	 * 							as saved in session variable
	 */
	public function testSession() {
		if (isset($_SESSION[self::$userAgentHolder]) && 
			isset($_SESSION[self::$IPHolder])) {
			
			if ($_SERVER["HTTP_USER_AGENT"] === 
				$_SESSION[self::$userAgentHolder] &&
				$_SERVER["REMOTE_ADDR"] === 
				$_SESSION[self::$IPHolder]) {
					
				return true;
			}
			return false;
		}
		// If session variables is not set then it is
		// the first time to log in and test should pass
		return true; 
	}
	
	/**
	 * Saves username in session
	 */
	public function saveUsername($username) {
		$_SESSION[self::$usernameHolder] = $username;
	}
	
	/**
	 * @return String - username saved in session variable if it is set, 
	 * 									else it returns an empty string
	 */
	public function getUsername() {
		if (isset($_SESSION[self::$usernameHolder])) {
			return $_SESSION[self::$usernameHolder];
		}
		if ($this->cookieHandler->userIsSaved()) {
			return $this->cookieHandler->getUsername();
		}
		return "";
	}
	
	/**
	 * @param String message to save in session variable
	 */
	public function setMessage($message) {
		$_SESSION[self::$messageHolder] = $message;
	}
	
	/**
	 * @return String message stored in session variable
	 */
	public function getMessage() {
		if (isset($_SESSION[self::$messageHolder])) {
			return $_SESSION[self::$messageHolder];
		}
		return "";
	}
	public function isSameUser($username) {
		if ($this->getUsername() === $username) {
			return true;
		}
		return false;
	}
}
