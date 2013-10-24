<?php
ini_set('display_errors', 1);  
error_reporting(E_ALL);

require_once("Default/view/HTMLPage.php");
require_once("Default/view/Menu.php");

require_once("Login/view/LoginView.php");
require_once("Login/view/CookieHandler.php");
require_once("Login/view/Session.php");
require_once("Login/view/DateMaker.php");
require_once("Login/controller/LoginController.php");
require_once("Login/controller/Init.php");
require_once("Login/model/LoginModel.php");
require_once("Login/model/UserValidation.php");


require_once("Login/model/UserDAL.php");
require_once("Login/view/Message.php");
require_once("Login/view/RegisterView.php");
require_once("Login/controller/RegisterController.php");
require_once("Login/model/User.php");

$init = new Init();
$init->run();
