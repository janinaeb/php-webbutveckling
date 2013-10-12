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
					<title>Laboration 3 - Webbutveckling med PHP</title>
				</head>
				<body>
					<h1>Laboration 3 - jb222qp</h1>
					$body
				</body>
			</html>";
	}
}