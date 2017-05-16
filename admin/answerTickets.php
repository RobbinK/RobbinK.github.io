<?php
class answerTickets
{
	var $db;
	
	function __construct() 
	{
		$this->db = mysql_connect(DB_HOST,DB_USER,DB_PASS);
		mysql_select_db(DB_NAME);
	}
	
	
	public function answer($userId,$ticketId, $msg, $moderator, $dateAded, $status = "answered")
	{
		$email_message = $msg;
		$msg = urlencode($msg);
		$insert = "INSERT INTO ticketanswer (ticketId,userId,msg,moderator,dateAded) VALUES ('$ticketId','$userId','$msg','$moderator','$dateAded')";
		if(mysql_query($insert))
		{
			$update = "UPDATE tickets SET status='$status',dateActivity='$dateAded' WHERE id='$ticketId'";
			if(mysql_query($update))
			{
				$this->_ticketInfo = new ticketInfo();
				$ticketInfo = $this->_ticketInfo->getTickets();
				$ticketInfo = json_decode($ticketInfo);
				$to = self::TO($ticketId);
				$_subject = "Your ticket has been answered";
				for ($i = 0; $i < count($ticketInfo->id); $i++)
				{
					if($ticketInfo->id[$i] == $ticketId)
					{
						$_subject = "RE: " . $ticketInfo->subject[$i];
					}
				}
				$email_message = str_replace("\'", "'", $email_message);
				$send_mail = mail($to, $_subject,"Your ticket has been answered: "  . $email_message);
				if($send_mail)
				{
					echo '<article class="container" style="margin-top:-18px;">
							<section class="well well-small mePadd">
							<div class="alert alert-success">
		  						<center>Your answer has been saved.</center>
							</div>
							</section>
			 		     </article>';
				}
				else 
				{
					echo '<article class="container" style="margin-top:-18px;">
							<section class="well well-small mePadd">
							<div class="alert alert-error">
		  						<center>Your answer has been saved but can not send email to user.</center>
						 	</div>
						 	</section>
			 		     </article>';
				}
				if($ticketInfo->res == "true")
				{
					echo '<article class="container" style="margin-top:-18px;">
							<section class="well well-small mePadd">
								<h2 class="meFont" style="padding-bottom:30px;"><span class="icon-comment meMarg"></span> Edit</h2>';
					for ($i = 0; $i < count($ticketInfo->id); $i++)
					{
						if($ticketInfo->id[$i] == $ticketId)
						{
							$message = urldecode($msg);
							$message = str_replace("\'", "'", $message);
							$userMessage = urldecode($ticketInfo->msg[$i]);
							$userMessage = str_replace("\'", "'", $userMessage);
							$_method = md5("edit");
							$href = "answer.php?method=".$_method.":".$ticketInfo->id[$i];
							$__method = md5("Fclosed");
							$_href = "answer.php?method=".$__method.":".$ticketInfo->id[$i].":".urlencode($ticketInfo->subject[$i]);
							$disabled = "";
							$buttons = '<a href="'.$href.'"><button class="btn btn-info btnDetails" type="button">Edit</button></a>';
							$_buttons = '<a href="'.$_href.'"><button class="btn btn-danger btnClose" type="button">Close</button></a>';
							$text =  "Replied";
							$icon = "icon-ok";
							$class = "badge-success";
							$dateModified = self::change_date_format($ticketInfo->dateAded[$i]);
							$dateActivity = self::change_date_format($ticketInfo->dateActivity[$i]);
							echo '<div class="myTable">
               				 <div class="tableH">
               				 <div class="resTable1">
				                  <table class="tables" width="100%" style="border:none;">
				                    <tbody>
				                    <tr>
				                    <th style="border-top:none;" scope="row" class="tableFont" align="left">ID</th>
				                    <td style="border-top:none;" class="tableFont2">'.$ticketInfo->id[$i].'</td>
				                    </tr>
				                    <tr>
				                    <th scope="row" class="tableFont" align="left">Status</th>
				                    <td class="tableFont2"><div class="badge '.$class.'" style="width:40px;">'.$text.'</div></td>
				                    </tr>
				                    <tr>
				                    <th scope="row" class="tableFont" align="left">Subject</th>
				                    <td class="tableFont2">'.$ticketInfo->subject[$i].'</td>
				                    </tr>
				                    <tr>
				                    <th scope="row" class="tableFont" align="left">Department</th>
				                    <td class="tableFont2">'.$ticketInfo->department[$i].'</td>
				                    </tr>
				                    <tr>
				                    <th scope="row" class="tableFont" align="left">Date Modified</th>
				                    <td class="tableFont2">'.$dateModified.'</td>
				                    </tr>
				                    <tr>
				                    <th scope="row" class="tableFont" align="left">Last Activity</th>
				                    <td class="tableFont2">'.$dateActivity.'</td>
				                    </tr>
				                    </tbody>
				                  </table>
				                <div class="paddingRL resTable1 mePadB">
				                <div class="tableW">
				                <b>Your message: </b>'.$message.'
				                </div>
				                    <div class="pull-right paddingTop">
				                    	'.$buttons.'
						                '.$_buttons.'
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
							
						echo ' 			<td>'.$ticketInfo->id[$i].'</td>
					                      <td>
					                        	<center><div class="badge '.$class.'" style="width:50px;">'.$text.'</div></center>
					                        </td>
					                        <td>'.$ticketInfo->subject[$i].'</td>
					                        <td>'.$ticketInfo->department[$i].'</td>
					                        <td>'.$dateModified.'</td>
					                        <td>'.$dateActivity.'</td>
					                      </tr>
					                    </table>
					                </div>
					                <div class="paddingRL resTable2 mePadB">
					                <div class="tableW"><b>Your message: </b>'.$message.'</div>
					                 <div class="pull-right paddingTop">
					                 '.$buttons.'
					                 '.$_buttons.'
					              	</div>
					              	</div>
		                   		 </div>
		                   		 </div>
					                ';	
						}
					}
					echo '	</section>
			 		     </article>';
				}
			}
			else
			{
				echo '<article class="container" style="margin-top:-18px;">
							<section class="well well-small mePadd">
							<div class="alert alert-error">
  						<center>Your answer has been NOT saved.</center>
					</div></section>
			 		     </article>';
			}
		}
		else 
		{
			echo '<article class="container" style="margin-top:-18px;">
							<section class="well well-small mePadd">
							<div class="alert alert-error">
  						<center>Your answer has been NOT saved.</center>
				</div></section>
			 		     </article>';
		}
	}
	
	private function TO($ticketId)
	{
		$query = mysql_query("SELECT id,userId FROM tickets WHERE id='$ticketId'");
		$result = mysql_fetch_array($query);
		$userId = $result["userId"];
		$query = mysql_query("SELECT id,email FROM ticketusers WHERE id='$userId'");
		$result = mysql_fetch_array($query);
		return $result["email"];
	}
	
	public function edit($answerId, $ticketId, $msg, $moderator, $dateAded)
	{
		$msg = urlencode($msg);
		if(mysql_query("UPDATE tickets SET dateActivity='$dateAded' WHERE id='$ticketId'"))
		{
			$update = "UPDATE ticketanswer SET msg='$msg', moderator='$moderator', dateAded='$dateAded' WHERE id='$answerId' AND ticketId='$ticketId'";
			if(mysql_query($update))
			{
				$this->_ticketInfo = new ticketInfo();
				$ticketInfo = $this->_ticketInfo->getTickets();
				$ticketInfo = json_decode($ticketInfo);
				
				$ticketAnswerInfo = $this->_ticketInfo->getAnswerTickets();
				$ticketAnswerInfo = json_decode($ticketAnswerInfo);
				
				echo '<article class="container" style="margin-top:-18px;">
							<section class="well well-small mePadd">
								<h2><span class="icon-comment" style="margin-top:10px;"></span> Edit</h2>
							<div class="alert alert-success">
  						<center>Your answer has been edited.</center>
					  </div>
					  </section></article>';
				echo '<article class="container" style="margin-top:-22px;">
							<section class="well well-small mePadd">
        					';
					$class = "warning";
					$href = "#";
					$disabled = "disabled";
					$btn_success = "";
					$text = "closed";
					for ($i = 0; $i < count($ticketInfo->id); $i++)
					{
						if($ticketInfo->id[$i] == $ticketId)
						{
							$userMessage = urldecode($ticketInfo->msg[$i]);
							$userMessage = str_replace("\'", "'", $userMessage);
							$dateActivity = self::change_date_format($ticketInfo->dateActivity[$i]);
						}
					}
					for($i = 0; $i < count($ticketAnswerInfo->id); $i++)
					{
						if($ticketAnswerInfo->id[$i] == $answerId)
						{
							$message = urldecode($ticketAnswerInfo->msg[$i]);
							$message = str_replace("\'", "'", $message);
							$dateModified = self::change_date_format($ticketInfo->dateAded[$i]);
							$key = $_COOKIE['login'];
							$adminInfo = self::getAdminInfo($key);
							$adminInfo = json_decode($adminInfo);
							echo '<div class="myTable">
               				 <div class="tableH">
               				 <div class="resTable1">
				                  <table class="tables" width="100%" style="border:none;">
				                    <tbody>
				                    <tr>
				                    <th style="border-top:none;" scope="row" class="tableFont" align="left">ID</th>
				                    <td style="border-top:none;" class="tableFont2">'.$ticketInfo->id[$i].'</td>
				                    </tr>
				                    <tr>
				                    <th scope="row" class="tableFont" align="left">Subject</th>
				                    <td class="tableFont2">'.$ticketInfo->subject[$i].'</td>
				                    </tr>
				                    <tr>
				                    <th scope="row" class="tableFont" align="left">Selected moderator</th>
				                    <td class="tableFont2">'.$ticketAnswerInfo->moderator[$i].'</td>
				                    </tr>
				                    <tr>
				                    <th scope="row" class="tableFont" align="left">Date Modified</th>
				                    <td class="tableFont2">'.$dateModified.'</td>
				                    </tr>
				                    <tr>
				                    <th scope="row" class="tableFont" align="left">Last Activity</th>
				                    <td class="tableFont2">'.$dateActivity.'</td>
				                    </tr>
				                    </tbody>
				                  </table>
				                <div class="paddingRL resTable1 mePadB">
				                <div class="tableW">
				               	 	<b>Your message: </b>'.$message.'
				                </div>';
				                    echo '
						                <div class="tableW">
						                <form class="form-inline" action="answer.php" method="post">
									<label class="control-label" for="inputMessage">Your message</label>
									<br/>
									<textarea class="modalTextarea" required="required" rows="3" id="inputMessage" name="message">'.
										$message
									.'</textarea>';
							echo '<label class="control-label" for="moderator">Select moderator</label><br/>
							<span class="input-xlarge uneditable-input">'.$adminInfo->moderatorRole.'</span>';
							
							echo '<br/><input type="hidden" value="'.$adminInfo->moderatorRole.'" name="moderator">
										<input type="hidden" value="'.$ticketAnswerInfo->id[$i].'" name="answerId">
										<input type="hidden" value="'.$ticketId.'" name="ticketId">
										<input type="hidden" value="edit" name="method"><br/>
										<button class="btn btn-success" type="submit" name="edit">Edit</button>						 		
									</form></div>
				              </div>
				              </div>
				                
			                    <div class="resTable2" style="margin-top:-32px;">
				                    <table class="table table-striped table-condensed" style="border:none;">
				                  <tr class="tableFont">
			                        <td>ID</td>
			                        <td>Subject</td>
			                        <td>Selected moderator</td>
			                        <td>Date Modified</td>
			                        <td>Last Activity</td>
			                      </tr>';
					
							echo '		  <tr class="tableFont2">';
								
							echo ' 			<td>'.$ticketInfo->id[$i].'</td>
						                        <td>'.$ticketInfo->subject[$i].'</td>
						                        <td>'.$ticketAnswerInfo->moderator[$i].'</td>
						                        <td>'.$dateModified.'</td>
						                        <td>'.$dateActivity.'</td>
						                      </tr>
						                    </table>
						                </div>
						                <div class="paddingRL resTable2 mePadB">
						                <div class="tableW"><b>Your message: </b>'.$message.'</div>
						                 <div class="pull-right paddingTop">
						                 
						              	</div>
						              	</div>
			                   		 </div>
			                   		 </div>
						                ';
							
							echo ' <div class="paddingRL resTable2 mePadB">
						                <div class="tableW">
						                <form class="form-inline" action="answer.php" method="post" name="answerForm" onsubmit="return checkAnswer();">
									<label class="control-label" for="inputMessage" style="margin-left: 23px;">Your message</label>
									<br/>
									<textarea class="modalTextarea" required="required" rows="3" id="inputMessage" name="message">'.
										$message
									.'</textarea><br/><br/>';
							echo '<label class="control-label" for="moderator" style="margin-left: 23px;">Your role </label><br/>
							<span class="input-xlarge uneditable-input" style="margin-left: 23px;">'.$adminInfo->moderatorRole.'</span>';
							
							echo '<br/><input type="hidden" value="'.$adminInfo->moderatorRole.'" name="moderator">
										<input type="hidden" value="'.$ticketAnswerInfo->id[$i].'" name="answerId">
										<input type="hidden" value="'.$ticketId.'" name="ticketId">
										<input type="hidden" value="edit" name="method"><br/>
										<button class="btn btn-success" type="submit" name="edit" style="margin-left: 23px;">Edit</button>	
										<br/>
										<br/>	
										<p style="margin-left: 23px; color: red;" id="answerError"></p>					 		
									</form></div></div></div>';
						}
					}
					echo '</section></article>';
				}
				else
				{
					echo '<article class="container" style="margin-top:-18px;">
							<section class="well well-small mePadd">
							<div class="alert alert-error">
		  						<center>Your edition has not been saved.</center>
						</div>
						</section></article>';
				}
			}
			else 
			{
				echo '<article class="container" style="margin-top:-18px;">
							<section class="well well-small mePadd">
							<div class="alert alert-error">
  						<center>Your edition has not been saved.</center>
				</div></section></article>';
			}
		}
		
		public function show_answer($id, $ticketInfo)
		{
			if($ticketInfo->res == "true")
			{
				$this->_ticketInfo = new ticketInfo();
				echo '<article class="container" style="margin-top:-18px;">
							<section class="well well-small mePadd">
								<h2 class="meFont" style="padding-bottom:30px;"><span class="icon-comment meMarg"></span> Answer</h2>';
				for ($i = 0; $i < count($ticketInfo->id); $i++)
				{
					if($ticketInfo->id[$i] == $id)
					{
						$message = urldecode($ticketInfo->msg[$i]);
						$message = str_replace("\'", "'", $message);
						$user_name = $this->_ticketInfo->getuser($ticketInfo->userId[$i]);
						$user_name = json_decode($user_name);
						if(empty($user_name))
						{
							$full_name = "this user deleted";
						}
						else
						{
							$full_name = $user_name->name.' '.$user_name->lastname;
						}
						$dateModified = self::change_date_format($ticketInfo->dateAded[$i]);
						$dateActivity = self::change_date_format($ticketInfo->dateActivity[$i]);
						$key = $_COOKIE['login'];
						$adminInfo = self::getAdminInfo($key);
						$adminInfo = json_decode($adminInfo);
						echo '<div class="myTable">
               				 <div class="tableH">
               				 <div class="resTable1">
				                  <table class="tables" width="100%" style="border:none;">
				                    <tbody>
				                    <tr>
				                    <th style="border-top:none;" scope="row" class="tableFont" align="left">ID</th>
				                    <td style="border-top:none;" class="tableFont2">'.$ticketInfo->id[$i].'</td>
				                    </tr>
				                    <tr>
				                    <th scope="row" class="tableFont" align="left">User</th>
				                    <td class="tableFont2">'.$full_name.'</td>
				                    </tr>
				                    <tr>
				                    <th scope="row" class="tableFont" align="left">Subject</th>
				                    <td class="tableFont2">'.$ticketInfo->subject[$i].'</td>
				                    </tr>
				                    <tr>
				                    <th scope="row" class="tableFont" align="left">Department</th>
				                    <td class="tableFont2">'.$ticketInfo->department[$i].'</td>
				                    </tr>
				                    <tr>
				                    <th scope="row" class="tableFont" align="left">Date Modified</th>
				                    <td class="tableFont2">'.$dateModified.'</td>
				                    </tr>
				                    <tr>
				                    <th scope="row" class="tableFont" align="left">Last Activity</th>
				                    <td class="tableFont2">'.$dateActivity.'</td>
				                    </tr>
				                    </tbody>
				                  </table>
				                <div class="paddingRL resTable1 mePadB">
				                <div class="tableW">
				                '.$message.'
				                </div>';
				                   echo '<hr/>
						                <div class="tableW">
								<form class="form-inline" action="answer.php" method="post">
									<label class="control-label" for="inputMessage">Your answer</label>
									<br/>
									<textarea rows="3" class="modalTextarea" required="required" id="inputMessage" name="message" placeholder="Your answer"></textarea>
									<br/>	
									<label class="control-label" for="moderator">Your role</label>
									<br/>
									<span class="input-xlarge uneditable-input">'.$adminInfo->moderatorRole.'</span>
									<br/>';
						echo '
									<input type="hidden" value="'.$adminInfo->moderatorRole.'" name="moderator">
									<input type="hidden" value="'.$id.'" name="ticketId">
									<input type="hidden" value="answer" name="method">
									<br/>
									<button class="btn btn-success" type="submit" name="answer">Send</button>						 		
								</form>
								</div>
								</div>
				                </div>
				                </div>
				                
			                    <div class="resTable2" style="margin-top:-32px;">
				                    <table class="table table-striped table-condensed" style="border:none;">
				                      <tr class="tableFont">
				                        <td>ID</td>
				                        <td>User</td>
				                        <td>Subject</td>
				                        <td>Department</td>
				                        <td>Date Modified</td>
				                        <td>Last Activity</td>
				                      </tr>';
					
						echo '		  <tr class="tableFont2">';
							
						echo ' 			<td>'.$ticketInfo->id[$i].'</td>
					                      <td>
					                        	<center>'.$full_name.'</center>
					                        </td>
					                        <td>'.$ticketInfo->subject[$i].'</td>
					                        <td>'.$ticketInfo->department[$i].'</td>
					                        <td>'.$dateModified.'</td>
					                        <td>'.$dateActivity.'</td>
					                      </tr>
					                    </table>
					                </div>
					                <div class="paddingRL resTable2 mePadB">
					                <div class="tableW">'.$message.'</div>
					                 <div class="pull-right paddingTop">
					                 
					              	</div>
					              	</div>
		                   		 </div>
		                   		 
					                ';	
						
						echo '<hr/>
								 <div class="paddingRL resTable2 mePadB">
						                <div class="tableW">
								<form class="form-inline" action="answer.php" method="post" name="answerForm" onsubmit="return checkAnswer();">
									<label class="control-label" for="inputMessage" style="margin-left: 23px;">Your answer</label>
									<br/>
									<textarea rows="3" class="modalTextarea" required="required" id="inputMessage" name="message" placeholder="Your answer"></textarea>
									<br/>
									<br/>	
									<label class="control-label" for="moderator" style="margin-left: 23px;">Your role</label>
									<br/>
									<span class="input-xlarge uneditable-input" style="margin-left: 23px;">'.$adminInfo->moderatorRole.'</span>
									<br/>';
						echo '
									<input type="hidden" value="'.$adminInfo->moderatorRole.'" name="moderator">
									<input type="hidden" value="'.$id.'" name="ticketId">
									<input type="hidden" value="answer" name="method">
									<br/>
									<button class="btn btn-success" type="submit" name="answer" style="margin-left: 23px;">Send</button>
									<br/>
									<br/>	
									<p style="margin-left: 23px; color: red;" id="answerError"></p>					 		
								</form>
								</div>
								</div>
							  ';
					}
				}
				echo '</section></article>';
			}
		}
		
		
		private function getAdminInfo($key)
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
		}
		
		public function show_edit($id,$ticketInfo)
		{
			$this->_ticketInfo = new ticketInfo();
			$ticketAnswerInfo = $this->_ticketInfo->getAnswerTickets();
			$ticketAnswerInfo = json_decode($ticketAnswerInfo);
			echo '<article class="container" style="margin-top:-18px;">
					<section class="well well-small mePadd">
						<h2 class="meFont" style="padding-bottom:30px;"><span class="icon-comment meMarg"></span> Edit</h2>';
			$class = "warning";
			$href = "#";
			$disabled = "disabled";
			$btn_success = "";
			$text = "closed";
			for ($i = 0; $i < count($ticketInfo->id); $i++)
			{
				if($ticketInfo->id[$i] == $id)
				{
					$userMessage = urldecode($ticketInfo->msg[$i]);
					$userMessage = str_replace("\'", "'", $userMessage);
					$dateActivity = self::change_date_format($ticketInfo->dateActivity[$i]);
					$_dateActivity = $ticketInfo->dateActivity[$i];
				}
			}
			for($i = 0; $i < count($ticketAnswerInfo->id); $i++)
			{
				$answerId = $this->_ticketInfo->getAnswerId($id,$_dateActivity);
				if($ticketAnswerInfo->id[$i] == $answerId)
				{
					$message = urldecode($ticketAnswerInfo->msg[$i]);
					$message = str_replace("\'", "'", $message);
					$dateModified = self::change_date_format($ticketInfo->dateAded[$i]);
					$key = $_COOKIE['login'];
					$adminInfo = self::getAdminInfo($key);
					$adminInfo = json_decode($adminInfo);
					echo '<div class="myTable">
               				 <div class="tableH">
               				 <div class="resTable1">
				                  <table class="tables" width="100%" style="border:none;">
				                    <tbody>
				                    <tr>
				                    <th style="border-top:none;" scope="row" class="tableFont" align="left">ID</th>
				                    <td style="border-top:none;" class="tableFont2">'.$ticketInfo->id[$i].'</td>
				                    </tr>
				                    <tr>
				                    <th scope="row" class="tableFont" align="left">Subject</th>
				                    <td class="tableFont2">'.$ticketInfo->subject[$i].'</td>
				                    </tr>
				                    <tr>
				                    <th scope="row" class="tableFont" align="left">Selected moderator</th>
				                    <td class="tableFont2">'.$ticketAnswerInfo->moderator[$i].'</td>
				                    </tr>
				                    <tr>
				                    <th scope="row" class="tableFont" align="left">Date Modified</th>
				                    <td class="tableFont2">'.$dateModified.'</td>
				                    </tr>
				                    <tr>
				                    <th scope="row" class="tableFont" align="left">Last Activity</th>
				                    <td class="tableFont2">'.$dateActivity.'</td>
				                    </tr>
				                    </tbody>
				                  </table>
				                <div class="paddingRL resTable1 mePadB">
				                <div class="tableW">
				                <b>Your message: </b>'.$message.'
				                </div>';
				                   echo '<hr/>
			                <div class="tableW">
							<form class="form-inline" action="answer.php" method="post">
							<label class="control-label" for="inputMessage">Your message</label>
							<br/>
							<textarea class="modalTextarea" required="required" rows="3" id="inputMessage" name="message">'.
								$message
							.'</textarea>';
							echo '<label class="control-label" for="moderator">Your role</label><br/>
							<span class="input-xlarge uneditable-input">'.$adminInfo->moderatorRole.'</span><br/>';
							
							echo '		<input type="hidden" value="'.$adminInfo->moderatorRole.'" name="moderator">
										<input type="hidden" value="'.$ticketAnswerInfo->id[$i].'" name="answerId">
										<input type="hidden" value="'.$id.'" name="ticketId">
										<input type="hidden" value="edit" name="method"><br/>
										<button class="btn btn-success" type="submit" name="edit">Edit</button>						 		
									</form></div>
						              </div>
						              </div>
						                
			                    <div class="resTable2" style="margin-top:-32px;">
				                    <table class="table table-striped table-condensed" style="border:none;">
				                      <tr class="tableFont">
				                        <td>ID</td>
				                        <td>Subject</td>
				                        <td>Selected moderator</td>
				                        <td>Date Modified</td>
				                        <td>Last Activity</td>
				                      </tr>';
					
				echo '		  <tr class="tableFont2">';
					
				echo ' 			<td>'.$ticketInfo->id[$i].'</td>
			                        <td>'.$ticketInfo->subject[$i].'</td>
			                        <td>'.$ticketAnswerInfo->moderator[$i].'</td>
			                        <td>'.$dateModified.'</td>
			                        <td>'.$dateActivity.'</td>
			                      </tr>
			                    </table>
			                </div>
			                <div class="paddingRL resTable2 mePadB">
			                <div class="tableW"><b>Your message: </b>'.$message.'</div>
			                 <div class="pull-right paddingTop">
			                 
			              	</div>
			              	</div>
                   		 </div>
                   		 </div>
			                ';	
					echo '<hr/><div class="paddingRL resTable2 mePadB">
			                <div class="tableW">
							<form class="form-inline" action="answer.php" method="post" name="answerForm" onsubmit="return checkAnswer();">
							<label class="control-label" for="inputMessage" style="margin-left: 23px;">Your message</label>
							<br/>
							<textarea class="modalTextarea" required="required" rows="3" id="inputMessage" name="message">'.
								$message
							.'</textarea><br/><br/>';
					echo '<label class="control-label" for="moderator" style="margin-left: 23px;">Your role</label><br/>
					<span class="input-xlarge uneditable-input" style="margin-left: 23px;">'.$adminInfo->moderatorRole.'</span><br/>';
					
					echo '		<input type="hidden" value="'.$adminInfo->moderatorRole.'" name="moderator">
								<input type="hidden" value="'.$ticketAnswerInfo->id[$i].'" name="answerId">
								<input type="hidden" value="'.$id.'" name="ticketId">
								<input type="hidden" value="edit" name="method"><br/>
								<button class="btn btn-success" type="submit" name="edit" style="margin-left: 23px;">Edit</button>	
								<br/>
								<br/>	
								<p style="margin-left: 23px; color: red;" id="answerError"></p>						 		
							</form></div></div>';
				}
			}
			echo '<section></article>';
		}
		
	public function show_answer_ticket($id,$ticketInfo,$answered,$status)
	{
		$ticketId = $id;
		if($ticketInfo->res == "true")
		{
			$count = count($ticketInfo->userId);
			for ($i=0;$i<$count;$i++)
			{
				if($ticketInfo->id[$i] == $id)
				{
					switch ($ticketInfo->status[$i]) 
						{
							case "open":
								$class = "badge-info";
								$_method = md5("answer");
								$href = "answer.php?method=".$_method.":".$ticketInfo->id[$i];
								$text = "Open";
								$__method = md5("Fclosed");
								$_href = "answer.php?method=".$__method.":".$ticketInfo->id[$i].":".urlencode($ticketInfo->subject[$i]);
								$continue = md5("showO");
								$icon = "icon-folder-open";
						        $_buttons = '<a href="'.$_href.'"><button class="btn btn-danger" type="button">Close</button></a>';
								$buttons = '<a href="'.$href.'"><button class="btn btn-info btnDetails" type="button">Reply</button></a>';
							break;
							
							case "closed":
								$class = "badge-close";
								$_method = md5("showC");
								$href = "show.php?method=".$_method.":".$ticketInfo->id[$i];
								$text = "closed";
								$text = "Close";
								$continue = $_method;
								$icon = "icon-folder-close";
								$buttons = '<a href="'.$href.'"><button class="btn btn-info btnDetails" type="button">Details</button></a>';
								$_buttons = "";
							break;
			
							case "answered":
								$class = "badge-success";
								$_method = md5("showA");
								$href = "show.php?method=".$_method.":".$ticketInfo->id[$i];
								$__method = md5("Fclosed");
								$_href = "answer.php?method=".$__method.":".$ticketInfo->id[$i].":".urlencode($ticketInfo->subject[$i]);
								$text =  "Replied";
								$continue = $_method; 
								$buttons = '<a href="'.$href.'"><button class="btn btn-info btnDetails" type="button">Details</button></a>';
								$_buttons = '<a href="'.$_href.'"><button class="btn btn-danger" type="button">Close</button></a>';
								$icon = "icon-ok";
							break;
							
							case "in progress":
								$class = "badge-inverse";
								$_method = md5("openC");
								$href = "answer.php?method=".$_method.":".$ticketInfo->id[$i];
								$__method = md5("Fclosed");
								$_href = "answer.php?method=".$__method.":".$ticketInfo->id[$i].":".urlencode($ticketInfo->subject[$i]);
								$text = "Pending";
								$continue = $_method;
								$icon = "icon-time";
								$buttons = '<a href="'.$href.'"><button class="btn btn-info btnDetails" type="button">Details</button></a>';
								$_buttons = '<a href="'.$_href.'"><button class="btn btn-danger" type="button">Close</button></a>';
							break;
								
							default:
								$class = "error";
								$href = "#";
								$disabled = "disabled";
								$btn_success = "";
								$text = "error";
								$continue = "";
							break;
						}
						$stat = '<div class="badge '.$class.'" style="width:50px;">'.$text.'</div>';
						$dateModified = self::change_date_format($ticketInfo->dateAded[$i]);
						$dateActivity = self::change_date_format($ticketInfo->dateActivity[$i]);
						$department = $ticketInfo->department[$i];
						$priority = $ticketInfo->priorety[$i];
						$subject = $ticketInfo->subject[$i];
				}
			}
			if($status == "in progress" or $status == "answered" or $status == "open")
			{
				echo '<article class="container" style="margin-top:-18px;">
						 <section class="well well-small mePadd">
						 <h2 class="meFont"><span class="icon-search meMarg"></span> '.$subject.'</h2>
						 <div class="resTable2">
       						 <h4 class="alert alert-heading" style="font-style: italic;">Date modified: <span class="spnPadding"> '.$dateModified.' </span> Last Activity: <span class="spnPadding"> '.$dateActivity.' </span>
				                <span class="floatR">
				                <button class="btn btn-success" type="button" data-toggle="modal" data-target="#myModal">Reply</button>
				                '.$_buttons.'
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
			                        <td><center>'.$stat.'</center></td>
			                        <td>'.$priority.'</td>
			                      </tr>
			                    </table>
			                </div>
			            </div>
			            </div>
			             <div class="myTable3 resTable1">
		            	<h4 class="alert alert-heading" style="font-style: italic;">Date modified: <span class="spnPadding"> '.$dateModified.' </span> </h4>
		                <h4 class="alert alert-heading" style="font-style: italic;">Last Activity: <span class="spnPadding"> '.$dateActivity.' </span> </h4>
		                <span class="paddingLR">
		                <button class="btn btn-success" type="button" data-toggle="modal" data-target="#myModal">Reply</button>
		                 '.$_buttons.'
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
		                    <td class="tableFont2">'.$stat.'</td>
		                    </tr>
		                    <tr>
		                    <th scope="row" class="tableFont" align="left">Priority</th>
		                    <td class="tableFont2">'.$priority.'</td>
		                    </tr>
		                    </tbody>
		                  </table>
		                </div>
		            </div>
			            <div class=""><h2 class="meFont" style="padding-bottom:30px;"> Conversation:</h2>';
			}
			else 
			{
				echo '<article class="container" style="margin-top:-18px;">
							 <section class="well well-small mePadd">
							 <h2 class="meFont"><span class="icon-search meMarg"></span> '.$subject.'</h2>
							 <div class="resTable2">
	       						 <h4 class="alert alert-heading" style="font-style: italic;">Date modified: <span class="spnPadding"> '.$dateModified.' </span> Last Activity: <span class="spnPadding"> '.$dateActivity.' </span>
					                <span class="floatR">
					                <button class="btn btn-success" type="button" data-toggle="modal" data-target="#myModal">Reopen</button>
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
				                        <td><center>'.$stat.'</center></td>
				                        <td>'.$priority.'</td>
				                      </tr>
				                    </table>
				                </div>
				            </div>
				            </div>
				             <div class="myTable3 resTable1">
			            	<h4 class="alert alert-heading" style="font-style: italic;">Date modified: <span class="spnPadding"> '.$dateModified.' </span> </h4>
			                <h4 class="alert alert-heading" style="font-style: italic;">Last Activity: <span class="spnPadding"> '.$dateActivity.' </span> </h4>
			                <span class="paddingLR">
			                <button class="btn btn-success" type="button" data-toggle="modal" data-target="#myModal">Reopen</button>
			                 '.$_buttons.'
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
			                    <td class="tableFont2">'.$stat.'</td>
			                    </tr>
			                    <tr>
			                    <th scope="row" class="tableFont" align="left">Priority</th>
			                    <td class="tableFont2">'.$priority.'</td>
			                    </tr>
			                    </tbody>
			                  </table>
			                </div>
			            </div>
				            <div class=""><h2 class="meFont" style="padding-bottom:30px;"> Conversation:</h2>';
			}
				
				for ($i=0;$i<$count;$i++)
				{
					if($ticketInfo->id[$i] == $id)
					{
						$message = urldecode($ticketInfo->msg[$i]);
						$message = str_replace("\'", "'", $message);
						$this->userInfo = new ticketInfo();
						$userinfo = $this->userInfo->getuser($ticketInfo->userId[$i]);
						$userinfo = json_decode($userinfo);
						if(empty($userinfo))
						{
							$username = "this user deleted";
							$userMail = "this user deleted";
						}
						else
						{
							$username = $userinfo->name.' '.$userinfo->lastname;
							$userMail = $userinfo->email;
						}
						$class = "alert-user";
						echo '<li class="alert '.$class.' meUs" style="list-style: none;">
                        <img src="'.self::get_gravatar($userMail, "50").'" width="50" height="50" class="img-circle">
                        <span class="tableFont"> User:</span>
                        <span class="tableFont2" style="background-color:transparent;"> '.$username.'</span>
                        <h6 class="fk">Posted on: <span> '.self::change_date_format($ticketInfo->dateAded[$i]).'</span></h6>
                        <div class="alert '.$class.' meUser" style="width: 95%;">
                        '.$message.'
                        </div>';
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
							@$moderator = "user";
							@$full_name = $userinfo->name.' '.$userinfo->lastname;
							$gravatar = self::get_gravatar($userMail, "50");
						}
						else 
						{
							$class = "alert-success meAd";
							$_class = "alert-success meAdmin";
							$moderator = $answered->moderator[$i];
							$full_name = self::moderator_name($answered->userId[$i]);
							$moderator_mail = self::moderator_email($answered->userId[$i]);
							$gravatar = self::get_gravatar($moderator_mail, "50");
							$_moderator = $answered->moderator[$i];
						}
						if($i == ($_count - 1))
						{
							if($answered->moderator[$i] == "user")
							{
								$msg = urldecode($answered->msg[$i]);
								$msg = str_replace("\'", "'", $msg);
							}
							else 
							{
								if($i > 0)
								{
									$msg = urldecode($answered->msg[$i - 1]);
									$msg = str_replace("\'", "'", $msg);
								}
								else 
								{
									$msg = urldecode($answered->msg[$i]);
									$msg = str_replace("\'", "'", $msg);
								}
							}
						}
						$message = urldecode($answered->msg[$i]);
						$message = str_replace("\'", "'", $message);
						echo ' <li class="alert '.$class.'" style="list-style: none;">
		                        <img src="'.$gravatar.'" width="50" height="50" class="img-circle">
		                        <span class="tableFont"> '.$moderator.':</span>
		                        <span class="tableFont2" style="background-color:transparent;"> '.$full_name.'</span>
		                        <h6 class="fk">Posted on: <span> '.self::change_date_format($answered->dateAded[$i]).'</span></h6>
		                        <div class="alert '.$_class.'" style="width: 95%;">
		                        '.$message.'
		                        </div>
		                      	</li>';
					}
				}
				echo '</div> </section></article>';
				echo '<div class="modal hide fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"><!--Modal Starts-->
			        	<div class="modal-header">
			            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			            <h3 id="myModalLabel">Send a new message</h3>
			            </div>
			            <form action="answer.php" method="post">
			            <div class="modal-body">
			            <textarea class="modalTextarea" required="required" name="message"></textarea>
			            </div>
			            <div class="modal-footer">
			            <input type="hidden" name="id" value="'.$ticketId.'" >
                        <input type="hidden" name="moderator" value="'.@$_moderator.'" >
			            <button class="btn btn-success" type="submit" name="openC"><span class="icon-arrow-right icon-white"></span> Send</button>
			            <button class="btn" data-dismiss="modal" aria-hidden="true"><span class="icon-thumbs-down"></span> Cancel</button>
			            </div>
			            </form>
			        </div><!--Modal Ends--> <!--Details Ends-->';
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
	
	public function show_user_answer_ticket($id,$ticketInfo,$answered,$status)
	{
	$ticketId = $id;
		if($ticketInfo->res == "true")
		{
			$count = count($ticketInfo->userId);
			for ($i=0;$i<$count;$i++)
			{
				if($ticketInfo->id[$i] == $id)
				{
					switch ($ticketInfo->status[$i]) 
						{
							case "open":
								$class = "badge-info";
								$_method = md5("answer");
								$href = "answer.php?method=".$_method.":".$ticketInfo->id[$i];
								$text = "Open";
								$__method = md5("Fclosed");
								$_href = "answer.php?method=".$__method.":".$ticketInfo->id[$i].":".urlencode($ticketInfo->subject[$i]);
								$continue = md5("showO");
								$icon = "icon-folder-open";
						        $buttons = '<a href="'.$_href.'"><button class="btn btn-danger btnClose" type="button"><span class="icon-remove-sign icon-white"></span> Close</button></a>';
								$_buttons = '<a href="'.$href.'"><button class="btn btn-info btnDetails" type="button"><span class="icon-info-sign icon-white"></span> Reply</button></a>';
							break;
							
							case "closed":
								$class = "btn-danger";
								$_method = md5("showC");
								$href = "show.php?method=".$_method.":".$ticketInfo->id[$i];
								$text = "closed";
								$text = "Close";
								$continue = $_method;
								$icon = "icon-folder-close";
								$buttons = '<a href="'.$href.'"><button class="btn btn-info btnDetails" type="button"><span class="icon-info-sign icon-white"></span> Details</button></a>';
								$_buttons = "";
							break;
			
							case "answered":
								$class = "badge-success";
								$_method = md5("showA");
								$href = "show.php?method=".$_method.":".$ticketInfo->id[$i];
								$__method = md5("Fclosed");
								$_href = "answer.php?method=".$__method.":".$ticketInfo->id[$i].":".urlencode($ticketInfo->subject[$i]);
								$text =  "Replied";
								$continue = $_method; 
								$buttons = '<a href="'.$href.'"><button class="btn btn-info btnDetails" type="button"><span class="icon-info-sign icon-white"></span> Details</button></a>';
								$_buttons = '<a href="'.$_href.'"><button class="btn btn-danger" type="button"><span class="icon-remove-sign icon-white"></span> Close</button></a>';
								$icon = "icon-ok";
							break;
							
							case "in progress":
								$class = "badge-inverse";
								$_method = md5("openC");
								$href = "answer.php?method=".$_method.":".$ticketInfo->id[$i];
								$__method = md5("Fclosed");
								$_href = "answer.php?method=".$__method.":".$ticketInfo->id[$i].":".urlencode($ticketInfo->subject[$i]);
								$text = "Pending";
								$continue = $_method;
								$icon = "icon-time";
								$buttons = '<a href="'.$href.'"><button class="btn btn-info btnDetails" type="button"><span class="icon-info-sign icon-white"></span> Details</button></a>';
								$_buttons = '<a href="'.$_href.'"><button class="btn btn-danger" type="button"><span class="icon-remove-sign icon-white"></span> Close</button></a>';
							break;
								
							default:
								$class = "error";
								$href = "#";
								$disabled = "disabled";
								$btn_success = "";
								$text = "error";
								$continue = "";
							break;
						}
						$stat = '<div class="badge '.$class.'" style="width:65px;"><span class="'.$icon.' icon-white"></span> '.$text.'</div>';
						$dateModified = self::change_date_format($ticketInfo->dateAded[$i]);
						$dateActivity = self::change_date_format($ticketInfo->dateActivity[$i]);
						$department = $ticketInfo->department[$i];
						$priority = $ticketInfo->priorety[$i];
						$subject = $ticketInfo->subject[$i];
				}
			}
			if($status == "in progress" or $status == "answered")
			{
				echo '<article class="container hero-unit" style="margin-top:-31px;">
						<section class="well well-small"><!--Details Starts-->
       						 <h2><span class="icon-search" style="margin-top:7px;"></span> '.$subject.'</h2>
       						 <h4 class="alert alert-heading" style="font-style: italic;">Date modified: <span class="spnPadding"> '.$dateModified.' </span> Last Activity: <span class="spnPadding"> '.$dateActivity.' </span>
				                <span class="floatR">
				                <button class="btn btn-success" type="button" data-toggle="modal" data-target="#myModal"><span class="icon-envelope icon-white"></span> Reply</button>
				                '.$_buttons.'
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
			                        <td>'.$stat.'</td>
			                        <td>'.$priority.'</td>
			                      </tr>
			                    </table>
			                </div>
			            </div>
			            <hr><div class="myTable3">';
			}
			else 
			{
				echo '<article class="container hero-unit" style="margin-top:-31px;">
						<section class="well well-small"><!--Details Starts-->
       						 <h2><span class="icon-search" style="margin-top:7px;"></span> '.$subject.'</h2>
       						 <h4 class="alert alert-heading" style="font-style: italic;">Date modified: <span class="spnPadding"> '.$dateModified.' </span> Last Activity: <span class="spnPadding"> '.$dateActivity.' </span>
				                <span class="floatR">
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
			                        <td>'.$stat.'</td>
			                        <td>'.$priority.'</td>
			                      </tr>
			                    </table>
			                </div>
			            </div>
			            <hr><div class="myTable3">';
			}
				
				for ($i=0;$i<$count;$i++)
				{
					if($ticketInfo->id[$i] == $id)
					{
						$message = urldecode($ticketInfo->msg[$i]);
						$message = str_replace("\'", "'", $message);
						$this->userInfo = new ticketInfo();
						$userinfo = $this->userInfo->getuser($ticketInfo->userId[$i]);
						$userinfo = json_decode($userinfo);
						if(empty($userinfo))
						{
							$username = "this user deleted";
							$userMail = "this user deleted";
						}
						else
						{
							$username = "user, ".$userinfo->name.' '.$userinfo->lastname;
							$userMail = $userinfo->email;
						}
						$class = "alert-user";
						echo '<li class="alert '.$class.'" style="list-style: none;">
                        <img src="'.self::get_gravatar($userMail, "50").'" width="50" height="50" class="img-circle">
                        <span class="tableFont"> User:</span>
                        <span class="tableFont2"> '.$username.'</span>
                        <h6>Posted on: <span> '.self::change_date_format($ticketInfo->dateAded[$i]).'</span></h6>
                        <div class="alert '.$class.'" style="width: 95%;">
                        '.$message.'
                        </div>';
					}
				}
				if($answered->result == "true")
				{
					$_count = count($answered->id);
					for ($i=0;$i<$_count;$i++)
					{
						if($answered->moderator[$i] == "user")
						{
							$class = "alert-user";
							@$moderator = "user";
							@$full_name = $userinfo->name.' '.$userinfo->lastname;
							$gravatar = self::get_gravatar($userMail, "50");
						}
						else 
						{
							$class = "alert-success";
							$moderator = $answered->moderator[$i];
							$full_name = self::moderator_name($answered->userId[$i]);
							$moderator_mail = self::moderator_email($answered->userId[$i]);
							$gravatar = self::get_gravatar($moderator_mail, "50");
							$_moderator = $answered->moderator[$i];
						}
						if($i == ($_count - 1))
						{
							if($answered->moderator[$i] == "user")
							{
								$msg = urldecode($answered->msg[$i]);
								$msg = str_replace("\'", "'", $msg);
							}
							else 
							{
								if($i > 0)
								{
									$msg = urldecode($answered->msg[$i - 1]);
									$msg = str_replace("\'", "'", $msg);
								}
								else 
								{
									$msg = urldecode($answered->msg[$i]);
									$msg = str_replace("\'", "'", $msg);
								}
							}
						}
						$message = urldecode($answered->msg[$i]);
						$message = str_replace("\'", "'", $message);
						echo ' <li class="alert '.$class.'" style="list-style: none;">
		                        <img src="'.$gravatar.'" width="50" height="50" class="img-circle">
		                        <span class="tableFont"> '.$moderator.':</span>
		                        <span class="tableFont2"> '.$full_name.'</span>
		                        <h6>Posted on: <span> '.self::change_date_format($answered->dateAded[$i]).'</span></h6>
		                        <div class="alert '.$class.'" style="width: 95%;">
		                        '.$message.'
		                        </div>
		                      	</li>';
					}
				}
				echo '</div> </section></article>';
				echo '<div class="modal hide fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"><!--Modal Starts-->
			        	<div class="modal-header">
			            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			            <h3 id="myModalLabel">Send a new message</h3>
			            </div>
			            <form action="answer.php" method="post">
			            <div class="modal-body">
			            <textarea class="modalTextarea" required="required" name="message"></textarea>
			            </div>
			            <div class="modal-footer">
			            <input type="hidden" name="id" value="'.$ticketId.'" >
                        <input type="hidden" name="moderator" value="'.@$_moderator.'" >
			            <button class="btn btn-success" type="submit" name="openC"><span class="icon-arrow-right icon-white"></span> Send</button>
			            <button class="btn" data-dismiss="modal" aria-hidden="true"><span class="icon-thumbs-down"></span> Cancel</button>
			            </div>
			            </form>
			        </div><!--Modal Ends--> <!--Details Ends-->';
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
$answerTickets = new answerTickets();
?>