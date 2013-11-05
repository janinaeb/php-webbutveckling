<?php

class CookieHandler {
	/**
	 * @var String where username is stored in cookies
	 */
	private static $usernameCookie = "view::CookieHandler::username";
	
	/**
	 * @var String where password is stored in cookies
	 */
	private static $passwordCookie = "view::CookieHandler::password";
	
	/**
	 * @var String file name where cookie end time is stored
	 */
	private static $cookieTimeHolder = "cookieTime.txt";
	
	/**
	 * @var String file name where cookie password is stored
	 */
	private static $passwordHolder = "password.txt";
	
	/**
	 * @var String file name where cookie username is stored
	 */
	private static $usernameHolder = "username.txt";
	
	/**
	 * Stores username and password in a cookie
	 * @param $user User object
	 */
	public function saveUser(User $user) {
		$cookieTime = time() + 60*60*24*30;
		
		file_put_contents(self::$cookieTimeHolder, $cookieTime);
		file_put_contents(self::$passwordHolder, $user->getPassword());
		file_put_contents(self::$usernameHolder, $user->getUsername());
		
		setcookie(self::$usernameCookie, $user->getUsername(), $cookieTime);
		setcookie(self::$passwordCookie, $user->getPassword(), $cookieTime);
	}
	
	/**
	 * @return Boolean true if cookie-information is the same as saved values
	 */
	public function compareCookie() {
		$savedUsername = file_get_contents(self::$usernameHolder);
		$savedPassword = file_get_contents(self::$passwordHolder);
		$savedTime = file_get_contents(self::$cookieTimeHolder);
		
		if ($_COOKIE[self::$usernameCookie] == $savedUsername && 
			$_COOKIE[self::$passwordCookie] == $savedPassword &&
			time() < $savedTime) {
			return true;
		}
			return false;
	}

	/**
	 * @return Boolean 
	 * true if there is cookies saved with password and username
	 */
	public function userIsSaved() {
		if (isset($_COOKIE[self::$usernameCookie]) && 
			isset($_COOKIE[self::$passwordCookie])) {
				
			return true;
		}
		return false;
	}
	
	/**
	 * Sets expire date on the username and password cookies to unvalid state
	 */
	public function forgetUser() {
		if ($this->userIsSaved()) {

			unset($_COOKIE[self::$usernameCookie]);
			unset($_COOKIE[self::$passwordCookie]);

			setcookie(self::$usernameCookie, "", time()-1);
			setcookie(self::$passwordCookie, "", time()-1);	
		}
			
	}
	
	/**
	 * @return String - username stored in cookie
	 */
	public function getUsername() {
		return $_COOKIE[self::$usernameCookie];
	}
}