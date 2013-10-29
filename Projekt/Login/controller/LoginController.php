<?php

class LoginController {
	/**
	 * @var LoginView object
	 */
	private $loginView;
	
	/**
	 * @var Navigation object
	 */
	private $navigationView;
	
	/**
	 * @var UserValidation object
	 */
	private $userValidator;
	
	/**
	 * @var LoginModel object
	 */
	private $loginModel;
	
	/**
	 * @var CookieHandler object
	 */
	private $cookieHandler;
	
	/**
	 * @var Session object
	 */
	private $sessionHandler;
	
	/**
	 * @var String username
	 */
	private $username;
	/**
	 * @var String password
	 */
	private $password;
	
	/**
	 * @param LoginView object
	 * @param Navigation object
	 * @param UserValidator object
	 * @param LoginModel object
	 * @param CookieHandler object
	 * @param Session object
	 */
	public function __construct(LoginView $loginView,
								UserValidation $userValidator,
								LoginModel $loginModel,
								CookieHandler $cookieHandler,
								Session $sessionHandler) {
		$this->loginView = $loginView;
		$this->userValidator = $userValidator;
		$this->loginModel = $loginModel;
		$this->cookieHandler = $cookieHandler;
		$this->sessionHandler = $sessionHandler;
	}
	
	/**
	 * Handles logout of user
	 */
	public function logoutUser() {
		$this->loginModel->setLogin(false);
		$this->sessionHandler->setMessage(Message::logoutSuccess);
		$this->cookieHandler->forgetUser();
		$this->sessionHandler->saveUsername("");
		return $this->loginView->getLoginForm();
	}
	public function isLoggedInUser() {
		$this->sessionHandler->setMessage("");
		return $this->loginView->getLoggedInHTML();
	}
	
	public function setLogin() {
		$this->loginModel->setLogin(true);
		$this->sessionHandler->saveSession();
		return $this->loginView->getLoggedInHTML();
	}
	/**
	 * Handles validation of user input
	 * @return Boolean true if user is valid and can log in
	 */
	public function tryLogin() {
		$this->username = $this->loginView->getUsername();
		$this->password = $this->loginView->getPassword();
		
		$this->sessionHandler->saveUsername($this->username);
		if (!$this->userValidator->usernameNotEmpty($this->username)) {
			$this->sessionHandler->setMessage(Message::usernameMissing);
			return false;
		}
		else if (!$this->userValidator->passwordNotEmpty($this->password)) {
			$this->sessionHandler->setMessage(Message::passwordMissing);
			return false;
		}
		else if (!$this->userValidator->usernameExists($this->username) ||
				 !$this->userValidator->passwordExists($this->password)) {
			$this->sessionHandler->setMessage(Message::credentialsWrong);
			return false;
		}
		else {
			$this->sessionHandler->setMessage(Message::loginSuccess);
			return true;
		}
	}
	
	/**
	 * Handles saving of user information
	 */
	public function saveUser() {
		$this->cookieHandler->saveUser($this->username, $this->password);
		$this->sessionHandler->setMessage(Message::loginSuccessWithSave);
	}
}
