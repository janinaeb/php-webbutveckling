<?php

class RegisterController {
	private $registerView;
	private $sessionHandler;
	private $userValidator;
	private $userDAL;
	
	public function __construct(RegisterView $registerView,
								Session $sessionHandler,
								UserValidation $userValidator,
								UserDAL $userDAL) {
		$this->registerView = $registerView;
		$this->sessionHandler = $sessionHandler;
		$this->userValidator = $userValidator;
		$this->userDAL = $userDAL;
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
		if ($this->userValidator->usernameExists($username)) {
			$this->sessionHandler->setMessage(Message::usernameTaken);
			return false;
		}
		
		$this->sessionHandler->saveUsername($username);
		return true;
	}
								
	public function registerSuccess() {
		$this->registerView->registerOK();
		$user = new User($this->registerView->getRegisterUsername(false), 
						$this->registerView->getRegisterPassword());
		$this->userDAL->addUser($user);
	}
}
