<?php
include 'userLogin.php';
include 'header.php';
include 'menu.php';
include 'answerTickets.php';
class search
{
	var $db;
	
	function __construct() 
	{
		$this->db = mysql_connect(DB_HOST,DB_USER,DB_PASS);
		mysql_select_db(DB_NAME);
	}
	
	public function search_results($s)
	{
		$baceTicketId = "";
		$subject = "";
		$baceResult = "false";
		$baceMessage = "";
		$department = "";
		$select = mysql_query("SELECT id, subject, msg, status, dateAded, dateActivity,department FROM  tickets WHERE id LIKE '%$s%' OR subject LIKE '%$s%' OR msg LIKE '%$s%'");
		while ($result = mysql_fetch_array($select)) 
		{
			$baceTicketId[] = $result["id"];
			$subject[] = $result["subject"];
			$baceMessage[] = $result["msg"];
			$status[] = $result["status"];
			$dateAded[] = self::change_date_format($result["dateAded"]);
			$dateActivity[] = self::change_date_format($result["dateActivity"]);
			$department[] = $result["department"];
			$baceResult = "true";
		}
		
		if(empty($baceTicketId))
		{
			$array = array
			(
			"baceResult" => $baceResult,
			);
		}
		else 
		{
			$array = array
			(
			"baceTicketId" => $baceTicketId,
			"subject" => $subject,
			"baceMessage" => $baceMessage,
			"status" => $status,
			"baceResult" => $baceResult,
			"dateAded" => $dateAded,
			"dateActivity" => $dateActivity,
			"department" => $department
			);
		}
		return $array;
	}
	public function search_answer_result($s)
	{
		$answeredMessage = "";
		$answeredTicketId = "";
		$answerResult = "false";
		$select = mysql_query("SELECT ticketId, msg, dateAded FROM  ticketanswer WHERE ticketId LIKE '%$s%' OR msg LIKE '%$s%'");
		while ($result = mysql_fetch_array($select)) 
		{
			$answeredTicketId[] = $result["ticketId"];
			$answeredMessage[] = $result["msg"];
			$status[] = self::get_status($result["ticketId"]);
			$subject[] = self::get_subject($result["ticketId"]);
			$dateActivity[] = self::change_date_format(self::get_dateLastActivity($result["ticketId"]));
			$department[] = self::get_department($result["ticketId"]);
			$dateAded[] = self::change_date_format($result["dateAded"]);
			$answerResult = "true";
		}
		if(empty($answeredTicketId))
		{
			$array = array
			(
			"answerResult" => $answerResult,
			);
		}
		else 
		{
			$array = array
			(
			"answeredTicketId" => $answeredTicketId,
			"answerResult" => $answerResult,
			"status" => $status,
			"subject" => $subject,
			"answeredMessage" => $answeredMessage,
			"dateAded" => $dateAded,
			"department" => $department,
			"dateActivity" => $dateActivity
			);
		}
		
		return $array;
	}
	
	private function get_status($ticketId)
	{
		$select = mysql_query("SELECT id,status FROM  tickets WHERE id='$ticketId'");
		while ($result = mysql_fetch_array($select))
		{
			if($result["id"] == $ticketId)
			{
				return $result["status"];
			}
		}
	}
	
	private function get_subject($ticketId)
	{
		$select = mysql_query("SELECT id,subject FROM  tickets WHERE id='$ticketId'");
		while ($result = mysql_fetch_array($select))
		{
			if($result["id"] == $ticketId)
			{
				return $result["subject"];
			}
		}
	}
	
	private function get_dateLastActivity($ticketId)
	{
		$select = mysql_query("SELECT id,dateActivity FROM  tickets WHERE id='$ticketId'");
		while ($result = mysql_fetch_array($select))
		{
			if($result["id"] == $ticketId)
			{
				return $result["dateActivity"];
			}
		}
	}
	
	private function get_department($ticketId)
	{
		$select = mysql_query("SELECT id,department FROM  tickets WHERE id='$ticketId'");
		while ($result = mysql_fetch_array($select))
		{
			if($result["id"] == $ticketId)
			{
				return $result["department"];
			}
		}
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
if(@$isLogin and $userinfo->role == "admin"):
?>
<?php 
if(isset($_GET["s"]))
{
	$search = new search();
	$s = $_GET["s"];
	$search_array = $search->search_results($s);
	$search_answer_array = $search->search_answer_result($s);
	$search_result = json_encode($search_array);
	$search_result = json_decode($search_result);
	$search_answer_result = json_encode($search_answer_array);
	$search_answer_result = json_decode($search_answer_result);
	if($search_result->baceResult == "true" or $search_answer_result->answerResult == "true")
	{
		echo '
			<article class="container" style="margin-top:-18px;">
					<section class="well well-small mePadd">
        				<h2 class="meFont" style="padding-bottom:30px;"><span class="icon-search meMarg"></span> Search result for '.$s.'</h2>';
		@$baceCount = count($search_result->baceTicketId);
		@$answerCount = count($search_answer_result->answeredTicketId);
				
		$_method = md5("search");
		if($baceCount > 0)
		{
			for ($i=0;$i<$baceCount;$i++)
			{
					switch ($search_result->status[$i]) 
					{
						case "open":
							$class = "badge-info";
							$disabled = "";
							$href = "show.php?method=".$_method.":".$search_result->baceTicketId[$i].":".urldecode($search_result->status[$i]);
							$btn_success = "btn-warning";
							$text = "Open";
							$continue =  md5("show");
							$icon = "icon-folder-open";
				            $buttons = '<a href="'.$href.'"><button class="btn btn-info btnDetails" type="button"> Details</button></a>';
						break;
						
						case "closed":
							$class = "badge-close";
							$disabled = "";
							$href = "show.php?method=".$_method.":".$search_result->baceTicketId[$i].":".urldecode($search_result->status[$i]);
							$btn_success = "";
							$text = "Close";
							$continue = $_method;
							$icon = "icon-folder-close";
							$buttons = '<a href="'.$href.'"><button class="btn btn-info btnDetails" type="button"> Details</button></a>';
						break;
		
						case "answered":
							$class = "badge-success";
							$disabled = "";
							$btn_success = "btn-success";
							$href = "show.php?method=".$_method.":".$search_result->baceTicketId[$i].":".urldecode($search_result->status[$i]);
							$text =  "Replied";
							$continue = md5("showA");
							$icon = "icon-ok";
							$buttons = '<a href="'.$href.'"><button class="btn btn-info btnDetails" type="button"> Details</button></a>';
						break;
						
						case "in progress":
							$class = "badge-inverse";
							$disabled = "";
							$btn_success = "btn-primary";
							$href = "show.php?method=".$_method.":".$search_result->baceTicketId[$i].":".urldecode($search_result->status[$i]);
							$text = "Pending";
							$continue = md5("openC");
							$icon = "icon-time";
							$buttons = '<a href="'.$href.'"><button class="btn btn-info btnDetails" type="button"> Details</button></a>';
						break;
							
						default:
							$class = "error";
							$href = "#";
							$disabled = "disabled";
							$btn_success = "";
							$text = "error";
							$continue = "";
							$icon = "";
							$buttons = "";
						break;
					}
					$message = urldecode($search_result->baceMessage[$i]);
					$message = str_replace("\'", "'", $message);
					$message = str_replace($s, '<span class="label label-warning">'.$s.'</span>', $message);
					$message = str_replace(ucfirst($s), '<span class="label label-warning">'.ucfirst($s).'</span>', $message);
					$message = str_replace(ucfirst(strtolower($s)), '<span class="label label-warning">'.ucfirst(strtolower($s)).'</span>', $message);
					$message = str_replace(strtolower($s), '<span class="label label-warning">'.strtolower($s).'</span>', $message);
					$search_result->subject[$i] = str_replace($s, '<span class="label label-warning">'.$s.'</span>', $search_result->subject[$i]);
					$search_result->subject[$i] = str_replace(ucfirst($s), '<span class="label label-warning">'.ucfirst($s).'</span>', $search_result->subject[$i]);
					$search_result->subject[$i] = str_replace(ucfirst(strtolower($s)), '<span class="label label-warning">'.ucfirst(strtolower($s)).'</span>', $search_result->subject[$i]);
					$search_result->subject[$i] = str_replace(strtolower($s), '<span class="label label-warning">'.strtolower($s).'</span>', $search_result->subject[$i]);
					$search_result->baceTicketId[$i] = str_replace($s, '<span class="label label-warning">'.$s.'</span>', $search_result->baceTicketId[$i]);
					$href = "show.php?method=".$_method.":".$search_result->baceTicketId[$i].":".urldecode($search_result->status[$i]);
					echo '<div class="myTable">
               				 <div class="tableH">
               				 <div class="resTable1">
				                  <table class="tables" width="100%" style="border:none;">
				                    <tbody>
				                    <tr>
				                    <th style="border-top:none;" scope="row" class="tableFont" align="left">ID</th>
				                    <td style="border-top:none;" class="tableFont2">'.$search_result->baceTicketId[$i].'</td>
				                    </tr>
				                    <tr>
				                    <th scope="row" class="tableFont" align="left">Status</th>
				                    <td class="tableFont2"><div class="badge '.$class.'" style="width:40px;">'.$text.'</div></td>
				                    </tr>
				                    <tr>
				                    <th scope="row" class="tableFont" align="left">Subject</th>
				                    <td class="tableFont2">'.$search_result->subject[$i].'</td>
				                    </tr>
				                    <tr>
				                    <th scope="row" class="tableFont" align="left">Department</th>
				                    <td class="tableFont2">'.$search_result->department[$i].'</td>
				                    </tr>
				                    <tr>
				                    <th scope="row" class="tableFont" align="left">Date Modified</th>
				                    <td class="tableFont2">'.$search_result->dateAded[$i].'</td>
				                    </tr>
				                    <tr>
				                    <th scope="row" class="tableFont" align="left">Last Activity</th>
				                    <td class="tableFont2">'.$search_result->dateActivity[$i].'</td>
				                    </tr>
				                    </tbody>
				                  </table>
				                <div class="paddingRL resTable1 mePadB">
				                <div class="tableW">
				                '.$message.'
				                </div>
				                    <div class="pull-right paddingTop">
				                    	'.$buttons.'
				                    </div>
				                </div>
				                </div>
				                
			                    <div class="resTable2" style="margin-top:-32px;">
				                    <table class="table table-striped table-condensed" style="border:none;">
				                      <tr class="tableFont">
			                        <td>ID</td>
			                        <td>Status</td>
			                        <td>Subject</td>
			                        <td>Department</td>
			                        <td>Date Modified</td>
			                        <td>Last Activity</td>
			                      </tr>';
					
					echo '		  <tr class="tableFont2">';
						
					echo ' 			<td>'.$search_result->baceTicketId[$i].'</td>
				                        <td>
				                        	<center><div class="badge '.$class.'" style="width:50px;">'.$text.'</div></center>
				                        </td>
				                        <td>'.$search_result->subject[$i].'</td>
				                        <td>'.$search_result->department[$i].'</td>
				                        <td>'.$search_result->dateAded[$i].'</td>
				                        <td>'.$search_result->dateActivity[$i].'</td>
				                      </tr>
				                    </table>
				                </div>
				                <div class="paddingRL resTable2 mePadB">
				                <div class="tableW">'.$message.'</div>
				                 <div class="pull-right paddingTop">
				                 '.$buttons.'
				              	</div>
				              	</div>
	                   		 </div>
	                   		 </div>
				                ';	
			}
		}
		
		if($answerCount > 0)
		{
			for ($i=0;$i<$answerCount;$i++)
			{
					switch ($search_answer_result->status[$i]) 
					{
						case "open":
							$class = "badge-info";
							$disabled = "";
							$href = "show.php?method=".$_method.":".$search_answer_result->answeredTicketId[$i].":".urldecode($search_answer_result->status[$i]);							$btn_success = "btn-warning";
							$text = "Open";
							$continue =  md5("show");
							$icon = "icon-folder-open";
				            $buttons = '<a href="'.$href.'"><button class="btn btn-info btnDetails" type="button"> Details</button></a>';
						break;
						
						case "closed":
							$class = "badge-close";
							$disabled = "";
							$href = "show.php?method=".$_method.":".$search_answer_result->answeredTicketId[$i].":".urldecode($search_answer_result->status[$i]);
							$btn_success = "";
							$text = "Close";
							$continue = $_method;
							$icon = "icon-folder-close";
							$buttons = '<a href="'.$href.'"><button class="btn btn-info btnDetails" type="button"> Details</button></a>';
						break;
		
						case "answered":
							$class = "badge-success";
							$disabled = "";
							$href = "show.php?method=".$_method.":".$search_answer_result->answeredTicketId[$i].":".urldecode($search_answer_result->status[$i]);
							$btn_success = "btn-success";
							$text =  "Replied";
							$continue = md5("showA");
							$icon = "icon-ok";
							$buttons = '<a href="'.$href.'"><button class="btn btn-info btnDetails" type="button"> Details</button></a>';
						break;
						
						case "in progress":
							$class = "badge-inverse";
							$disabled = "";
							$btn_success = "btn-primary";
							$href = "show.php?method=".$_method.":".$search_answer_result->answeredTicketId[$i].":".urldecode($search_answer_result->status[$i]);
							$text = "Pending";
							$continue = md5("openC");
							$icon = "icon-time";
							$buttons = '<a href="'.$href.'"><button class="btn btn-info btnDetails" type="button"> Details</button></a>';
						break;
							
						default:
							$class = "error";
							$href = "#";
							$disabled = "disabled";
							$btn_success = "";
							$text = "error";
							$continue = "";
							$icon = "";
							$buttons = "";
						break;
					}
				$message = urldecode($search_answer_result->answeredMessage[$i]);
				$message = str_replace("\'", "'", $message);
				$message = str_replace($s, '<span class="label label-warning">'.$s.'</span>', $message);
				$message = str_replace(ucfirst($s), '<span class="label label-warning">'.ucfirst($s).'</span>', $message);
				$message = str_replace(strtolower($s), '<span class="label label-warning">'.strtolower($s).'</span>', $message);
				$message = str_replace(ucfirst(strtolower($s)), '<span class="label label-warning">'.ucfirst(strtolower($s)).'</span>', $message);
				$search_answer_result->subject[$i] = str_replace($s, '<span class="label label-warning">'.$s.'</span>', $search_answer_result->subject[$i]);
				$search_answer_result->subject[$i] = str_replace(ucfirst($s), '<span class="label label-warning">'.ucfirst($s).'</span>', $search_answer_result->subject[$i]);
				$search_answer_result->subject[$i] = str_replace(ucfirst(strtolower($s)), '<span class="label label-warning">'.ucfirst(strtolower($s)).'</span>', $search_answer_result->subject[$i]);
				$search_answer_result->subject[$i] = str_replace(strtolower($s), '<span class="label label-warning">'.strtolower($s).'</span>', $search_answer_result->subject[$i]);
				$search_answer_result->answeredTicketId[$i] = str_replace($s, '<span class="label label-warning">'.$s.'</span>', $search_answer_result->answeredTicketId[$i]);
				echo '<div class="myTable">
               				 <div class="tableH">
               				 <div class="resTable1">
				                  <table class="tables" width="100%" style="border:none;">
				                    <tbody>
				                    <tr>
				                    <th style="border-top:none;" scope="row" class="tableFont" align="left">ID</th>
				                    <td style="border-top:none;" class="tableFont2">'.$search_answer_result->answeredTicketId[$i].'</td>
				                    </tr>
				                    <tr>
				                    <th scope="row" class="tableFont" align="left">Status</th>
				                    <td class="tableFont2"><div class="badge '.$class.'" style="width:40px;">'.$text.'</div></td>
				                    </tr>
				                    <tr>
				                    <th scope="row" class="tableFont" align="left">Subject</th>
				                    <td class="tableFont2">'.$search_answer_result->subject[$i].'</td>
				                    </tr>
				                    <tr>
				                    <th scope="row" class="tableFont" align="left">Department</th>
				                    <td class="tableFont2">'.$search_answer_result->department[$i].'</td>
				                    </tr>
				                    <tr>
				                    <th scope="row" class="tableFont" align="left">Date Modified</th>
				                    <td class="tableFont2">'.$search_answer_result->dateAded[$i].'</td>
				                    </tr>
				                    <tr>
				                    <th scope="row" class="tableFont" align="left">Last Activity</th>
				                    <td class="tableFont2">'.$search_answer_result->dateActivity[$i].'</td>
				                    </tr>
				                    </tbody>
				                  </table>
				                <div class="paddingRL resTable1 mePadB">
				                <div class="tableW">
				                '.$message.'
				                </div>
				                    <div class="pull-right paddingTop">
				                    	'.$buttons.'
				                    </div>
				                </div>
				                </div>
				                
			                    <div class="resTable2" style="margin-top:-32px;">
				                    <table class="table table-striped table-condensed" style="border:none;">
				                      <tr class="tableFont">
			                        <td>ID</td>
			                        <td>Status</td>
			                        <td>Subject</td>
			                        <td>Department</td>
			                        <td>Date Modified</td>
			                        <td>Last Activity</td>
			                      </tr>';
					
				echo '		  <tr class="tableFont2">';
					
				echo ' 			<td>'.$search_answer_result->answeredTicketId[$i].'</td>
			                        <td>
			                        	<center><div class="badge '.$class.'" style="width:50px;">'.$text.'</div></center>
			                        </td>
			                        <td>'.$search_answer_result->subject[$i].'</td>
			                        <td>'.$search_answer_result->department[$i].'</td>
			                        <td>'.$search_answer_result->dateAded[$i].'</td>
			                        <td>'.$search_answer_result->dateActivity[$i].'</td>
			                      </tr>
			                    </table>
			                </div>
			                <div class="paddingRL resTable2 mePadB">
			                <div class="tableW">'.$message.'</div>
			                 <div class="pull-right paddingTop">
			                 '.$buttons.'
			              	</div>
			              	</div>
                   		 </div>
                   		 </div>
			                ';	
			}
		}
		echo "</section>
				</article>";
	}
	else 
	{
		echo '<article class="container" style="margin-top:-18px;">
					<section class="well well-small mePadd">
					<div class="alert alert-info">
			 		<center>Can not found result for '.$s.'...</center>
					</div>
					</section>
					</article>';
	}
}
?>
<?php include 'footer.php'; endif;?>