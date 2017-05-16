<?php
  ini_set('error_reporting', E_ALL);
  error_reporting(E_ALL);
  ini_set('log_errors',TRUE);
  ini_set('html_errors',FALSE);
  ini_set('error_log','error_log.txt');
  ini_set('display_errors',FALSE);
include 'config.php';
class Login 
{
	var $db;
	function __construct() 
	{
		$this->db = mysql_connect(DB_HOST,DB_USER,DB_PASS);
		mysql_select_db(DB_NAME);
	}
	
	public function _isLogin()
	{
		if(isset($_COOKIE['login']))
		{
			$key = $_COOKIE['login'];
			$query = "SELECT userKey,isLogin  FROM  ticketusers WHERE userKey='$key' AND isLogin='1'";
			if($select = mysql_query($query))
			{
				while ($result = mysql_fetch_array($select))
				{
					if($result["userKey"] == $key and $result["isLogin"] == "1")
					{
						$while = true;
						return true;
					}
					else 
					{
						$while = false;
					}
				}
			}
			else
			{
				return false;
			}
			if(@$while != true)
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}
	
	public function userRole($email)
	{
		$query = "SELECT email,role FROM  ticketusers WHERE email='$email'";
		if($select = mysql_query($query))
		{
			while ($result = mysql_fetch_array($select))
			{
				if($email == $result["email"])
				{
					return $result["role"];
				}
			}
		}
	}
	
	public function LoginUser($username,$password,$role)
	{
		
		$password = md5($password);
		if($role == "admin")
		{
			$query = "SELECT email,password,userKey,role FROM  ticketusers WHERE email='$username' AND password='$password' AND role='$role'";
		}
		elseif ($role == "user")
		{
			$query = "SELECT email,password,userKey,role FROM  ticketusers WHERE email='$username' AND password='$password' AND role='$role'";
		}
		else 
		{
			$query = "";
		}
		if($select = mysql_query($query))
		{
			while ($result = mysql_fetch_array($select))
			{
				if($username == $result["email"] and $password == $result["password"])
				{
					$while = true;
					$CK_VALUE = date('YmdHis');
					$CK_VALUE = md5($CK_VALUE);
					$cookie = setcookie ("login", $CK_VALUE, time()+60*60*24*10,'/');
					$update = mysql_query("UPDATE  ticketusers SET isLogin='1', userKey='$CK_VALUE' WHERE email='$username' AND password='$password'");
					if($update and $cookie)
					{
						return true;
					}
					else 
					{
						return false;
					}
				}
				else 
				{
					$while = false;
				}
			}
		}
		else
		{
			return false;
		}
		if(@$while != true)
		{
			return false;
		}
	}
	
	public function getUserInfo($key)
	{
		$query = "SELECT * FROM  ticketusers WHERE userKey='$key'";
		if($select = mysql_query($query))
		{
			while ($result = mysql_fetch_array($select))
			{
				if($key == $result["userKey"])
				{
					$_result = "true";					
					$id = $result["id"];
					$name = $result["name"];
					$lastname = $result["lastname"];
					$email = $result["email"];
					$userKey = $result["userKey"];
					$numTickets = $result["numTickets"];
					$role = $result["role"];
					$date = $result["date"];
					$isBlock = $result["isBlock"];
					$moderatorRole = $result["moderatorRole"];
					
					$array = array
					(
					"result" => $_result,
					"id" => $id,
					"name" => $name,
					"lastname" => $lastname,
					"email" => $email,
					"userKey" => $userKey,
					"numTickets" => $numTickets,
					"role" => $role,
					"isBlock" => $isBlock,
					"moderatorRole" => $moderatorRole,
					"date" => $date
					);
					
					$json = json_encode($array);
					return $json;
				}
				else 
				{
					$while = false;
				}
			}
		}
		else
		{
			$_result = "false";
			$array = array
			(
				"result" => $_result
			);
			$json = json_encode($array);
			return $json;
		}
		if(@$while != true)
		{
			$_result = "false";
			$array = array
			(
				"result" => $_result
			);
			$json = json_encode($array);
			return $json;
		}
	}
	
	public function LogoutUser($key)
	{
		$exp = explode(":",$key);
		$query = mysql_query("SELECT userKey, isLogin, id FROM ticketusers");
		while($result = mysql_fetch_array($query))
		{
			if($result["userKey"] == $exp[0] and $result["id"] == $exp[1])
			{
				$update = "UPDATE ticketusers SET isLogin='0' WHERE userKey='$exp[0]' AND id='$exp[1]'";
				if(mysql_query($update))
				{
					$cookie = setcookie ("login", '', time()+60,'/');
					$while = true;
					return true;
				}
				else
				{
					return false;
				}
			}
			else
			{
				$while = false;
			}
		}
		if(@$while != true)
		{
			return false;
		}
	}
	
}
$Login = new Login();
$isLogin = $Login->_isLogin();
?>