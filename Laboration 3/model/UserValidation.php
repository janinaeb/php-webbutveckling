<?php

class UserValidation {
	
	/**
	 * @param String
	 * @return Boolean true if username is set
	 */
	public function usernameNotEmpty($username) {
		if ($username != "") {
			return true;
		}
		return false;
	}
	
	/**
	 * @param String
	 * @return Boolean true if username is valid
	 */
	public function usernameExists($username) {
		if ($username == "Admin") {
			return true;
		}
		return false;
	}
	
	/**
	 * @param String
	 * @return Boolean true if password is set
	 */
	public function passwordNotEmpty($password) {
		if ($password != "") {
			return true;
		}
		return false;
	}
	
	/**
	 * @param String
	 * @return Boolean true if password is valid
	 */
	public function passwordExists($password) {
		if ($password == "Password") {
			return true;
		}
		return false;
	}
}
