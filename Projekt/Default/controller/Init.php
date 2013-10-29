<?php

class Init{
	private $isLoggedIn = false;
	private $user;
	
	public function run() {
		session_start();
		
		$userDAL = new UserDAL();
		
		
		$this->userValidator = new UserValidation($userDAL);
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
															$this->sessionHandler,
															$this->userValidator,
															$userDAL);
		$html = $this->runLogin();
		if ($this->isLoggedIn) {
			$entryView = new EntryView($this->sessionHandler);
			$html .= $entryView->getMenuHTML();
			$entryDAL = new EntryDAL();
			$entryController = new EntryController($entryDAL, 
													$entryView, 
													$this->sessionHandler);
			$html .= $entryController->getEntries();
		}

		$html .= $dateMaker->getDateString();
		$page = new HTMLPage();
		$page->getHTMLPage($html);
	}
	
	/**
	 * @return String HTML
	 */
	private function runLogin() {
		if ($this->sessionHandler->testSession()) { //Tests session variables
			if ($this->loginView->isLoggedOut()) {
				return $this->controller->logoutUser();
			} else if ($this->loginModel->isLoggedIn()) {
				$this->isLoggedIn = true;
				return $this->controller->isLoggedInUser();
			} else if ($this->loginView->isTryingToLogin()) {
				if ($this->controller->tryLogin()) {	// User is logged in!
					$this->isLoggedIn = true;
					$this->user = new User($this->loginView->getUsername(), 
											$this->loginView->getPassword());
					if ($this->loginView->isSavingUser())
						$this->controller->saveUser();
					return $this->controller->setLogin();
				}
			} else if ($this->cookieHandler->UserIsSaved()) {
				if ($this->cookieHandler->compareCookie()) {
					$this->isLoggedIn = true;
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
					$this->registerController->registerSuccess();
					return $this->loginView->getLoginForm();
				}
			}
			return $this->registerView->getRegisterForm();
		}
		return $this->loginView->getLoginForm();
	}
}