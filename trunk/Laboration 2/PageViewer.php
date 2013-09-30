<?php

class PageViewer {
	/**
	 * @param $body string HTML body
	 */
	public function getHTML($body) {
		echo "<!DOCTYPE html>
			<html lang='sv'>
				<head>
					<meta charset='UTF-8' />
					<title>Laboration 2 - Webbutveckling med PHP</title>
				</head>
				<body>
					<h1>Laboration 1 - jb222qp</h1>
					$body
				</body>
			</html>";
	}
	
	/**
	 * @return string HTML login form
	 */
	public function getLoginForm($message) {
		
		return "<h2>Ej Inloggad</h2>
			<form method='post'>
				<fieldset>
					<legend>Login - Skriv in användarnamn och lösenord</legend>
						
					<p>$message</p>
					
					<label for='user'>Användarnamn: </label>
					<input type='text' id='user' name='user' value='" . $this->getUsername() . "' />
					
					<label for='pass'>Lösenord: </label>
					<input type='password' id='pass' name='pass' />
					
					<label for='save'>Håll mig inloggad: </label>
					<input type='checkbox' id='save' name='save' />
					
					<input type='submit' value='Logga in' name='login' />
				</fieldset>
			</form>";
	}
	
	/**
	 * @return string HTML logged in page
	 */
	public function getLoggedInHTML($message) {
		return "<h2>" . $this->getUsername() . " är inloggad</h2>
			<p>$message</p>
			<a href='index.php?logout'>Logga ut</a>";
	}
	
	/**
	 * @param $loggedIn boolean - if logged in is set to true or not
	 */
	public function saveState($loggedIn) {
		if ($loggedIn === true) {
			$_SESSION["logged-in"] = $loggedIn;
		} else {
			unset($_SESSION["logged-in"]);
		}
		
	}
	
	/**
	 * @return Boolean returns true if logged in is set to true in session
	 */
	public function loggedIn() {
		if (isset($_SESSION["logged-in"]) && $_SESSION["logged-in"] = true) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Saves username in session variable "username" from post if it is set
	 */
	public function saveUsername() {
		if (isset($_POST["user"])) {
			$_SESSION["username"] = $_POST["user"];
		}
		
	}
	
	/**
	 * @return String - username saved in session variable if it is set, 
	 * 									else it returns an empty string
	 */
	private function getUsername() {
		if (isset($_SESSION["username"])) {
			return $_SESSION["username"];
		}
		return "";
	}
	
	/**
	 * Redirect the page to index.php before showing output
	 */
	public function redirectURL() {
		header("Location: index.php");
	}
}
