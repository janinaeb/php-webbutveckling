<?php

namespace application\view;

require_once("common/view/Page.php");
require_once("SwedishDateTimeView.php");

class View {
	private $loginView;

	private $timeView;
	
	public function __construct(\login\view\LoginView $loginView) {
		$this->loginView = $loginView;
		$this->timeView = new SwedishDateTimeView();
	}
	
	public function getLoggedOutPage() {
		$html = $this->getHeader();
		$loginBox = $this->loginView->getLoginBox(); 

		$html .= "<h2>Ej Inloggad</h2>
				  	$loginBox
				 ";
		$html .= $this->getFooter();

		return new \common\view\Page("Laboration. Inte inloggad", $html);
	}
	
	public function getLoggedInPage(\login\model\UserCredentials $user) {
		$html = $this->getHeader();
		$logoutButton = $this->loginView->getLogoutButton(); 
		$userName = $user->getUserName();

		$html .= "
				<h2>$userName är inloggad</h2>
				 	$logoutButton
				 ";
		$html .= $this->getFooter();

		return new \common\view\Page("Laboration. Inloggad", $html);
	}

	public function getRegisterPage() {
		$html = $this->getHeader(false);
		$registerBox = $this->loginView->getRegisterBox();
		$html .= $registerBox;
		$html .= $this->getFooter();
		return new \common\view\Page("Laboration. Registrerar ny användare", $html);
	}
	
	private function getHeader() {
		$ret =  "<h1>Laborationskod xx222aa</h1>";
		return $ret;
		
	}

	private function getFooter() {
		$timeString = $this->timeView->getTimeString(time());
		return "<p>$timeString<p>";
	}
	
	
	
}
