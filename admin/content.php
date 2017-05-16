<?php 
if (@$isLogin and $userinfo->role == "admin"): 
?>
<?php
	if($ticketInfo->res == "true")
	{
		echo '<article class="container" style="margin-top: -18px;">
					<section class="well well-small mePadd">
        				<h2 class="meFont" style="padding-bottom:30px;"><span class="icon-comment meMarg"></span> 5 Very high priority tickets</h2>';
		$count = count($ticketInfo->id);
		$_count = 0;
		for($i = 0; $i < $count; $i++)
		{
			if($ticketInfo->priorety[$i] == "very high" or $ticketInfo->priorety[$i] == "Very high" and $ticketInfo->status[$i] != "closed" and $ticketInfo->status[$i] != "answered")
			{ 
				$_count++;
				$ticketInfo_id[] = $ticketInfo->id[$i];
				$ticketInfo_status[] = $ticketInfo->status[$i];
				$ticketInfo_priorety[] = $ticketInfo->priorety[$i];
				$ticketInfo_msg[] = $ticketInfo->msg[$i];
				$ticketInfo_subject[] = $ticketInfo->subject[$i];
			    $ticketInfo_department[] = $ticketInfo->department[$i];
			    $ticketInfo_dateAded[] = $ticketInfo->dateAded[$i];
			    $ticketInfo_dateActivity[] = $ticketInfo->dateActivity[$i];
			}
		}
		if($_count > 5)	$_count = 5;
		$j = 0;
		for ($i=0;$i<$_count;$i++)
		{
			if(($ticketInfo_priorety[$i] == "very high" or $ticketInfo_priorety[$i] == "Very high") and $ticketInfo_status[$i] != "closed" and $ticketInfo_status[$i] != "answered")
			{
				$j++;
				switch ($ticketInfo_status[$i]) 
				{
					case "open":
						$class = "badge-info";
						$_method = md5("answer");
						$href = "answer.php?method=".$_method.":".$ticketInfo_id[$i];
						$text = "Open";
						$__method = md5("Fclosed");
						$_href = "answer.php?method=".$__method.":".$ticketInfo_id[$i].":".urlencode($ticketInfo_subject[$i]);
						$continue = md5("showO");
						$icon = "icon-folder-open";
				        $_buttons = '<a href="'.$_href.'"><button class="btn btn-danger btnClose" type="button">Close</button></a>';
						$buttons = '<a href="'.$href.'"><button class="btn btn-success btnDetails" type="button">Reply</button></a>';
						$___method = md5("showO");
						$___href = "show.php?method=".$___method.":".$ticketInfo_id[$i];
						$__buttons = '<a href="'.$___href.'"><button class="btn btn-info btnDetails" type="button">Details</button></a>';
				    break;
					
					case "answered":
						$class = "badge-success";
						$_method = md5("showA");
						$href = "show.php?method=".$_method.":".$ticketInfo_id[$i];
						$__method = md5("Fclosed");
						$_href = "answer.php?method=".$__method.":".$ticketInfo_id[$i].":".urlencode($ticketInfo_subject[$i]);
						$text =  "Replied";
						$continue = $_method;
						$icon = "icon-ok";
						$buttons = '<a href="'.$href.'"><button class="btn btn-info btnDetails" type="button">Details</button></a>';
						$_buttons = '<a href="'.$_href.'"><button class="btn btn-danger btnClose" type="button">Close</button></a>';
						$__buttons = "";
					break;
					
					case "in progress":
						$class = "badge-inverse";
						$_method = md5("openC");
						$__method = md5("Fclosed");
						$href = "answer.php?method=".$_method.":".$ticketInfo_id[$i];
						$_href = "answer.php?method=".$__method.":".$ticketInfo_id[$i].":".urlencode($ticketInfo_subject[$i]);
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
						$a = "";
						$continue = "";
						$_buttons = "";
						$__buttons = "";
					break;
				}
				$message = urldecode($ticketInfo_msg[$i]);
				$message = str_replace("\'", "'", $message);
				$exp = explode(" ",$message);
				if(count($exp) > 20)
				{
					$discription = substr($message, 0, 400);
					$show = $continue;
					$show = $show.":".$ticketInfo_id[$i];
					$discription = $discription.' '.'&hellip; <a href="show.php?method='.$show.'"> Continue reading <span class="meta-nav">&rarr;</span></a>';
				}
				else 
				{
					$discription = $message;
				}
				$dateModified = $_ticketInfo->change_date_format($ticketInfo_dateAded[$i]);
				$dateActivity = $_ticketInfo->change_date_format($ticketInfo_dateActivity[$i]);
				echo '<div class="myTable">
               				 <div class="tableH">
               				 <div class="resTable1">
				                  <table class="tables" width="100%" style="border:none;">
				                    <tbody>
				                    <tr>
				                    <th style="border-top:none;" scope="row" class="tableFont" align="left">ID</th>
				                    <td style="border-top:none;" class="tableFont2">'.$ticketInfo_id[$i].'</td>
				                    </tr>
				                    <tr>
				                    <th scope="row" class="tableFont" align="left">Status</th>
				                    <td class="tableFont2"><div class="badge '.$class.'" style="width:40px;">'.$text.'</div></td>
				                    </tr>
				                    <tr>
				                    <th scope="row" class="tableFont" align="left">Subject</th>
				                    <td class="tableFont2">'.$ticketInfo_subject[$i].'</td>
				                    </tr>
				                    <tr>
				                    <th scope="row" class="tableFont" align="left">Department</th>
				                    <td class="tableFont2">'.$ticketInfo_department[$i].'</td>
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
				                '.$discription.'
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
					
				echo ' 			<td>'.$ticketInfo_id[$i].'</td>
			                        <td>
			                        	<center><div class="badge '.$class.'" style="width:50px;">'.$text.'</div></center>
			                        </td>
			                        <td>'.$ticketInfo_subject[$i].'</td>
			                        <td>'.$ticketInfo_department[$i].'</td>
			                        <td>'.$dateModified.'</td>
			                        <td>'.$dateActivity.'</td>
			                      </tr>
			                    </table>
			                </div>
			                <div class="paddingRL resTable2 mePadB">
			                <div class="tableW">'.$discription.'</div>
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
			<center><b>NO TICKETS</b></center>
			</div>
			</section>
			</article>';
	}
	if(@$j == "0")
	{
		echo '
			<article class="container" style="margin-top:-20px;">
			<section class="well well-small mePadd">
			<div class="alert alert-info">
			<center><b>NO TICKETS</b></center>
			</div>
			</section>
			</article>';
	}		  
?>
<!-- ---------------- -->	
<?php
	if($ticketInfo->res == "true")
	{
		echo '<article class="container" style="margin-top:-23px;">
					<section class="well well-small mePadd">
						<h2 class="meFont" style="padding-bottom:30px;"><span class="icon-comment meMarg"></span> 5 First tickets based on activity date</h2>';
		$end = 0;
		for ($i=0;$i<$count;$i++)
		{
			if($end < 5)
			{
				if($ticketInfo->status[$i] != "closed")
				{
					$end++;
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
		
						case "answered":
							$class = "badge-success";
							$_method = md5("showA");
							$__method = md5("Fclosed");
							$href = "show.php?method=".$_method.":".$ticketInfo->id[$i];
							$_href = "answer.php?method=".$__method.":".$ticketInfo->id[$i].":".urlencode($ticketInfo->subject[$i]);
							$text =  "Replied";
							$continue = $_method; 
							$buttons = '<a href="'.$href.'"><button class="btn btn-info btnDetails" type="button">Details</button></a>';
							$_buttons = '<a href="'.$_href.'"><button class="btn btn-danger btnClose" type="button">Close</button></a>';
							$__buttons = "";
							$icon = "icon-ok";
						break;
						
						case "in progress":
							$class = "badge-inverse";
							$_method = md5("openC");
							$__method = md5("Fclosed");
							$href = "answer.php?method=".$_method.":".$ticketInfo->id[$i];
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
							$a = "";
							$continue = "";
						break;
					}
					$message = urldecode($ticketInfo->msg[$i]);
					$message = str_replace("\'", "'", $message);
					$exp = explode(" ",$message);
					if(count($exp) > 20)
					{
						$discription = substr($message, 0, 400);
						$show = $continue;
						$show = $show.":".$ticketInfo->id[$i];
						$discription = $discription.' '.'&hellip; <a href="show.php?method='.$show.'"> Continue reading <span class="meta-nav">&rarr;</span></a>';
					}
					else 
					{
						$discription = $message;
					}
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
				                '.$discription.'
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
			                <div class="tableW">'.$discription.'</div>
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
		}
		echo '</section></article>';
	}
	else 
	{
		echo '
		<article class="container" style="margin-top:-23px;">
			<section class="well well-small mePadd">
			<div class="alert alert-info">
			<center><b>NO TICKETS</b></center>
			</div>
			</section>
	    </article>';
	}		  
?>
<?php endif; ?>