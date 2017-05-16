<?php
include 'userLogin.php';
include 'header.php';
include 'menu.php';
include 'answerTickets.php';
if(@$isLogin and $userinfo->role == "admin"):
?>
<?php 
	if (isset($_GET["method"]))
	{
		$method = $_GET["method"];
		$exp = explode(":", $method);
		$method = $exp[0];
		$id = $exp[1];
		$answer = md5("answer");
		$open_converstion = md5("openC");
		$edit = md5("edit");
		$fclose = md5("Fclosed");
		$close = md5("close");
		if($method == $answer)
		{
			$answerTickets->show_answer($id, $ticketInfo);
		}
		
		elseif ($method == $open_converstion)
		{
			$answered = $_ticketInfo->view_answered($id);
			$answered = json_decode($answered);
			$status = "in progress";
			$answerTickets->show_answer_ticket($id, $ticketInfo, $answered, $status);
		}	
		elseif ($method == $edit) 
		{
			$answerTickets->show_edit($id,$ticketInfo);
		}
		elseif ($method == $fclose)
		{
			$subject = $exp[2];
			
			echo '<article class="container" style="margin-top:-18px;">
					<section class="well well-small mePadd">
						<h2 class="meFont" style="padding-bottom:30px;"><span class="icon-comment meMarg"></span> Subject: '.$subject.', ID: '.$id.'</h2>
						<div class="alert alert-error">
							<center><p><b>Do you want to close this thicket? </b> </p>
							<a href="answer.php?method='.md5("close").':'.$id.'" class="btn btn-danger">Yes</a>
							<a href="'.$_SERVER["HTTP_REFERER"].'" class="btn" type="button">No</a></center>
						 </div>
					</section>
					</article>	 ';
		}
		elseif ($method == $close)
		{
			if($_ticketInfo->closeTicket($id))
			{
				echo '<article class="container" style="margin-top:-18px;">
						<section class="well well-small mePadd">
						<div class="alert alert-success">
						<center><b>The ticket has been closed.</b></center>
					  </div>';
				if($ticketInfo->res == "true")
				{
					$count = count($ticketInfo->userId);
					for ($i=0;$i<$count;$i++)
					{
						if($ticketInfo->id[$i] == $id)
						{
							$class = "btn-danger";
							$_method = md5("showC");
							$href = "show.php?method=".$_method.":".$ticketInfo->id[$i];
							$disabled = "";
							$btn_success = "";
							$text = "Close";
							$continue = $_method;
							$icon = "icon-folder-close";
							$_buttons = "";
							$buttons = '<a href="'.$href.'"><button class="btn btn-info btnDetails" type="button">Details</button></a>';
							$message = urldecode($ticketInfo->msg[$i]);
							$message = str_replace("\'", "'", $message);
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
				                    <td class="tableFont2">'.$_ticketInfo->change_date_format($ticketInfo->dateAded[$i]).'</td>
				                    </tr>
				                    <tr>
				                    <th scope="row" class="tableFont" align="left">Last Activity</th>
				                    <td class="tableFont2">'.$_ticketInfo->change_date_format($ticketInfo->dateActivity[$i]).'</td>
				                    </tr>
				                    </tbody>
				                  </table>
				                <div class="paddingRL resTable1 mePadB">
				                <div class="tableW">
				                '.$message.'
				                </div>
				                    <div class="pull-right paddingTop">
				                    	'.$buttons.'
						                '.$_buttons.'
				                    </div>
				                </div>
				                </div>
				                
			                    <div class="resTable2" style="margin-top:-18px;">
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
					                        <td>'.$_ticketInfo->change_date_format($ticketInfo->dateAded[$i]).'</td>
					                        <td>'.$_ticketInfo->change_date_format($ticketInfo->dateActivity[$i]).'</td>
					                      </tr>
					                    </table>
					                </div>
					                <div class="paddingRL resTable2 mePadB">
					                <div class="tableW">'.$message.'</div>
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
					echo '</section></article>';
				}
			}
			else 
			{
				echo '<article class="container" style="margin-top:-18px;">
						<section class="well well-small mePadd">
						<div class="alert alert-error">
						<center><b>ERROR! please try again.</b></center>
					  </div>';
				if($ticketInfo->res == "true")
				{
					$count = count($ticketInfo->userId);
					for ($i=0;$i<$count;$i++)
					{
						if($ticketInfo->id[$i] == $id)
						{
							$class = "badge-info";
							$_method = md5("answer");
							$href = "answer.php?method=".$_method.":".$ticketInfo->id[$i];
							$text = "Open";
							$__method = md5("Fclosed");
							$_href = "answer.php?method=".$__method.":".$ticketInfo->id[$i].":".urlencode($ticketInfo->subject[$i]);
							$continue = md5("show");
							$icon = "icon-folder-open";
						    $buttons = '<a href="'.$_href.'"><button class="btn btn-danger btnClose" type="button">Close</button></a>';
							$_buttons = '<a href="'.$href.'"><button class="btn btn-info btnDetails" type="button">Reply</button></a>';
							$message = urldecode($ticketInfo->msg[$i]);
							$message = str_replace("\'", "'", $message);
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
				                    <td class="tableFont2">'.$_ticketInfo->change_date_format($ticketInfo->dateAded[$i]).'</td>
				                    </tr>
				                    <tr>
				                    <th scope="row" class="tableFont" align="left">Last Activity</th>
				                    <td class="tableFont2">'.$_ticketInfo->change_date_format($ticketInfo->dateActivity[$i]).'</td>
				                    </tr>
				                    </tbody>
				                  </table>
				                <div class="paddingRL resTable1 mePadB">
				                <div class="tableW">
				                '.$message.'
				                </div>
				                    <div class="pull-right paddingTop">
				                    	'.$buttons.'
						                '.$_buttons.'
				                    </div>
				                </div>
				                </div>
				                
			                    <div class="resTable2" style="margin-top:-18px;">
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
						                        <td>'.$_ticketInfo->change_date_format($ticketInfo->dateAded[$i]).'</td>
						                        <td>'.$_ticketInfo->change_date_format($ticketInfo->dateActivity[$i]).'</td>
						                      </tr>
						                    </table>
						                </div>
						                <div class="paddingRL resTable2 mePadB">
						                <div class="tableW">'.$message.'</div>
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
					echo '</section></article>';
				}
			}
		}
		else
		{
			echo '<article class="container" style="margin-top:-18px;">
						<section class="well well-small mePadd">
						<div class="alert alert-error">
  						<center><b>ERROR!</b></center>
				 </div></section></article>';
		}	
	}
	
	if(isset($_POST["answer"]))
	{
		$message = $_POST["message"];
		if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') 
		{
			$message = $_POST["message"];
		    $message = str_replace("'", "\'", $message);
		}
		$moderator = $_POST["moderator"];
		$ticketId = $_POST["ticketId"];
		$method = $_POST["method"];
		$date = date('YmdHis');
		$answerTickets = new answerTickets();
		if($method == "answer")
		{
			$answerTickets->answer($userId,$ticketId, $message, $moderator, $date);
		}
	}
	if(isset($_POST["edit"]))
	{
		$message = $_POST["message"];
		if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') 
		{
			$message = $_POST["message"];
		    $message = str_replace("'", "\'", $message);
		}
		$answerId = $_POST["answerId"];
		$moderator = $_POST["moderator"];
		$ticketId = $_POST["ticketId"];
		$method = $_POST["method"];
		$date = date('YmdHis');
		if($method == "edit")
		{
			$answerTickets->edit($answerId, $ticketId, $message, $moderator, $date);
		}
	}
	if(isset($_POST["openC"]))
	{
		if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') 
		{
			$message = $_POST["message"];
		    $message = str_replace("'", "\'", $message);
		}
		$message = $_POST["message"];
		$ticketId = $_POST["id"];
		$date = date('YmdHis');
		$moderator = $_POST["moderator"];
		$answerTickets->answer($userId,$ticketId, $message, $moderator, $date);
	}
?>
<?php include 'footer.php'; endif;?>