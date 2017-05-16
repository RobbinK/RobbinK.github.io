<?php
ini_set('error_reporting', E_ALL);
  error_reporting(E_ALL);
  ini_set('log_errors',TRUE);
  ini_set('html_errors',FALSE);
  ini_set('error_log','error_log.txt');
  ini_set('display_errors',FALSE);
include 'userLogin.php';
include 'html_config.php';
if(isset($_GET["id"]))
{
	$key = $_GET["id"];
	
	if($Login->LogoutUser($key))
	{
		header("location: ../");
	}
	else 
	{
		echo $head;
		echo $startBody;
		echo $logoutError;
		echo $endBody;
	}
}
else 
{
	header("location: .");
}
?>