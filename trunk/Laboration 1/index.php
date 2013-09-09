<?php

// Dagens namn på svenska
$dayOfWeek = date("N");
switch ($dayOfWeek) {
	case 1:
		$dayOfWeek = "Måndag";
		break;
	case 2:
		$dayOfWeek = "Tisdag";
		break;
	case 3:
		$dayOfWeek = "Onsdag";
		break;
	case 4:
		$dayOfWeek = "Torsdag";
		break;
	case 5:
		$dayOfWeek = "Fredag";
		break;
	case 6:
		$dayOfWeek = "Lördag";
		break;
	case 7:
		$dayOfWeek = "Söndag";
		break;
}

// Dagens nr
$day = date("j");

// Månad
$month = date("n");
switch ($month) {
	case 1:
		$month = "Januari";
		break;
	case 2:
		$month = "Februari";
		break;
	case 3:
		$month = "Mars";
		break;
	case 4:
		$month = "April";
		break;
	case 5:
		$month = "Maj";
		break;
	case 6:
		$month = "Juni";
		break;
	case 7:
		$month = "Juli";
		break;
	case 8:
		$month = "Augusti";
		break;
	case 9:
		$month = "September";
		break;
	case 10:
		$month = "Oktober";
		break;
	case 11:
		$month = "November";
		break;
	case 12:
		$month = "December";
		break;
}

// År
$year = date("Y");

// Klockan
date_default_timezone_set("CET");
$time = date("H\:i\:s");




// Vid submit
$errorMessage = "";

if ($_SERVER["QUERY_STRING"] == "logout") {
	$errorMessage = "Du har nu loggat ut";
}

if (isset($_POST["login"])) {
	// Testar om användarnamn eller lösenord är tomma
	if ($_POST["user"] == "") {
		$errorMessage = "Användarnamn saknas";
	}
	else if ($_POST["pass"] == "") {
		$errorMessage = "Lösenord saknas.";
	}
	// Testar så rätt användarnamn och lösenord matats in
	else if ($_POST["user"] != "Admin" || $_POST["pass"] != "Password") {
		$errorMessage = "Felaktigt användarnamn och/eller lösenord";
	}
	// Bestämmer om formuläret ska visas eller inte
	
	if ($errorMessage == "" || $errorMessage == "Du har nu loggat ut") {
		$errorMessage = "clear";
	}
}


if ($errorMessage == "clear") {
	// Visa inloggad-sidan
	echo "<!DOCTYPE html>
		<html lang='sv'>
			<head>
				<meta charset='UTF-8' />
				<title>Laboration 1 - Webbutveckling med PHP</title>
			</head>
			<body>
				<h1>Laboration 1 - jb222qp</h1>
				<h2>" . $_POST["user"] . " är inloggad</h2>
				<p>$message</p>
				<a href='" . $_SERVER["PHP_SELF"] . "?logout'>Logga ut</a>
				<p>$dayOfWeek, den $day $month år $year. Klockan är [$time].</p>
			
			</body>
		</html>
	";
	
} else {
	// Skriv ut dokumentet med formulär
	echo "<!DOCTYPE html>
		<html lang='sv'>
			<head>
				<meta charset='UTF-8' />
				<title>Laboration 1 - Webbutveckling med PHP</title>
			</head>
			<body>
			<h1>Laboration 1 - jb222qp</h1>
			<h2>Ej Inloggad</h2>
			<form method='post'>
				<fieldset>
					<legend>Login - Skriv in användarnamn och lösenord</legend>
					
					<p>$errorMessage</p>
					
					<label for='user'>Användarnamn: </label>
					<input type='text' id='user' name='user' value='" . $_POST["user"] . "' />
					
					<label for='pass'>Lösenord: </label>
					<input type='password' id='pass' name='pass' />
					
					<label for='save'>Håll mig inloggad: </label>
					<input type='checkbox' id='save' name='save' />
					
					<input type='submit' value='Logga in' name='login' />
				</fieldset>
			</form>
			<p>$dayOfWeek, den $day $month år $year. Klockan är [$time].</p>
			</body>
		</html>
	";
}

