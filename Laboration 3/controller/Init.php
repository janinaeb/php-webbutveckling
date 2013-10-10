<?php

class Init{
	public function run() {
		session_start();

		$navigationView = new Navigation();
		$userValidator = new UserValidation();
		$loginModel = new LoginModel();
		$cookieHandler = new CookieHandler();
		$dateMaker = new DateMaker();

		$sessionHandler = new Session($cookieHandler);
		$loginView = new LoginView($loginModel, $sessionHandler);

		$controller = new LoginController($loginView, 
										  $navigationView, 
										  $userValidator, 
										  $loginModel, 
										  $cookieHandler,
										  $sessionHandler);
		$html = $controller->runApplication();

		$html .= $dateMaker->getDateString();
		$page = new HTMLPage();
		$page->getHTMLPage($html);
	}
}