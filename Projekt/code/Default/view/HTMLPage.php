<?php

class HTMLPage {
	/**
	 * @param String HTML
	 */
	public function getHTMLPage($body) {
		echo "<!DOCTYPE html>
			<html lang='sv'>
				<head>
					<meta charset='UTF-8' />
					<link href='css/bootstrap/css/bootstrap.css' type='text/css' rel='stylesheet' />
					<link href='css/Stylesheet.css' type='text/css' rel='stylesheet' />
					<link rel='shortcut icon' href='css/rosett-favicon.png' type='image/png' />
					<title>Janinas PHP-Projektblogg</title>
				</head>
				<body>
					<div id='container'>
						<h1>Janinas PHP-Projektblogg</h1>
						$body
					</div>
				</body>
			</html>";
	}
	/**
	 * @param string HTML
	 * @return string HTML
	 */
	public function getContentDiv($content) {
		return "<div id='content'>$content</div>";
	}
}