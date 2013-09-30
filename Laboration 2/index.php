<?php

require_once("DateMaker.php");
require_once("PageViewer.php");
require_once("LoginControl.php");

ini_set('display_errors', 1);  
error_reporting(E_ALL);

session_start();

$pageViewer = new PageViewer();

$loginControl = new LoginControl($pageViewer);

$loginControl->checkPageState();
$html = $loginControl->decidePage();


$dateMaker = new DateMaker();
$dateString = $dateMaker->getDateString();

$html .= $dateString;


if (isset($_POST["login"])) {
	
}


$pageViewer->getHTML($html);


/*

if ($_SERVER["QUERY_STRING"] == "logout" && $_SESSION["logged-in"] == true) {
	$errorMessage = "Du har nu loggat ut";
	
	// "Stäng av" inloggad
	$_SESSION["logged-in"] = false;
	
}

// Vid submit
if (isset($_POST["login"])) {
	
		
		// Spara inloggning i sessionen
		$_SESSION["logged-in"] = true;
	}
}


if (isset($_SESSION["logged-in"]) && $_SESSION["logged-in"] == true) {
	
	// Visa inloggad-sidan
	
	
}

*/


