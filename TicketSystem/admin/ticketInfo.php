<?php
class ticketInfo
{
	var $db;
	
	function __construct() 
	{
		$this->db = mysql_connect(DB_HOST,DB_USER,DB_PASS);
		mysql_select_db(DB_NAME);
	}
	
	public function getTickets()
	{
		$res = "false";
		$id = "";
		$subject = "";
		$msg = "";
		$department = "";
		$priorety = "";
		$status = "";
		$dateActivity = "";
		$dateAded = "";
		$dateClosed = "";
		$userId = "";
		
		$select = mysql_query("SELECT * FROM tickets ORDER BY dateActivity DESC, dateAded DESC");
		while ($result = mysql_fetch_array($select))
		{
			$id[] = $result["id"];
			$subject[] = $result["subject"];
			$msg[] = $result["msg"];
			$department[] = $result["department"];
			$priorety[] = $result["priorety"];
			$status[] = $result["status"];
			$dateAded[] = $result["dateAded"];
			$dateClosed[] = $result["dateClosed"];
			$dateActivity[] = $result["dateActivity"];
			$userId[] = $result["userId"];
			$res = "true";
		}
		
		$array = array
		(
		"id" => $id,
		"subject" => $subject,
		"msg" => $msg,
		"department" => $department,
		"priorety" => $priorety,
		"status" => $status,
		"dateAded" => $dateAded,
		"dateClosed" => $dateClosed,
		"dateActivity" => $dateActivity,
		"userId" => $userId,
		"res" => $res
		);
		
		$json = json_encode($array);
		
		return $json;
	}
	
	public function getAnswerTickets()
	{
		$res = "false";
		$id = "";
		$ticketId = "";
		$msg = "";
		$moderator = "";
		$dateAded = "";
		$select = mysql_query("SELECT * FROM ticketanswer ORDER BY dateAded DESC");
		while ($result = mysql_fetch_array($select))
		{
			if($result["moderator"] != "user")
			{
				$id[] = $result["id"];
				$ticketId[] = $result["ticketId"];
				$msg[] = $result["msg"];
				$moderator[] = $result["moderator"];
				$dateAded[] = $result["dateAded"];
				$res = "true";
			}
		}
		
		$array = array
		(
		"id" => $id,
		"ticketId" => $ticketId,
		"msg" => $msg,
		"moderator" => $moderator,
		"dateAded" => $dateAded,
		"res" => $res
		);
		
		$json = json_encode($array);
		
		return $json;
		
	}
	
	public function getAnswerId($ticketId,$dateActivity)
	{
		$select = mysql_query("SELECT id,ticketId,moderator,dateAded FROM ticketanswer WHERE ticketId='$ticketId' AND dateAded='$dateActivity'");
		while ($result = mysql_fetch_array($select))
		{
			if($ticketId == $result["ticketId"] and $result["moderator"] != "user" and $result["dateAded"] == $dateActivity)
			{
				return $result["id"];
			}
		}
	}
	
	public function getuser($userId)
	{
		$select = mysql_query("SELECT id, name, lastname, numTickets, moderatorRole, email FROM ticketusers WHERE id = '$userId'");
		while ($result = mysql_fetch_array($select))
		{
			if($result["id"] == $userId)
			{
				$array = array
				(
				"id" => $result["id"],
				"name" => $result["name"],
				"lastname" => $result["lastname"],
				"email" => $result["email"],
				"moderatorRole" => $result["moderatorRole"],
				"numTickets" => $result["numTickets"]
				);
				
				$json = json_encode($array);
				return $json;
			}
		}
	}
	
	public function getModeratorRoles()
	{
		$select = mysql_query("SELECT moderatorRole, role FROM ticketusers WHERE role='admin'");
		while ($result = mysql_fetch_array($select))
		{
			if($result["role"] == "admin")
			{
				$moderators[] = $result["moderatorRole"];
			}
		}
		
		$array = array
		(
		"moderators" => $moderators
		);
		
		$json = json_encode($array);
		return $json;
	}
	
	public function getUsers()
	{
		$res = "false";
		$id = "";
		$name = "";
		$lastname = "";
		$email = "";
		$numTickets = "";
		$date = "";
		$isBlock = "";
		$select = mysql_query("SELECT * FROM ticketusers WHERE role='user'");
		while ($result = mysql_fetch_array($select)) 
		{
			if($result["role"] == "user")
			{
				$res = "true";
				$id[] = $result["id"];
				$name[] = $result["name"];
				$lastname[] = $result["lastname"];
				$email[] = $result["email"];
				$numTickets[] = $result["numTickets"];
				$date[] = $result["date"];
				$isBlock[] = $result["isBlock"];
			}
		}
		
		$array = array
		(
		"id" => $id,
		"name" => $name,
		"lastname" => $lastname,
		"email" => $email,
		"numTickets" => $numTickets,
		"result" => $res,
		"isBlock" => $isBlock,
		"date" => $date
		);
		
		$json = json_encode($array);
		return $json;
	}
	
	// USER TICKET INFO FUNCTIONS
	
	public function getUserTicketInfo($userId)
	{
		$res = "false";
		$subject = "";
		$msg = "";
		$status = "";
		$dateAded = "";
		$dateClosed = "";
		$dateLastActivity = "";
		$id = "";
		$_id = "";
		$department = "";
		$priorety = "";
		$select = mysql_query("SELECT * FROM tickets WHERE userId='$userId' ORDER BY dateActivity DESC, dateAded DESC");
		while ($result = mysql_fetch_array($select))
		{
			if($result["userId"] == $userId)
			{
				$dateModified = self::change_date_format($result["dateAded"]);
				$dateActivity = self::change_date_format($result["dateActivity"]);
				$_dateClosed = self::change_date_format($result["dateClosed"]);
				$res = "true";
				$id[] = $result["userId"];
				$subject[] = $result["subject"];
				$msg[] = $result["msg"];
				$status[] = $result["status"];
				$dateAded[] = $dateModified;
				$dateClosed[] = $_dateClosed;
				$dateLastActivity[] = $dateActivity;
				$department[] = $result["department"];
				$priorety[] = $result["priorety"];
				$_id[] = $result["id"];
			}
		}
		
		$array = array
		(
		"res" => $res,
		"subject" => $subject,
		"msg" => $msg,
		"status" => $status,
		"dateAded" => $dateAded,
		"dateClosed" => $dateClosed,
		"dateActivity" => $dateLastActivity,
		"userId" => $id,
		"department" => $department,
		"priorety" => $priorety,
		"id" => $_id
		);
		
		$json = json_encode($array);
		return $json;
	}
	
	public function closeTicket($id)
	{
		$select = mysql_query("SELECT id,status,dateClosed,dateActivity FROM tickets WHERE id='$id'");
		while ($result = mysql_fetch_array($select)) 
		{
			if($result["id"] == $id)
			{
				$date = date("YmdHis");
				$update = "UPDATE tickets SET status='closed',dateClosed='$date',dateActivity='$date' WHERE id='$id'";
				if(mysql_query($update))
				{
					return true;
				}
				else
				{
					return false;
				}
			}
		}
	}
	
	public function view_answered($ticketId)
	{
		$_result = "false";
		$id = "";
		$_ticketId = "";
		$msg = "";
		$moderator = "";
		$dateAded = "";
		$userId = "";
		$select = mysql_query("SELECT * FROM ticketanswer WHERE ticketId='$ticketId'");
		while ($result = mysql_fetch_array($select)) 
		{
			if ($result["ticketId"] == $ticketId)
			{
				$_result = "true";
				$id[] = $result["id"];
				$_ticketId[] = $result["ticketId"];
				$userId[] = $result["userId"];
				$msg[] = $result["msg"];
				$moderator[] = $result["moderator"];
				$dateAded[] = $result["dateAded"];
			}
		}
		$array = array
		(
		"result" => $_result,
		"id" => $id,
		"ticketId" => $_ticketId,
		"userId" => $userId,
		"msg" => $msg,
		"moderator" => $moderator,
		"dateAded" => $dateAded
		);
		
		$json = json_encode($array);
		
		return $json;
	}
	
	public function change_date_format($date)
	{
		$_date = $date;
		$new_date = date("Y-m-d H:i:s");
		$date = date_parse($date);
		$new_date = date_parse($new_date);
		
		$years_ago = $new_date["year"] - $date["year"];
		if($years_ago != 0)
		{
			if($years_ago == 1)
			{
				return $years_ago." year ago";
				exit();
			}
			else 
			{
				return $years_ago." years ago";
				exit();
			}
		}
		
		if($new_date["month"] == $date["month"] and $new_date["day"] == $date["day"] and $new_date["hour"] == $date["hour"] and $new_date["minute"] <= ($date["minute"] + 1))
		{
			return "Just now";
			exit();
		}
		
		$min_ago = $new_date["minute"] - $date["minute"];
		if($new_date["month"] == $date["month"] and $new_date["day"] == $date["day"] and $new_date["hour"] == $date["hour"])
		{
			return $min_ago." min ago";
			exit();
		}
		
		$hour_ago = $new_date["hour"] - $date["hour"];
		if($new_date["month"] == $date["month"] and $new_date["day"] == $date["day"])
		{
			if($hour_ago == 1)
			{
				return $hour_ago." hr ago";
				exit();
			}
			else 
			{
				return $hour_ago." hrs ago";
				exit();
			}
		}
		
		$day_ago = $new_date["day"] - $date["day"];
		if($new_date["month"] == $date["month"] and $day_ago <= 10)
		{
			if($day_ago == 1)
			{
				return $day_ago." day ago";
				exit();
			}
			else
			{
				return $day_ago." days ago";
				exit(); 
			}
		}
		
	     $dateModified = strtotime($_date);
		 $dateModified = date("M j, Y", $dateModified);
		 return $dateModified;
		 exit();
	}
	
}
$_ticketInfo = new ticketInfo();
?>