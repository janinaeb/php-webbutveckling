<?php

class Init{
	public function run() {
		session_start();
		
		$dal = new UserDAL();
		$dal->getUsers();
		
		$this->userValidator = new UserValidation();
		$this->loginModel = new LoginModel();
		$this->cookieHandler = new CookieHandler();
		
		$dateMaker = new DateMaker();

		$this->sessionHandler = new Session($this->cookieHandler);
		$this->registerView = new RegisterView($this->sessionHandler);
		$this->loginView = new LoginView($this->loginModel, 
										$this->sessionHandler);

		$this->controller = new LoginController($this->loginView, 
										  $this->userValidator, 
										  $this->loginModel, 
										  $this->cookieHandler,
										  $this->sessionHandler);
		$this->registerController = new RegisterController($this->registerView,
															$this->loginView,
															$this->sessionHandler,
															$this->userValidator);
		$html = $this->runApplication();

		$html .= $dateMaker->getDateString();
		$page = new HTMLPage();
		$page->getHTMLPage($html);
	}
	
	/**
	 * @return String HTML
	 */
	private function runApplication() {
		if ($this->sessionHandler->testSession()) { //Tests session variables
			if ($this->loginView->isLoggedOut()) {
				return $this->controller->logoutUser();
			} else if ($this->loginModel->isLoggedIn()) {
				return $this->controller->isLoggedInUser();
			} else if ($this->loginView->isTryingToLogin()) {
				if ($this->controller->tryLogin()) {	// User is logged in!
					if ($this->loginView->isSavingUser())
						$this->controller->saveUser();
					return $this->controller->setLogin();
				}
			} else if ($this->cookieHandler->UserIsSaved()) {
				if ($this->cookieHandler->compareCookie()) {
					$this->sessionHandler->setMessage(Message::loginFromCookie);
					return $this->controller->setLogin();
				} else {
					$this->sessionHandler->setMessage(Message::faultyCookie);
					$this->cookieHandler->forgetUser();
				}
			}
		} 
		if ($this->registerView->isRegistring()) {
			if ($this->registerView->isPressingRegister()) {
				if ($this->registerController->tryRegister()) {
					return $this->registerController->registerSuccess();
				}
			}
			return $this->registerView->getRegisterForm();
		}
		return $this->loginView->getLoginForm();
	}
}