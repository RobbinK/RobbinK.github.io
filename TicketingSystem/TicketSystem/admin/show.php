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
		$show = md5("show");
		$show_close = md5("showC");
		$show_answered = md5("showA");
		$show_in_progress = md5("openC");
		$show_search = md5("search");
		$show_open = md5("showO");		
		if($method == $show)
		{
			if($ticketInfo->res == "true")
			{
				$count = count($ticketInfo->id);
				for ($i=0;$i<$count;$i++)
				{
					if($ticketInfo->id[$i] == $id)
					{
						$sub = $ticketInfo->subject[$i];
					}
				}
				echo '<article class="container" style="margin-top:-18px;">
						<section class="well well-small mePadd">
						    <h2 class="meFont" style="padding-bottom:30px;"><span class="icon-comment meMarg"></span>'.$sub.'</h2>';
				
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
						        $_buttons = '<a href="'.$_href.'"><button class="btn btn-danger btnClose" type="button">Close</button></a>';
								$buttons = '<a href="'.$href.'"><button class="btn btn-success btnDetails" type="button">Reply</button></a>';
								$___method = md5("showO");
								$___href = "show.php?method=".$___method.":".$ticketInfo->id[$i];
								$__buttons = '<a href="'.$___href.'"><button class="btn btn-info btnDetails" type="button">Details</button></a>';
							break;
							
							case "closed":
								$class = "btn-danger";
								$_method = md5("showC");
								$href = "show.php?method=".$_method.":".$ticketInfo->id[$i];
								$text = "closed";
								$text = "Close";
								$continue = $_method;
								$icon = "icon-folder-close";
								$buttons = '<a href="'.$href.'"><button class="btn btn-info btnDetails" type="button">Details</button></a>';
								$_buttons = "";
								$__buttons = "";
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
								$_buttons = '<a href="'.$_href.'"><button class="btn btn-danger btnClose" type="button">Close</button></a>';
								$icon = "icon-ok";
								$__buttons = "";
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
								$_buttons = '<a href="'.$_href.'"><button class="btn btn-danger btnClose" type="button">Close</button></a>';
								$__buttons = "";
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
						$message = urldecode($ticketInfo->msg[$i]);
						$message = str_replace("\'", "'", $message);
						$dateModified = $_ticketInfo->change_date_format($ticketInfo->dateAded[$i]);
						$dateActivity = $_ticketInfo->change_date_format($ticketInfo->dateActivity[$i]);
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
				                '.$message.'
				                </div>
				                    <div class="pull-right paddingTop">
				                    	'.$buttons.'
						                '.$__buttons.'
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
						                <div class="tableW">'.$message.'</div>
						                 <div class="pull-right paddingTop">
						                 '.$buttons.'
						                  '.$__buttons.'
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
			else 
			{
				echo '
				<article class="container" style="margin-top:-18px;">
					<section class="well well-small mePadd">
					<div class="alert alert-info">
					<center><b>NO TICKET</b></center>
					</div>
					</section>
			    </article>';
			}		  
		}
		if($method == $show_close)
		{
			$answered = $_ticketInfo->view_answered($id);
			$answered = json_decode($answered);
			$status = "closed";
			$answerTickets->show_answer_ticket($id, $ticketInfo, $answered, $status);
		}
		if($method == $show_answered)
		{
			$answered = $_ticketInfo->view_answered($id);
			$answered = json_decode($answered);
			$status = "answered";
			$answerTickets->show_answer_ticket($id, $ticketInfo, $answered, $status);
		}
		if($method == $show_search)
		{
			$status = $exp[2];
			$answered = $_ticketInfo->view_answered($id);
			$answered = json_decode($answered);
			$answerTickets->show_answer_ticket($id, $ticketInfo, $answered, $status);
		}
		if($method == $show_in_progress)
		{
			$answered = $_ticketInfo->view_answered($id);
			$answered = json_decode($answered);
			$status = "in progress";
			$answerTickets->show_answer_ticket($id, $ticketInfo, $answered, $status);
		}
		if($method == $show_open)
		{
			$answered = $_ticketInfo->view_answered($id);
			$answered = json_decode($answered);
			$status = "open";
			$answerTickets->show_answer_ticket($id, $ticketInfo, $answered, $status);
		}
	}
?>
<?php include 'footer.php'; endif;?>