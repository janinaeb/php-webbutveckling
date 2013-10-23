<?php
ini_set('display_errors', 1);  
error_reporting(E_ALL);

require_once("view/HTMLPage.php");
require_once("view/LoginView.php");
require_once("view/CookieHandler.php");
require_once("view/Session.php");
require_once("view/DateMaker.php");
require_once("controller/LoginController.php");
require_once("controller/Init.php");
require_once("model/LoginModel.php");
require_once("model/UserValidation.php");


require_once("model/UserDAL.php");
require_once("view/Message.php");
require_once("view/RegisterView.php");
require_once("controller/RegisterController.php");

$init = new Init();
$init->run();
