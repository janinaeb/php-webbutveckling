<?php

class Navigation {
	/**
	 * Redirects the page to remove query strings
	 */
	public function reloadToFrontPage() {
		header("Location: index.php");
	}
}