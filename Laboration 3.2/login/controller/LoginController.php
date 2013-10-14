<?php

namespace login\controller;

require_once("./login/model/LoginModel.php");
require_once("./login/view/LoginView.php");

class LoginController {
	
	private $model;

	private $view;
	
	public function __construct(\login\view\LoginView $view) {
		$this->model = new \login\model\LoginModel();;
		$this->view = $view;
	}
	
	
	public function isLoggedIn() {
		return $this->model->isLoggedIn();
	}
	public function isRegistring() {
		return $this->view->isRegistring();
	}
	
	public function getLoggedInUser() {
		return $this->model->getLoggedInUser();
	}
	
	public function doToggleLogin() {
		if ($this->model->isLoggedIn()) {
			\Debug::log("We are logged in");
			if ($this->view->isLoggingOut() ) {
				$this->model->doLogout();
				$this->view->doLogout();
				\Debug::log("We logged out");
			}
		} else if ($this->view->isRegistring()) {
			\Debug::log("We are registring"); 
			if ($this->view->isPressingRegister()) {
				try {
					if (!$this->view->passwordsMatch()) {
						throw new \Exception("Passwords do not match");
					}
					$credentials = $this->view->getUserCredentials();
					$this->model->doRegister($credentials, $this->view);
					\Debug::log("Register succeded");
				} catch (\Exception $e) {
					\Debug::log("Register failed", false, $e->getMessage());
					$this->view->RegisterFailed();
				}
			}
		} else {
			\Debug::log("We are not logged in");
			if ($this->view->isLoggingIn() ) {
				try {
					$credentials = $this->view->getUserCredentials();
					$this->model->doLogin($credentials, $this->view);
					\Debug::log("Login succeded");
				} catch (\Exception $e) {
					\Debug::log("Login failed", false, $e->getMessage());
					$this->view->LoginFailed();
				}
			}
		} 
	}
}
