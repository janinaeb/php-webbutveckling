<?php

class RegisterView {
	private $sessionHandler;
	
	const registerString = "register";
	private static $registerButton = "registerButton";
	private static $registerUsername = "LoginView::Username";
	private static $registerPassword = "LoginView::RegisterPassword";
	private static $registerPasswordAgain = "LoginView::RegisterPasswordAgain";
	
	private static $homeAddress = "index.php";
	private static $registerSuccess = false;
	
	public function __construct(Session $sessionHandler) {
		$this->sessionHandler = $sessionHandler;		
	}
	
	
	
	public function registerSucceeded() {
		return self::$registerSuccess;
	}
	
	public function getRegisterForm() {
		$user = $this->getRegisterUserName(true);

		return "<p><a href='" . self::$homeAddress . "'>Tillbaka</a></p>
			<h1>Ej inloggad, Registrerar användare</h1>
			<form method='post' enctype='multipart/form-data'>
			<fieldset>
				<p>" . $this->sessionHandler->getMessage() . "</p>
				<legend>Registrera ny användare - Skriv in användarnamn och lösenord</legend>
				<label for='UserNameID' >Namn :</label>
				<input type='text' name='" . self::$registerUsername . "' id='UserNameID' value='$user' />
				<br/>
				<label for='PasswordID' >Lösenord  :</label>
				<input type='password' name='" . self::$registerPassword . "' id='PasswordID' value='' />
				<br/>
				<label for='PasswordIDAgain' >Repetera lösenord  :</label>
				<input type='password' name='" . self::$registerPasswordAgain . "' id='PasswordIDAgain' value='' />
				<br/>
				<input type='submit' name='" . self::$registerButton . "'  value='Registrera' />
				</fieldset>
			</form>";
	}
	public function isPressingRegister() {
		return isset($_POST[self::$registerButton]);
	}
	public function isRegistring() {
		return isset($_GET[self::registerString]);
	}
	
	public function passwordsMatch () {
		if ($_POST[self::$registerPassword] !== 
			$_POST[self::$registerPasswordAgain]) {
			
			return false;
		}
		return true;
	}
	public function registerOK() {
		self::$registerSuccess = true;
		$this->sessionHandler->setMessage(Message::registerSuccess);
	}
	
	/**
	 * @param Boolean true if username is going to be sanitized before return
	 * @return String Username from POST if set
	 */
	public function getRegisterUserName($sanitize) {
		if (isset($_POST[self::$registerUsername])) {
			if ($sanitize) {
				return UserValidation::cleanString($_POST[self::$registerUsername]);
			} else {
				return $_POST[self::$registerUsername];
			}
		}
		return "";
	}
	public function getRegisterPassword() {
		return $_POST[self::$registerPassword];
	}
}
