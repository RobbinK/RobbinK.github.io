<?php
class answer
{
	var $db;
	
	function __construct() 
	{
		$this->db = mysql_connect(DB_HOST,DB_USER,DB_PASS);
		mysql_select_db(DB_NAME);
	}
	
	public function _answer($userId,$ticketId,$msg,$moderator,$dateAded,$status = "in progress")
	{
		$msg = urldecode($msg);
		$insert = "INSERT INTO ticketanswer (ticketId,userId,msg,moderator,dateAded) VALUES ('$ticketId','$userId','$msg','$moderator','$dateAded')";
		if(mysql_query($insert))
		{
			$update = "UPDATE tickets SET status='$status',dateActivity='$dateAded' WHERE id='$ticketId'";
			if(mysql_query($update))
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
			return false;
		}
	}
	
	public function show_answer_ticket($id,$ticketInfo,$answered,$userinfo,$status)
	{
		$this->_ticketInfo = new ticketInfo();
		$ticketInfo = $this->_ticketInfo->getUserTicketInfo($userinfo->id);
		$ticketInfo = json_decode($ticketInfo);
		$ticketId = $id;
		if($ticketInfo->res == "true")
		{
				$count = count($ticketInfo->userId);
				$_method = md5("Fclosed");
				for ($i=0;$i<$count;$i++)
				{
					if($ticketInfo->id[$i] == $id)
					{
						switch ($ticketInfo->status[$i]) 
						{
							case "open":
								$class = "badge-info";
								$text = "Open";
								$icon = "icon-folder-open";
							break;
							
							case "closed":
								$class = "badge-close";
								$text = "Close";
								$icon = "icon-folder-close";
							break;
			
							case "answered":
								$class = "badge-success";
								$text =  "Replied";
								$icon = "icon-ok";
							break;
							
							case "in progress":
								$class = "badge-inverse";
								$text = "Pending";
								$icon = "icon-time";
							break;
								
							default:
								$class = "error";
								$text = "error";
								$icon = "";
							break;
						}
						$href = "tickets.php?method=".$_method.":".$ticketInfo->id[$i].":".urldecode($ticketInfo->subject[$i]);
						$_status = '<div class="badge '.$class.'" style="width:65px;"> '.$text.'</div>';
						$priorety = $ticketInfo->priorety[$i];
						$_subject = $ticketInfo->subject[$i];
						$department = $ticketInfo->department[$i];
						$dateAdded = $ticketInfo->dateAded[$i];
						$dateActivity = $ticketInfo->dateActivity[$i];
					}
				}
				
				echo '<article class="container" style="margin-top:-18px;">
						<section class="well well-small mePadd">';
				if($status == "in progress" or $status == "answered" or $status == "open")
				{
					echo '<h2 class="meFont" style="padding-bottom:30px;"><span class="icon-search meMarg"></span> '.$_subject.'</h2>
			        	<div class="resTable2">
			            	<h4 class="alert alert-heading" style="font-style: italic;">Date modified: <span class="spnPadding"> '.$dateAdded.' </span> Last Activity: <span class="spnPadding"> '.$dateActivity.' </span>
			                <span class="floatR">
			                <button class="btn btn-success" type="button" data-toggle="modal" data-target="#myModal"> Reply</button>
			                <a href="'.$href.'"><button class="btn btn-danger" type="button"> Close</button></a>
			                </span>
			                </h4>
			                
			                <div class="myTable4">
			                    <table class="table table-striped table-condensed">
			                      <tr class="tableFont">
			                        <td>ID</td>
			                        <td>Department</td>
			                        <td>Status</td>
			                        <td>Priority</td>
			                      </tr>
			                      <tr class="tableFont2">
			                        <td>'.$id.'</td>
			                        <td>
			                        	'.$department.'
			                        </td>
			                        <td><center>'.$_status.'</center></td>
			                        <td>'.$priorety.'</td>
			                      </tr>
			                    </table>
			                </div>
			            </div>
			            <div class="myTable3 resTable1">
			            	<h4 class="alert alert-heading" style="font-style: italic;">Date modified: <span class="spnPadding"> '.$dateAdded.' </span> </h4>
			                <h4 class="alert alert-heading" style="font-style: italic;">Last Activity: <span class="spnPadding"> '.$dateActivity.' </span> </h4>
			                <span class="paddingLR">
			                <button class="btn btn-success" type="button" data-toggle="modal" data-target="#myModal"> Reply</button>
			                <a href="'.$href.'"><button class="btn btn-danger" type="button"> Close</button></a>
			                </span>
			                
			                <div class="myTable4" style="padding-top:25px;">
			                    <table class="tables" width="100%" style="border:none;">
			                    <tbody>
			                    <tr>
			                    <th style="border-top:none;" scope="row" class="tableFont" align="left">ID</th>
			                    <td style="border-top:none;" class="tableFont2">'.$id.'</td>
			                    </tr>
			                    <tr>
			                    <th scope="row" class="tableFont" align="left">Department</th>
			                    <td class="tableFont2">'.$department.'</td>
			                    </tr>
			                    <tr>
			                    <th scope="row" class="tableFont" align="left">Status</th>
			                    <td class="tableFont2"><center>'.$_status.'</center></td>
			                    </tr>
			                    <tr>
			                    <th scope="row" class="tableFont" align="left">Priority</th>
			                    <td class="tableFont2">'.$priorety.'</td>
			                    </tr>
			                    </tbody>
			                  </table>
			                </div>
			            </div>
			            <hr>';
				}
				else 
				{
					echo '<h2 class="meFont" style="padding-bottom:30px;"><span class="icon-search meMarg"></span> '.$_subject.'</h2>
			        	<div class="resTable2">
			            	<h4 class="alert alert-heading" style="font-style: italic;">Date modified: <span class="spnPadding"> '.$dateAdded.' </span> Last Activity: <span class="spnPadding"> '.$dateActivity.' </span>
			        	    <span class="floatR">
			        	    	<button class="btn btn-success" type="button" data-toggle="modal" data-target="#myModal"> Reopen</button>
			                </span>
			                </h4>
			                
			                <div class="myTable4">
			                    <table class="table table-striped table-condensed">
			                      <tr class="tableFont">
			                        <td>ID</td>
			                        <td>Department</td>
			                        <td>Status</td>
			                        <td>Priority</td>
			                      </tr>
			                      <tr class="tableFont2">
			                        <td>'.$id.'</td>
			                        <td>
			                        	'.$department.'
			                        </td>
			                        <td><center>'.$_status.'</center></td>
			                        <td>'.$priorety.'</td>
			                      </tr>
			                    </table>
			                </div>
			            </div>
			             <div class="myTable3 resTable1">
			            	<h4 class="alert alert-heading" style="font-style: italic;">Date modified: <span class="spnPadding"> '.$dateAdded.' </span> </h4>
			                <h4 class="alert alert-heading" style="font-style: italic;">Last Activity: <span class="spnPadding"> '.$dateActivity.' </span> </h4>
			                <span class="paddingLR">
			                <button class="btn btn-success" type="button" data-toggle="modal" data-target="#myModal"> Reopen</button>
			                </span>
			                
			                <div class="myTable4" style="padding-top:25px;">
			                    <table class="tables" width="100%" style="border:none;">
			                    <tbody>
			                    <tr>
			                    <th style="border-top:none;" scope="row" class="tableFont" align="left">ID</th>
			                    <td style="border-top:none;" class="tableFont2">'.$id.'</td>
			                    </tr>
			                    <tr>
			                    <th scope="row" class="tableFont" align="left">Department</th>
			                    <td class="tableFont2">'.$department.'</td>
			                    </tr>
			                    <tr>
			                    <th scope="row" class="tableFont" align="left">Status</th>
			                    <td class="tableFont2"><center>'.$_status.'</center></td>
			                    </tr>
			                    <tr>
			                    <th scope="row" class="tableFont" align="left">Priority</th>
			                    <td class="tableFont2">'.$priorety.'</td>
			                    </tr>
			                    </tbody>
			                  </table>
			                </div>
			            </div>
			            <hr>';
				}
				$count = count($ticketInfo->userId);
				echo ' <div class=""><h2 class="meFont" style="padding-bottom:30px;"> Conversation:</h2>';
				for ($i=0;$i<$count;$i++)
				{
					if($ticketInfo->id[$i] == $id)
					{
						$message = urldecode($ticketInfo->msg[$i]);
						$message = str_replace("\'", "'", $message);
						$class = "info";
						$subject = $ticketInfo->subject[$i];
						$userGravatar = self::get_gravatar($userinfo->email, "50");
						echo '<li class="alert alert-user meUs">
								<img src="'.$userGravatar.'" width="50" height="50" class="img-circle">
								<span class="tableFont"> User:</span>
								<span class="tableFont2" style="background-color:transparent;">'.$userinfo->name.' '.$userinfo->lastname.'</span>
								<h6 class="fk">Posted on: <span> '.$ticketInfo->dateAded[$i].'</span></h6>
								<div class="alert alert-user meUser" >
								   '.$message.'</div>
                     		  </li>
						';
					}
				}
				if($answered->result == "true")
				{
					$_count = count($answered->id);
					for ($i=0;$i<$_count;$i++)
					{
						if($answered->moderator[$i] == "user")
						{
							$class = "alert-user meUs";
							$_class = "alert-user meUser";
							$moderator = "user";
							$full_name = $userinfo->name.' '.$userinfo->lastname;
							$userGravatar = self::get_gravatar($userinfo->email, "50");
						}
						else 
						{
							$class = "alert-success meAd";
							$_class = "alert-success meAdmin";
							$moderator = $answered->moderator[$i];
							$full_name = self::moderator_name($answered->userId[$i]);
							$userGravatar = self::get_gravatar(self::moderator_email($answered->userId[$i]), "50");
						}
						if($i == ($_count - 1))
						{
							if($answered->moderator[$i] != "user")
							{
								$msg = urldecode($answered->msg[$i]);
								$msg = str_replace("\'", "'", $msg);
							}
							else 
							{
								@$msg = urldecode($answered->msg[$i - 1]);
								@$msg = str_replace("\'", "'", $msg);
							}
						}
						$message = urldecode($answered->msg[$i]);
						$message = str_replace("\'", "'", $message);
						$dateModified = self::change_date_format($answered->dateAded[$i]);
						echo '<li class="alert '.$class.'">
							<img src="'.$userGravatar.'" width="50" height="50" class="img-circle">
							<span class="tableFont"> '.$moderator.':</span>
							<span class="tableFont2" style="background-color:transparent;">'.$full_name.'</span>
							<h6 class="fk">Posted on: <span> '.$dateModified.'</span></h6>
							<div class="alert '.$_class.'" >
							   '.$message.'</div>
                     		</li>';
					}
				}
				echo '  </div>
          			 </section>';
				
				echo '
				 <div class="modal hide fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"><!--Modal Starts-->
		        	<div class="modal-header">
		            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		            <h3 id="myModalLabel">Send a new message</h3>
		            </div>
		            <form action="tickets.php" method="post">
		            <div class="modal-body">
		            <textarea class="modalTextarea" required="required" name="message"></textarea>
		            </div>
		            <div class="modal-footer">
		            <input type="hidden" name="id" value="'.$ticketId.'" >
		            <button class="btn btn-success" type="submit" name="answer"><span class="icon-arrow-right icon-white"></span> Send</button>
		            <button class="btn" data-dismiss="modal" aria-hidden="true"><span class="icon-thumbs-down"></span> Cancel</button>
		            </div>
		            </form>
       			 </div><!--Modal Ends--> <!--Details Ends-->';
		}
		echo '</section>
			</article>';
	}
	
	/* Change date */
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
	
	private function moderator_name($userId)
	{
		$select = mysql_query("SELECT name,lastname,id FROM ticketusers WHERE id='$userId'");
		while ($result = mysql_fetch_array($select))
		{
			if($userId == $result["id"])
			{
				$while = true;
				return $result["name"]." ".$result["lastname"];
			}
			else 
			{
				$while = false;
			}
		}
		if(@$while == false)
		{
			return "This user deleted";
		}
	}
	
	private function moderator_email($userId)
	{
		$select = mysql_query("SELECT email,id FROM ticketusers WHERE id='$userId'");
		while ($result = mysql_fetch_array($select))
		{
			if($userId == $result["id"])
			{
				$while = true;
				return $result["email"];
			}
			else 
			{
				$while = false;
			}
		}
		if(@$while == false)
		{
			return "This user deleted";
		}
	}
	
	private function get_gravatar( $email, $s, $d = 'mm', $r = 'g', $img = false, $atts = array() ) 
	{
	    $url = 'http://www.gravatar.com/avatar/';
	    $url .= md5( strtolower( trim( $email ) ) );
	    $url .= "?s=$s&d=$d&r=$r";
	    if ( $img ) 
	    {
	       	$url = '<img src="' . $url . '"';
	        foreach ( $atts as $key => $val )
	            $url .= ' ' . $key . '="' . $val . '"';
	       		$url .= ' />';
	    }
	    return $url;
	}
}
$answer = new answer();
?>