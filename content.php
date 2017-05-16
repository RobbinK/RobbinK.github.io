<?php if(@$isLogin and $userinfo->role == "user"): ?>
<?php 
		function _numpage($co_tot,$co)
		{
			if ($co_tot == 0)
			{
				$page = 1;
				return $page;
			}
			
			if($co > $co_tot)
			{
				$co_tot = $co;
				$page = $co_tot / $co;
				return $page;
			}
			else 
			{
				$page = $co_tot / $co;
				if ($page > 0 and $page < 1)
				{
					$page = 2;
					return $page;
				}
				else if ($page > 1 and $page < 2)
				{
					$page = 2;
					return $page;
				}
				 return ceil($page);
			}
		}
		$count = count($ticketInfo->userId);
		$co_tot = 0;
		$co_show = 10;
		for($c = 0; $c < $count; $c++)
		{
			if($ticketInfo->status[$c] != "closed")
			{
				$co_tot++;
				$ticketInfo_id[] = $ticketInfo->id[$c];
				$ticketInfo_status[] = $ticketInfo->status[$c];
				$ticketInfo_subject[] = $ticketInfo->subject[$c];
				$ticketInfo_department[] = $ticketInfo->department[$c];
				$ticketInfo_dateAded[] = $ticketInfo->dateAded[$c];
				$ticketInfo_dateActivity[] = $ticketInfo->dateActivity[$c];
				$ticketInfo_msg[] = $ticketInfo->msg[$c];
			}
		}
		$_count = $co_tot;
		$count = $_count;
		if(isset($_GET["page"]))
		{
			$showPage = $_GET["page"];
		}
		else
		{
			$showPage = 1;
		}
		$_page = _numpage($co_tot, $co_show);
      	if($ticketInfo->res == "true")
		{
			echo '<article class="container" style="margin-top:-18px;">
					<section class="well well-small mePadd">
					 <h2 class="meFont" style="padding-bottom:30px;"><span class="icon-comment meMarg"></span> Tickets</h2>';
			if($showPage == 1)
			{
				$start = 0;
				if($count < $co_show)
				{
					$end = $count;
				}
				else 
				{
					$end = $co_show;
				}
			}
			else 
			{
				$_count = $showPage * $co_show;
				$start = $_count - $co_show;
				if($count < $_count)
				{
					$end = $count;
				}
				else 
				{
					$end = --$_count;
				}
			}
			
			for ($i=$start;$i<$end;$i++)
			{
				if($ticketInfo_status[$i] != "closed")
				{
					switch ($ticketInfo_status[$i]) 
					{
						case "open":
							$class = "badge-info";
							$_method = md5("Fclosed");
							$href = "tickets.php?method=".$_method.":".$ticketInfo_id[$i].":".urldecode($ticketInfo_subject[$i]);
							$_href = "show.php?method=".md5("showO").":".$ticketInfo_id[$i];
							$disabled = "";
							$btn_success = "btn-warning";
							$text = "Open";
							$continue =  md5("showO");
							$icon = "icon-folder-open";
				            $buttons = '<a href="'.$_href.'"><button class="btn btn-info btnDetails" type="button"> Details</button></a>';
				            $_buttons = '<a href="'.$href.'"><button class="btn btn-danger btnClose" type="button"> Close</button></a>';
						break;
						
						case "closed":
							$class = "badge-close";
							$_method = md5("showC");
							$href = "show.php?method=".$_method.":".$ticketInfo_id[$i];
							$_href = "";
							$disabled = "";
							$btn_success = "";
							$text = "Close";
							$continue = $_method;
							$icon = "icon-folder-close";
							$buttons = '<a href="'.$href.'"><button class="btn btn-info btnDetails" type="button"> Details</button></a>';
							$_buttons = "";
						break;
		
						case "answered":
							$class = "badge-success";
							$_method = md5("view");
							$href = "tickets.php?method=".$_method.":".$ticketInfo_id[$i];
							$_href = "tickets.php?method=".md5("Fclosed").":".$ticketInfo_id[$i].":".$ticketInfo_subject[$i];
							$disabled = "";
							$btn_success = "btn-success";
							$text =  "Replied";
							$continue = md5("showA");
							$icon = "icon-ok";
							$buttons = '<a href="'.$href.'"><button class="btn btn-info btnDetails" type="button"> Details</button></a>';
							$_buttons = '<a href="'.$_href.'"><button class="btn btn-danger btnClose" type="button"> Close</button></a>';
						break;
						
						case "in progress":
							$class = "badge-inverse";
							$_method = md5("view");
							$href = "tickets.php?method=".$_method.":".$ticketInfo_id[$i];
							$_href = "tickets.php?method=".md5("Fclosed").":".$ticketInfo_id[$i].":".$ticketInfo_subject[$i];
							$disabled = "";
							$btn_success = "btn-primary";
							$text = "Pending";
							$continue = md5("openC");
							$icon = "icon-time";
							$buttons = '<a href="'.$href.'"><button class="btn btn-info btnDetails" type="button"> Details</button></a>';
							$_buttons = '<a href="'.$_href.'"><button class="btn btn-danger btnClose" type="button"> Close</button></a>';
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
							$_buttons = "";
							$_href = "";
						break;
					}
					
					$message = urldecode($ticketInfo_msg[$i]);
					$message = str_replace("\'", "'", $message);
					$exp = explode(" ",$message);
					if(count($exp) > 20)
					{
						$discription = substr($message, 0, 400);
						$show = $continue;
						$show = "show.php?method=".$show.":".$ticketInfo_id[$i];
						$discription = $discription.' '.'&hellip; <a href="'.$show.'"> Continue reading <span class="meta-nav">&rarr;</span></a>';
					}
					else 
					{
						$discription = $message;
					}
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
				                    <td class="tableFont2">'.$ticketInfo_dateAded[$i].'</td>
				                    </tr>
				                    <tr>
				                    <th scope="row" class="tableFont" align="left">Last Activity</th>
				                    <td class="tableFont2">'.$ticketInfo_dateActivity[$i].'</td>
				                    </tr>
				                    </tbody>
				                  </table>
				                <div class="paddingRL resTable1 mePadB">
				                <div class="tableW">
				                '.$discription.'
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
						
					echo ' 			<td>'.$ticketInfo_id[$i].'</td>
				                        <td>
				                        	<center><div class="badge '.$class.'" style="width:50px;">'.$text.'</div></center>
				                        </td>
				                        <td>'.$ticketInfo_subject[$i].'</td>
				                        <td>'.$ticketInfo_department[$i].'</td>
				                        <td>'.$ticketInfo_dateAded[$i].'</td>
				                        <td>'.$ticketInfo_dateActivity[$i].'</td>
				                      </tr>
				                    </table>
				                </div>
				                <div class="paddingRL resTable2 mePadB">
				                <div class="tableW">'.$discription.'</div>
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
			if ($_page == 1)
			{
				echo '<div class="pagination pagination-centered">
						<ul>
							<li class="active"><a href="#">1</a></li>
						</ul>
					 </div>';
			}
			else 
			{
				echo '<div class="pagination pagination-centered"><ul>';
				for($i = 1;$i <= $_page; $i++)
				{
					if ($showPage == $i)
					{
						echo '<li class="active"><a href="#">'.$i.'</a></li>';
					}
					else 
					{
						echo '<li><a href=".?page='.$i.'">'.$i.'</a></li>';
					}
				}
				echo '</ul></div>';
			}
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
?>
</section>
</article>
<?php endif;?>