<?php

class LoginControl {
	/**
	 * @var PageViewer $pageViewer object
	 */
	private $pageViewer;
	
	/**
	 * @var String $message message to display in HTML
	 */
	private $message;
	
	/**
	 * @param PageViewer $pageViewer - object
	 */
	public function __construct($pageViewer) {
		$this->pageViewer = $pageViewer;
		$this->message = "";
	}
	
	/**
	 * Checking if the page state is logged out and resetting session
	 */
	public function checkPageState() {
		if ($_SERVER["QUERY_STRING"] == "logout" && 
			$this->pageViewer->loggedIn()) {
				
			$this->message = "Du har nu loggat ut";
			$this->pageViewer->saveState(false);
			
		} else if ($_SERVER["QUERY_STRING"] == "logout" && 
				   $this->pageViewer->loggedIn() == false) {
				   	
			$this->pageViewer->redirectURL();
			
		}
	}
	
	/**
	 * @return string HTML - decides which HTML-controllers to view.
	 * 				  Ex: login-form / logged in page
	 */
	public function decidePage() {
		$this->pageViewer->saveUsername();
		
		if ($this->pageViewer->loggedIn() === true) {
			return $this->pageViewer->getLoggedInHTML("");
		}
		else if (isset($_POST["login"])) {
			
			
			$this->message = $this->tryLogin();
			if ($this->message === "Inloggning lyckades") {
				
				$this->pageViewer->saveState(true);
				
				return $this->pageViewer->getLoggedInHTML($this->message);
			}
		} 
		
		return $this->pageViewer->getLoginForm($this->message);
		
	}
	
	
	/**
	 * @return String - log message if username & password is correct or not
	 */
	private function tryLogin() {
		if ($_POST["user"] == "") {
			return "Användarnamn saknas";
		}
		else if ($_POST["pass"] == "") {
			return "Lösenord saknas";
		}
		else if ($_POST["user"] != "Admin" || $_POST["pass"] != "Password") {
			return "Felaktigt användarnamn och/eller lösenord";
		}
		return "Inloggning lyckades";
	}
	
}
