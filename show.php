<?php
include 'admin/userLogin.php';
include 'answer.php';
include 'header.php';
include 'menu.php';
if(@$isLogin and $userinfo->role == "user"):
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
				$count = count($ticketInfo->userId);
				echo '<article class="container hero-unit" style="margin-top:-18px;">
						<section class="well well-small mePadd">
        					<h2 class="meFont" style="padding-bottom:30px;"><span class="icon-comment meMarg"></span> Tickets</h2>';
				for ($i=0;$i<$count;$i++)
				{
					if($ticketInfo->id[$i] == $id)
					{
						switch ($ticketInfo->status[$i]) 
						{
							case "open":
							$class = "badge-info";
							$_method = md5("Fclosed");
							$href = "tickets.php?method=".$_method.":".$ticketInfo->id[$i].":".urldecode($ticketInfo->subject[$i]);//----
							$disabled = "";
							$btn_success = "btn-warning";
							$text = "Open";
							$continue =  md5("show");
							$icon = "icon-folder-open";
				            $buttons = '<a href="'.$href.'"><button class="btn btn-danger btnClose" type="button"><span class="icon-remove-sign icon-white"></span> Close</button></a>';
							break;
							
							case "closed":
								$class = "badge-close";
								$_method = md5("showC");
								$href = "show.php?method=".$_method.":".$ticketInfo->id[$i];
								$disabled = "";
								$btn_success = "";
								$text = "Close";
								$continue = $_method;
								$icon = "icon-folder-close";
								$buttons = '<a href="'.$href.'"><button class="btn btn-info btnDetails" type="button"><span class="icon-info-sign icon-white"></span> Details</button></a>';
							break;
			
							case "answered":
								$class = "badge-success";
								$_method = md5("view");
								$href = "tickets.php?method=".$_method.":".$ticketInfo->id[$i];
								$disabled = "";
								$btn_success = "btn-success";
								$text =  "Replied";
								$continue = md5("showA");
								$icon = "icon-ok";
								$buttons = '<a href="'.$href.'"><button class="btn btn-info btnDetails" type="button"><span class="icon-info-sign icon-white"></span> Details</button></a>';
							break;
							
							case "in progress":
								$class = "badge-inverse";
								$_method = md5("view");
								$href = "tickets.php?method=".$_method.":".$ticketInfo->id[$i];
								$disabled = "";
								$btn_success = "btn-primary";
								$text = "Pending";
								$continue = md5("openC");
								$icon = "icon-time";
								$buttons = '<a href="'.$href.'"><button class="btn btn-info btnDetails" type="button"><span class="icon-info-sign icon-white"></span> Details</button></a>';
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
				                    <td class="tableFont2">'.$ticketInfo->dateAded[$i].'</td>
				                    </tr>
				                    <tr>
				                    <th scope="row" class="tableFont" align="left">Last Activity</th>
				                    <td class="tableFont2">'.$ticketInfo->dateActivity[$i].'</td>
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
						
					echo ' 			<td>'.$ticketInfo->id[$i].'</td>
				                        <td>
				                        	<center><div class="badge '.$class.'" style="width:50px;">'.$text.'</div></center>
				                        </td>
				                        <td>'.$ticketInfo->subject[$i].'</td>
				                        <td>'.$ticketInfo->department[$i].'</td>
				                        <td>'.$ticketInfo->dateAded[$i].'</td>
				                        <td>'.$ticketInfo->dateActivity[$i].'</td>
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
				echo '</section>
					</article>';
			}
		}
		
		if($method == $show_close)
		{
			$answered = $_ticketInfo->view_answered($id);
			$answered = json_decode($answered);
			$status = "closed";
			$answer->show_answer_ticket($id, $ticketInfo, $answered, $userinfo, $status);
		}
		if($method == $show_search)
		{
			$status = $exp[2];
			$answered = $_ticketInfo->view_answered($id);
			$answered = json_decode($answered);
			$answer->show_answer_ticket($id, $ticketInfo, $answered, $userinfo, $status);
		}
		if($method == $show_answered)
		{
			$answered = $_ticketInfo->view_answered($id);
			$answered = json_decode($answered);
			$status = "answered";
			$answer->show_answer_ticket($id, $ticketInfo, $answered, $userinfo, $status);
		}
		if ($method == $show_in_progress)
		{
			$answered = $_ticketInfo->view_answered($id);
			$answered = json_decode($answered);
			$status = "in progress";
			$answer->show_answer_ticket($id, $ticketInfo, $answered, $userinfo, $status);
		}
		if($method == $show_open)
		{
			$answered = $_ticketInfo->view_answered($id);
			$answered = json_decode($answered);
			$status = "open";
			$answer->show_answer_ticket($id, $ticketInfo, $answered, $userinfo, $status);
		}
	}
?> 

<?php endif;?>