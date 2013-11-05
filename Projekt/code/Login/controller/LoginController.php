<?php

class LoginController {
	/**
	 * @var LoginView object
	 */
	private $loginView;
	
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
	 * @var User object
	 */
	private $user;
	
	/**
	 * @param LoginView object
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
	 * @return string HTML
	 */
	public function logoutUser() {
		$this->loginModel->setLogin(false);
		$this->sessionHandler->setMessage(Message::logoutSuccess);
		$this->cookieHandler->forgetUser();
		$this->sessionHandler->saveUsername("");
		return $this->loginView->getLoginForm();
	}
	/**
	 * @return String HTML
	 */
	public function isLoggedInUser() {
		$this->sessionHandler->setMessage("");
		return $this->loginView->getLoggedInHTML();
	}
	/**
	 * @return string HTML
	 */
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
		$this->user = User::createNewUser($this->loginView->getUsername(), 
										$this->loginView->getPassword());
		$this->sessionHandler->saveUsername($this->user->getUsername());
		if (!$this->userValidator->usernameNotEmpty($this->user->getUsername())) {
			$this->sessionHandler->setMessage(Message::usernameMissing);
			return false;
		}
		else if (!$this->userValidator->passwordNotEmpty($this->user->getPassword())) {
			$this->sessionHandler->setMessage(Message::passwordMissing);
			return false;
		}
		else if (!$this->userValidator->usernameExists($this->user->getUsername()) ||
				 !$this->userValidator->passwordExists($this->user->getPassword())) {
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
		$this->cookieHandler->saveUser($this->user);
		$this->sessionHandler->setMessage(Message::loginSuccessWithSave);
	}
}
