<?php
include 'userLogin.php';
include 'html_config.php';


if ($isLogin)
{
	$_key = $_COOKIE['login']; 
	$userinfo = $Login->getUserInfo($_key);
	$userinfo = json_decode($userinfo);
	if($userinfo->role == "admin")
	{
		include ('header.php');
		include ('menu.php');
		include ('content.php');
		include ('footer.php');
	}
	else 
	{
		echo $head;
		echo $startBody;
		echo $loginForm;
		echo $endBody;
	}
}
else 
{
	echo $head;
	echo $startBody;
	echo $loginForm;
	echo $endBody;
}
?>