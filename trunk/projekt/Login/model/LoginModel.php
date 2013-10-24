<?php

class LoginModel {
	/**
	 * @var String
	 */
	private static $loginStateHolder = "model::LoginModel::logged-in";
	
	/**
	 * Handles log in/log out depending on if parameter is set to true/false
	 * @param Boolean value to set to session variable login
	 */
	public function setLogin($bool) {
		$_SESSION[self::$loginStateHolder] = $bool;
	}

	/**
	 * @return Boolean
	 */
	public function isLoggedIn() {
		if (isset($_SESSION[self::$loginStateHolder])) {
			return $_SESSION[self::$loginStateHolder];
		}
		return false;
	}
}
