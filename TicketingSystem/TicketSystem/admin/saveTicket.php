<?php
  ini_set('error_reporting', E_ALL);
  error_reporting(E_ALL);
  ini_set('log_errors',TRUE);
  ini_set('html_errors',FALSE);
  ini_set('error_log','error_log.txt');
  ini_set('display_errors',FALSE);
class saveTicket
{
	var $db;
	
	function __construct() 
	{
		$this->db = mysql_connect(DB_HOST,DB_USER,DB_PASS);
		mysql_select_db(DB_NAME);
	}
	
	public function saveNewTicket($email,$name,$lastname,$department,$priorety,$subject,$message)
	{
		$addNewUser = self::addNewUser($email, $name, $lastname);
		self::ATC();
		if($addNewUser == "0")
		{
			return false;
		}
		$date = date('YmdHis');
		if($priorety == "very high" or $priorety == "Very high")
		{
			$_subject = "New very high ticket";
			$to = self::TO($department);
			for($i = 0; $i < count($to); $i++)
			{
				mail($to[$i], $_subject,"DEPARTMENT: " . $department . " MESSAGE: " .$message);
			}
		}
		$message = urlencode($message);
		$query = "INSERT INTO tickets (userId,subject,msg,department,priorety,status,dateAded,dateActivity) VALUES ('$addNewUser','$subject','$message','$department','$priorety','open','$date','$date')";
		if(mysql_query($query))
		{
			return true;
		}
		else 
		{
			return false;
		}
	}
	
	private function ATC() // AnsweredToClosed
	{
		 $end = date("Y-m-d",strtotime("-10 day"));
		 $sql = mysql_query('SELECT dateActivity,dateClosed,status FROM tickets');
		 $date = date("YmdHis");	
		 while ($arr = mysql_fetch_array($sql))
		{
			$delete = mysql_query("UPDATE tickets SET status='closed',dateClosed='$date' WHERE status='answered' AND dateActivity < '$end'");
		}
	}
	
	private function TO($department)
	{
		$query = mysql_query("SELECT email,moderatorRole FROM ticketusers WHERE role='admin' AND moderatorRole='$department'");
		while ($result = mysql_fetch_array($query))
		{
			if($result["moderatorRole"] == $department)
			{
				$emails[] = $result["email"];
			}
		}
		
		return $emails;
	}
	
	private function addNewUser($email,$name,$lastname)
	{
		$query = "SELECT email,id,numTickets FROM ticketusers WHERE email='$email'";
		if($select = mysql_query($query))
		{
			while ($result = mysql_fetch_array($select))
			{
				if($result["email"] == $email)
				{
					$while = true;
					if($result["numTickets"] == "" or $result["numTickets"] == null or empty($result["numTickets"]))
					{
						$numTickets = 1;
					}
					else 
					{
						$numTickets = $result["numTickets"] + 1;
					}
					mysql_query("UPDATE ticketusers SET numTickets='$numTickets' WHERE email='$email'");
					return $result["id"];
				}
				else 
				{
					$while = false;
				}
			}
		}
		else
		{
			$_password = md5($email);
			$password = substr($_password, 0, 5);
			$_password = md5($password);
			$date = date('YmdHis');
			$query  = "INSERT INTO ticketusers (name,lastname,email,password,role,isLogin,date) VALUES ('$name','$lastname','$email','$_password','user','0','$date')";
			if(mysql_query($query))
			{
				$select = mysql_query("SELECT email,id,numTickets FROM ticketusers WHERE email='$email'");
				while ($result = mysql_fetch_array($select))
				{
					if($result["email"] == $email)
					{
						if($result["numTickets"] == "" or $result["numTickets"] == null or empty($result["numTickets"]))
						{
							$numTickets = 1;
						}
						else 
						{
							$numTickets = $result["numTickets"] + 1;
						}
						mysql_query("UPDATE ticketusers SET numTickets='$numTickets' WHERE email='$email'");
						$while = true;
						$send_mail = mail($email,"Hi ".$name." ".$lastname." see your ticket username and password","Your username: ".$email." Your password: ".$password);
						if($send_mail)
						{
							return $result["id"];
						}
						else 
						{
							return "0";
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
				return "0";
			}
		}
		if(@$while != true)
		{
			$_password = md5($email);
			$password = substr($_password, 0, 5);
			$_password = md5($password);
			$date = date('YmdHis');
			$query  = "INSERT INTO ticketusers (name,lastname,email,password,role,isLogin,date) VALUES ('$name','$lastname','$email','$_password','user','0','$date')";
			if(mysql_query($query))
			{
				$select = mysql_query("SELECT email,id,numTickets FROM ticketusers WHERE email='$email'");
				while ($result = mysql_fetch_array($select))
				{
					if($result["email"] == $email)
					{
						if($result["numTickets"] == "" or $result["numTickets"] == null or empty($result["numTickets"]))
						{
							$numTickets = 1;
						}
						else 
						{
							$numTickets = $result["numTickets"] + 1;
						}
						mysql_query("UPDATE ticketusers SET numTickets='$numTickets' WHERE email='$email'");
						$send_mail = mail($email,"Hi ".$name." ".$lastname." see your ticket username and password","Your username: ".$email." Your password: ".$password);
						if($send_mail)
						{
							return $result["id"];
						}
						else 
						{
							return "0";
						}
					}
				}
			}
			else 
			{
				return "0";
			}			
		}
	}
}
$newTicket = new saveTicket();
?>