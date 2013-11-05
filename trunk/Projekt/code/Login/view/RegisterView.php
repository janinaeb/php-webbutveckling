<?php

class RegisterView {
	/**
	 * @var Session object
	 */
	private $sessionHandler;
	
	/**
	 * @var const string
	 */
	const registerString = "register";
	
	/**
	 * @var string
	 */
	private static $registerButton = "registerButton";
	/**
	 * @var string
	 */
	private static $registerUsername = "LoginView::Username";
	/**
	 * @var string
	 */
	private static $registerPassword = "LoginView::RegisterPassword";
	/**
	 * @var string
	 */
	private static $registerPasswordAgain = "LoginView::RegisterPasswordAgain";
	/**
	 * @var string
	 */
	private static $homeAddress = "index.php";
	/**
	 * @var boolean if register is successful
	 */
	private static $registerSuccess = false;
	
	/**
	 * @param Session object
	 */
	public function __construct(Session $sessionHandler) {
		$this->sessionHandler = $sessionHandler;		
	}
	
	/**
	 * @return boolean
	 */
	public function registerSucceeded() {
		return self::$registerSuccess;
	}
	
	/**
	 * @return string HTML
	 */
	public function getRegisterForm() {
		$user = $this->getRegisterUserName(true);

		return "<h2>Ej inloggad, Registrerar användare</h2>
			<form method='post' enctype='multipart/form-data'>
				<h3>Registrera ny användare - Skriv in användarnamn och lösenord</h3>
				<p>" . $this->sessionHandler->getMessage() . "</p>
				<label for='UserNameID' >Namn :</label>
				<input type='text' class='input-block-level' name='" . self::$registerUsername . "' id='UserNameID' value='$user' />
				<label for='PasswordID' >Lösenord  :</label>
				<input type='password' class='input-block-level' name='" . self::$registerPassword . "' id='PasswordID' value='' />
				<label for='PasswordIDAgain' >Repetera lösenord  :</label>
				<input type='password' class='input-block-level' name='" . self::$registerPasswordAgain . "' id='PasswordIDAgain' value='' />
				<input type='submit' class='btn btn-primary margin' name='" . self::$registerButton . "'  value='Registrera' />
			</form>
			<p><a href='" . self::$homeAddress . "'>Tillbaka</a></p>";
	}

	/**
	 * @return boolean true if post is set
	 */
	public function isPressingRegister() {
		return isset($_POST[self::$registerButton]);
	}
	/**
	 * @return boolean true if get is set
	 */
	public function isRegistring() {
		return isset($_GET[self::registerString]);
	}
	/**
	 * @return boolean true if passwords match
	 */
	public function passwordsMatch () {
		if ($_POST[self::$registerPassword] !== 
			$_POST[self::$registerPasswordAgain]) {
			
			return false;
		}
		return true;
	}
	/**
	 * Manages successful register
	 */
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
	/**
	 * @return string from post
	 */
	public function getRegisterPassword() {
		return $_POST[self::$registerPassword];
	}
}
