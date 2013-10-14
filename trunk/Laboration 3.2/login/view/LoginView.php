<?php

namespace login\view;

require_once("./common/Filter.php");
require_once("./login/model/LoginObserver.php");

class LoginView implements \login\model\LoginObserver {

	private static $LOGOUT = "logout";
	private static $LOGIN = "login";
	private static $USERNAME = "LoginView::UserName";
	private static $PASSWORD = "LoginView::Password";
	private static $CHECKED = "LoginView::Checked";
	
	private $message = "";

	private static $registerString = "register";
	private static $registerButton = "registerButton";
	private static $registerUsername = "LoginView::Username";
	private static $registerPassword = "LoginView::RegisterPassword";
	private static $registerPasswordAgain = "LoginView::RegisterPasswordAgain";
	
	private static $homeAddress = "index.php";
	private static $registerSuccess = false;
	
	public function registerSucceeded() {
		return self::$registerSuccess;
	}
	public function getLoginBox() {
		if ($this->registerSucceeded()) {
			$user = $this->getRegisterUserName(true);
		} else {
			$user = $this->getUserName();
		}
		
		$checked = $this->userWantsToBeRemembered() ? "checked=checked" : "";
		
		$html = "
			<form action='?" . self::$LOGIN . "' method='post' enctype='multipart/form-data'>
				<fieldset>
					$this->message
					<p><a href='?" . self::$registerString . "'>Registrera ny användare</a></p>
					<legend>Login - Skriv in användarnamn och lösenord</legend>
					<label for='UserNameID' >Användarnamn :</label>
					<input type='text' size='20' name='" . self::$USERNAME . "' id='UserNameID' value='$user' />
					<label for='PasswordID' >Lösenord  :</label>
					<input type='password' size='20' name='" . self::$PASSWORD . "' id='PasswordID' value='' />
					<label for='AutologinID' >Håll mig inloggad  :</label>
					<input type='checkbox' name='" . self::$CHECKED . "' id='AutologinID' $checked/>
					<input type='submit' name=''  value='Logga in' />
				</fieldset>
			</form>";
		return $html;
	}	

	public function getRegisterBox () {
		$user = $this->getRegisterUserName(true);

		$html = "
			<p><a href='" . self::$homeAddress . "'>Tillbaka</a></p>
			<h1>Ej inloggad, Registrerar användare</h1>
			<form method='post' enctype='multipart/form-data'>
			<fieldset>
				$this->message
				<legend>Registrera ny användare - Skriv in användarnamn och lösenord</legend>
				<label for='UserNameID' >Namn :</label>
				<input type='text' size='20' name='" . self::$registerUsername . "' id='UserNameID' value='$user' />
				<br/>
				<label for='PasswordID' >Lösenord  :</label>
				<input type='password' size='20' name='" . self::$registerPassword . "' id='PasswordID' value='' />
				<br/>
				<label for='PasswordIDAgain' >Repetera lösenord  :</label>
				<input type='password' size='20' name='" . self::$registerPasswordAgain . "' id='PasswordIDAgain' value='' />
				<br/>
				<input type='submit' name='" . self::$registerButton . "'  value='Registrera' />
				</fieldset>
			</form>";
		return $html;
	}
	public function isPressingRegister() {
		return isset($_POST[self::$registerButton]);
	}
	public function isRegistring() {
		return isset($_GET[self::$registerString]);
	}
	
	public function isLoggingOut() {
		return isset($_GET[self::$LOGOUT]);
	}
	
	public function isLoggingIn() {
		if (isset($_GET[self::$LOGIN])) {
			return true;
		} else if ($this->hasCookies()) {
			return true;
		} else {
			return false;
		}
	}
	
	public function getLogoutButton() {
		return "$this->message <a href='?" . self::$LOGOUT . "'>Logga ut</a>";
	}
	
	public function getUserCredentials() {
		if ($this->hasCookies()) {
			return \login\model\UserCredentials::createWithTempPassword(new \login\model\UserName($this->getUserName()), 
																	  $this->getTemporaryPassword());
		} else {
			if (isset($_GET[self::$registerString])) {
				return \login\model\UserCredentials::createFromClientData(new \login\model\UserName($this->getRegisterUserName(false)), 
													\login\model\Password::fromCleartext($this->getPassword(self::$registerPassword)));
			}
			return \login\model\UserCredentials::createFromClientData(new \login\model\UserName($this->getUserName()), 
													\login\model\Password::fromCleartext($this->getPassword(self::$PASSWORD)));
		}
	}
	public function passwordsMatch () {
		if ($this->getPassword(self::$registerPassword) !== 
			$this->getPassword(self::$registerPasswordAgain)) {
			
			return false;
		}
		return true;
	}
	public function loginFailed() {
		if ($this->hasCookies()) {
			$this->message = "<p>Felaktig information i cookie</p>";
			$this->removeCookies();
		} else { 
			if ($this->getUserName() == "") {
				$this->message = "<p>Användarnamn saknas</p>";
			} else if ($this->getPassword(self::$PASSWORD) == "") {
				$this->message = "<p>Lösenord saknas</p>";
			} else {
				$this->message = "<p>Felaktigt användarnamn och/eller lösenord</p>";
			}
		}
	}
	
	public function registerFailed() {
		$messageAltered = false;
		if (\login\model\UserName::isUsernameTooShort($this->getRegisterUserName(false))) {
			$this->message .= "<p>Användarnamnet har för få tecken. Minst 3 tecken</p>";
			$messageAltered = true;
		} else if (\login\model\UserName::usernameContainsTags($this->getRegisterUsername(false))) {
			$this->message .= "<p>Användarnamnet innehåller ogiltiga tecken</p>";
			$messageAltered = true;
		} else if (\login\model\UserName::isUsernameTooLong($this->getRegisterUserName(false))) {
			$this->message .= "<p>Användarnamnet har för många tecken. Max 9 tecken</p>";
			$messageAltered = true;
		}
		if (\login\model\Password::isPasswordTooShort($this->getPassword(self::$registerPassword))) {
			$this->message .= "<p>Lösenorden har för få tecken. Minst 6 tecken</p>";
			$messageAltered = true;
		} else if (\login\model\Password::isPasswordTooLong($this->getPassword(self::$registerPassword))) {
			$this->message .= "<p>Lösenordet har för många tecken. Max 16 tecken</p>";
			$messageAltered = true;
		} else if ($this->getPassword(self::$registerPassword) !==
				   	$this->getPassword(self::$registerPasswordAgain)) {	
			$this->message .= "<p>Lösenord matchar inte</p>";
			$messageAltered = true;
		} 
		
		if ($messageAltered == false) {
			$this->message = "<p>Användarnamnet är redan upptaget</p>";
		}
	}
	public function registerOK() {
		self::$registerSuccess = true;
		$this->message .= "<p>Registrering av ny användare lyckades</p>";
	}
	
	public function loginOK(\login\model\TemporaryPasswordServer $tempCookie) {

		if ($this->userWantsToBeRemembered() || 
			$this->hasCookies()) {
			if ($this->hasCookies()) {
				$this->message  = "<p>Inloggning lyckades via cookies</p>";
			} else {
				$this->message  = "<p>Inloggning lyckades och vi kommer ihåg dig nästa gång</p>";
			}
			
			$expire = $tempCookie->getExpireDate();
			setcookie(self::$USERNAME, $this->getUserName(), $expire);
			setcookie(self::$PASSWORD, $tempCookie->getTemporaryPassword(), $expire);
		} else {
			$this->message  = "<p>Inloggning lyckades</p>";
		} 
		
	}
	
	public function doLogout() {
		$this->removeCookies();
		
		$this->message  = "<p>Du har nu loggat ut</p>";
	}
	
	
	
	private function getUserName() {
		if (isset($_POST[self::$USERNAME]))
			return \Common\Filter::sanitizeString($_POST[self::$USERNAME]);
		else if (isset($_COOKIE[self::$USERNAME]))
			return \Common\Filter::sanitizeString($_COOKIE[self::$USERNAME]);
		else
			return "";
	}
	
	private function getRegisterUserName($sanitize) {
		if (isset($_POST[self::$registerUsername])) {
			if ($sanitize) {
				return \Common\Filter::sanitizeString($_POST[self::$registerUsername]);
			} else {
				return $_POST[self::$registerUsername];
			}
		}
		return "";
	}
	
	private function getPassword($postParam) {
		if (isset($_POST[$postParam]))
			return \Common\Filter::sanitizeString($_POST[$postParam]);
		else
			return "";
	}
	
	private function userWantsToBeRemembered() {
		return isset($_POST[self::$CHECKED]);
	}

	private function getTemporaryPassword() {
		if (isset($_COOKIE[self::$PASSWORD])) {
			$fromCookieString = \Common\Filter::sanitizeString($_COOKIE[self::$PASSWORD]);
			return \login\model\TemporaryPasswordClient::fromString($fromCookieString);
		} else {
			return \login\model\TemporaryPasswordClient::emptyPassword();
		}
	}
	
	private function hasCookies() {
		return isset($_COOKIE[self::$PASSWORD]) && isset($_COOKIE[self::$USERNAME]);
	}

	private function removeCookies() {

		unset($_COOKIE[self::$USERNAME]);
		unset($_COOKIE[self::$PASSWORD]);
			
		$expireNow = time()-1;
		setcookie(self::$USERNAME, "", $expireNow);
		setcookie(self::$PASSWORD, "", $expireNow);
	}
	
}
