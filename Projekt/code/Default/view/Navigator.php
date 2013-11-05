<?php

class Navigator {
	/**
	 * redirects to homepage
	 */
	public function redirect() {
		header("Location: index.php");
	}
}