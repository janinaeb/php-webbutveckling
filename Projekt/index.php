<?php
ini_set('display_errors', 1);  
error_reporting(E_ALL);

require_once("Default/view/HTMLPage.php");
require_once("Default/controller/Init.php");
require_once("Default/model/DAL.php");
require_once("Default/view/Session.php");
require_once("Default/view/Message.php");
require_once("Default/view/Navigator.php");

require_once("Login/view/LoginView.php");
require_once("Login/view/CookieHandler.php");
require_once("Login/view/DateMaker.php");
require_once("Login/controller/LoginController.php");

require_once("Login/model/LoginModel.php");
require_once("Login/model/UserValidation.php");

require_once("Login/model/UserDAL.php");
require_once("Login/view/RegisterView.php");
require_once("Login/controller/RegisterController.php");
require_once("Login/model/User.php");

require_once("Entries/controller/EntryController.php");
require_once("Entries/view/EntryView.php");
require_once("Entries/model/EntryDAL.php");
require_once("Entries/model/Entry.php");
require_once("Entries/model/EntryList.php");

require_once("Entries/Comments/controller/CommentController.php");
require_once("Entries/Comments/view/CommentView.php");
require_once("Entries/Comments/model/Comment.php");
require_once("Entries/Comments/model/CommentList.php");
require_once("Entries/Comments/model/CommentDAL.php");


$init = new Init();
$init->run();
