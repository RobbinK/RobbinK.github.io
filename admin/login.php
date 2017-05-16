<?php
ini_set('error_reporting', E_ALL);
  error_reporting(E_ALL);
  ini_set('log_errors',TRUE);
  ini_set('html_errors',FALSE);
  ini_set('error_log','error_log.txt');
  ini_set('display_errors',FALSE);
include 'userLogin.php';
include 'html_config.php'; 
if(isset($_POST["login"]))
{
	$email = $_POST["email"];
	$password = $_POST["password"];
	$role = $Login->userRole($email);
	if($role == "admin")
	{
		if($Login->LoginUser($email,$password,$role))
		{
			header("location: .");
		}
		else 
		{
			echo $head;
			echo $startBody;
			echo $loginError;
			echo $loginForm;
			echo $endBody;
		}
	}
	else 
	{
		echo $head;
		echo $startBody;
		echo $loginError;
		echo $loginForm;
		echo $endBody;
	}
		
}
else 
{
	if($isLogin)
	{
		header("location: .");
	}
	else 
	{
		echo $head;
		echo $startBody;
		echo $loginForm;
		echo $endBody;
	}
}
?>