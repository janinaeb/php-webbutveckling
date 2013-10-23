<?php

class RegisterController {
	private $registerView;
	private $loginView;
	private $sessionHandler;
	private $userValidator;
	
	public function __construct(RegisterView $registerView,
								LoginView $loginView,
								Session $sessionHandler,
								UserValidation $userValidator) {
		$this->registerView = $registerView;
		$this->loginView = $loginView;
		$this->sessionHandler = $sessionHandler;
		$this->userValidator = $userValidator;
	}
	
	public function tryRegister() {
		$username = $this->registerView->getRegisterUserName(false);
		$password = $this->registerView->getRegisterPassword();
		
		if ($this->userValidator->usernameTooShort($username)) {
			$this->sessionHandler->setMessage(Message::usernameTooShort);
			return false;
		} else if ($this->userValidator->usernameContainsTags($username)) {
			$this->sessionHandler->setMessage(Message::usernameFaulty);
			return false;
		} else if ($this->userValidator->usernameTooLong($username)) {
			$this->sessionHandler->setMessage(Message::usernameTooLong);
			return false;
		} else if ($this->userValidator->passwordTooShort($password)) {
			$this->sessionHandler->setMessage(Message::passwordTooShort);
			return false;
		} else if ($this->userValidator->passwordTooLong($password)) {
			$this->sessionHandler->setMessage(Message::passwordTooLong);
			return false;
		} else if (!$this->registerView->passwordsMatch()) {
			$this->sessionHandler->setMessage(Message::passwordNoMatch);
			return false;
		}
		
		// TODO: TEST IF USERNAME EXISTS IN DATABASE
		/*if () {
			$this->sessionHandler->setMessage(Message::usernameTaken);
		}*/
		
		$this->sessionHandler->saveUsername($username);
		return true;
	}
								
	public function registerSuccess() {
		$this->sessionHandler->setMessage(Message::registerSuccess);
		return $this->loginView->getLoginForm();
	}
}
