<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<meta name="robots" content="noindex">
<meta name="googlebot" content="noindex">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<script type="text/javascript">
$(document).ready(function(){ $('#myModal').hide(); });
$('#myModal').modal({
	  keyboard: false
	});
$('#myModal').modal('show');
	ini_set("include_path", '/home/robbinkm/php:' . ini_get("include_path") );
</script>

<link rel="stylesheet" type="text/css" href="../css/bootstrap.css" />
<link rel="stylesheet" type="text/css" href="../css/bootstrap-responsive.css" />
<link rel="stylesheet" type="text/css" href="../css/myCSS.css" />

<title>Tickets</title>

<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->

</head>
<body>
<?php
ini_set('error_reporting', E_ALL);
error_reporting(E_ALL);
ini_set('log_errors',TRUE);
ini_set('html_errors',FALSE);
ini_set('error_log','error_log.txt');
ini_set('display_errors',FALSE);
include '../admin/config.php';
class Install
{
	var $db;
	function __construct()
	{
		$this->db = mysql_connect(DB_HOST,DB_USER,DB_PASS);
		mysql_select_db(DB_NAME);
	}
	
	public function createTables()
	{
		$usersTable = "CREATE TABLE IF NOT EXISTS ticketusers
						(
						id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
						name TEXT,
						lastname TEXT,
						email TEXT,
						password TEXT,
						role TEXT,
						moderatorRole TEXT,
						isLogin SMALLINT NOT NULL,
						isBlock SMALLINT NOT NULL,
						userKey TEXT,
						numTickets INT,
						date DATETIME NOT NULL
						)";
	
		if(mysql_query($usersTable))
		{
			$ticketsTable = "CREATE TABLE IF NOT EXISTS tickets 
						(
						id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
						userId SMALLINT NOT NULL,
						subject TEXT,
						msg longtext,
						department TEXT,
						priorety TEXT,
						status TEXT,
						dateAded DATETIME NOT NULL,
						dateClosed DATETIME NOT NULL,
						dateActivity DATETIME NOT NULL
						)";
			if(mysql_query($ticketsTable))
			{
				$ticketAnswerTable = "CREATE TABLE IF NOT EXISTS ticketanswer 
							(
							id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
							ticketId SMALLINT NOT NULL,
							userId SMALLINT,
							msg longtext,
							moderator TEXT,
							dateAded DATETIME NOT NULL
							)";
				if(mysql_query($ticketAnswerTable))
				{
					return "<div class=\"alert alert-success\"><b><center> Well done!</b> Tables have been created</center></div>";
				}
				else 
				{
					return "<div class=\"alert alert-error\"><b><center> ERROR: </b>".mysql_error()."</center></div>";
				}
			}
			else 
			{
				return "<div class=\"alert alert-error\"><b><center> ERROR: </b>".mysql_error()."</center></div>";
			}
		}
		else 
		{
			return "<div class=\"alert alert-error\"><b><center> ERROR: </b>".mysql_error()."</center></div>";
		}
	}
	
	public function _install($email,$name,$lastname,$password)
	{
		$password = md5($password);
		$date = date('YmdHis');
		
		$query = "INSERT INTO ticketusers (name,lastname,email,password,role,moderatorRole,isLogin,isBlock,date) VALUES ('$name','$lastname','$email','$password','admin','admin','0','0','$date');";
		if(mysql_query($query))
		{
			return true;
		}
		else 
		{
			return false;
		}
	}
	
}
if(isset($_POST["install"]))
{
	$email = $_POST["email"];
	$name = $_POST["name"];
	$lastname = $_POST["lname"];
	$password = $_POST["password"];
	$install = new Install();
	echo $install->createTables();
	if($install->_install($email, $name, $lastname, $password))
	{
		mail($email, "Hi ". $name. " " . $lastname .". Ticket Installation is completed", "username: ".$email." password: ".$password,"FROM: noreplay@doitflash.com");
		echo "<div class=\"alert alert-info\"><center><b>Installation is completed</b></center></div>";
	}
	else 
	{
		echo "<div class=\"alert alert-error\"><center><b>Installation is not completed</b></center></div>";
	}
}
?>
</body>
</html>
