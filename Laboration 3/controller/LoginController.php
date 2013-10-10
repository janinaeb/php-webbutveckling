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
								Navigation $navigationView,
								UserValidation $userValidator,
								LoginModel $loginModel,
								CookieHandler $cookieHandler,
								Session $sessionHandler) {
		$this->loginView = $loginView;
		$this->navigationView = $navigationView;
		$this->userValidator = $userValidator;
		$this->loginModel = $loginModel;
		$this->cookieHandler = $cookieHandler;
		$this->sessionHandler = $sessionHandler;
	}
								
	/**
	 * @return String HTML
	 */
	public function runApplication() { //TODO:Reset message after refresh
		if ($this->sessionHandler->testSession()) { //Test session variables
			if ($this->loginView->isLoggedOut()) {
				$this->logoutUser();
				return $this->loginView->getLoginForm();
			} else if ($this->loginView->isTryingToLogin()) {
				if ($this->tryLogin()) {	// User is logged in!
					if ($this->loginView->isSavingUser())
						$this->saveUser();
					$this->loginModel->setLogin(true);
					$this->sessionHandler->saveSession();
					return $this->loginView->getLoggedInHTML();
				}
			} else if ($this->loginModel->isLoggedIn()) {
				$this->sessionHandler->setMessage("");
				return $this->loginView->getLoggedInHTML();
			} else if ($this->cookieHandler->UserIsSaved()) {
				if ($this->cookieHandler->compareCookie()) {
					$this->sessionHandler->setMessage("Inloggning lyckades 
														via cookies");
					return $this->loginView->getLoggedInHTML();
				} else {
					$this->sessionHandler->setMessage("Felaktig 
														information i kaka!");
				}
			}
		}
		return $this->loginView->getLoginForm();
	}
	
	/**
	 * Handles logout of user
	 */
	private function logoutUser() {
		$this->loginModel->setLogin(false);
		$this->cookieHandler->forgetUser();
		$this->sessionHandler->saveusername("");
		$this->navigationView->reloadToFrontPage();
	}
	
	/**
	 * Handles validation of user input
	 * @return Boolean true if user is valid and can log in
	 */
	private function tryLogin() {
		$this->username = $this->loginView->getUsername();
		$this->password = $this->loginView->getPassword();
		$this->sessionHandler->saveUsername($this->username);
		
		if (!$this->userValidator->usernameNotEmpty($this->username)) {
			$this->sessionHandler->setMessage("Användarnamn saknas");
			return false;
		}
		else if (!$this->userValidator->passwordNotEmpty($this->password)) {
			$this->sessionHandler->setMessage("Lösenord saknas");
			return false;
		}
		else if (!$this->userValidator->usernameExists($this->username) ||
				 !$this->userValidator->passwordExists($this->password)) {
			$this->sessionHandler->setMessage("Felaktigt användarnamn/
																lösenord");
			return false;
		}
		else {
			$this->sessionHandler->setMessage("Inloggningen lyckades");
			return true;
		}
	}
	
	/**
	 * Handles saving of user information
	 */
	private function saveUser() {
		$this->cookieHandler->saveUser($this->username, $this->password);
		$this->sessionHandler->setMessage("Inloggningen lyckades, 
									 	  och vi kommer ihåg dig nästa gång");
	}
}
