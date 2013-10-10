<?php

class LoginView {
	/**
	 * @var String
	 */
	private static $logoutString = "logout";
	
	/**
	 * @var String
	 */
	private static $usernameHolder = "user";
	
	/**
	 * @var String
	 */
	private static $passwordHolder = "pass";
	
	/**
	 * @var String
	 */
	private static $saveUserCheck = "save";
	
	/**
	 * @var String
	 */
	private static $loginButton = "login";
	
	/**
	 * @var LoginModel object reference
	 */
	private $loginModel;
	
	/**
	 * @var Session object reference
	 */
	private $sessionHandler;
	
	/**
	 * @param LoginModel object
	 * @param Session object
	 */
	public function __construct(LoginModel $loginModel,
								Session $sessionHandler) {
		$this->loginModel = $loginModel;
		$this->sessionHandler = $sessionHandler;
	}

	/**
	 * @return Boolean true if query string is set
	 */
	public function isLoggedOut() {
		$logoutMessage = "Du har loggat ut";
		if (isset($_GET[self::$logoutString])) {
			$this->sessionHandler->setMessage($logoutMessage);
			return true;
		}
		return false;
	}

	/**
	 * @return Boolean true if login button is pressed
	 */
	public function isTryingToLogin() {
		return isset($_POST[self::$loginButton]);
	}
	
	/**
	 * @return Boolean true if save user checkbox is checked
	 */
	public function isSavingUser() {
		return isset($_POST[self::$saveUserCheck]);
	}
	
	/**
	 * @return String username from form
	 */
	public function getUsername() {
		return $_POST[self::$usernameHolder];
	}
	
	/**
	 * @return String password from form
	 */
	public function getPassword() {
		return $_POST[self::$passwordHolder];
	}

	/**
	 * @return string HTML login form
	 */
	public function getLoginForm() {
		return "<h2>Ej Inloggad</h2>
			<form method='post'>
				<fieldset>
					<legend>Login - Skriv in användarnamn och lösenord</legend>
					<p>" . $this->sessionHandler->getMessage() . "</p>
					
					<label for='user'>Användarnamn: </label>
					<input type='text' id='user' name='"
					. self::$usernameHolder . 
					"' value='" . $this->sessionHandler->getUsername() . "' />
					
					<label for='pass'>Lösenord: </label>
					<input type='password' id='pass' name='"
					. self::$passwordHolder . "' />
					
					<label for='save'>Håll mig inloggad: </label>
					<input type='checkbox' id='save' name='"
					. self::$saveUserCheck . "' />
					
					<input type='submit' value='Logga in' name='"
					. self::$loginButton . "' />
				</fieldset>
			</form>";
	}
	
	/**
	 * @return string HTML logged in page
	 */
	public function getLoggedInHTML() {
		return "<h2>"
			. $this->sessionHandler->getUsername() . " är inloggad</h2>
			<p>" . $this->sessionHandler->getMessage() . "</p>
			<a href='?" . self::$logoutString . "'>Logga ut</a>";
	}
}
