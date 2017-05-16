<?php 
if(@$isLogin and $userinfo->role == "user"):
		include 'admin/ticketInfo.php';
		$_key = $_COOKIE['login']; 
		$userinfo = $Login->getUserInfo($_key);
		$userinfo = json_decode($userinfo);
		if($userinfo->result == "true")
		{
			$userId = $userinfo->id;
		}
		else 
		{
			$userId = "0";
		}
		$ticketInfo = $_ticketInfo->getUserTicketInfo($userId);
		$ticketInfo = json_decode($ticketInfo);
		if($userinfo->isBlock == "1")
		{
			echo '<div class="alert alert-error">
  				<center><b>You are blocked</b></center>
				</div>';
			exit();
		}
		$count = count($ticketInfo->id);
		$allTickets = $count;
		$closeTickets = 0;
		$openTickets = 0;
		$answeredTickets = 0;
		$inProgresTickets = 0;
		$startSpan = '<span class="myBadge">';
		$endSpan = '</span>';
		for ($i = 0; $i < $count; $i++)
		{
			switch (@$ticketInfo->status[$i])
			{
				case "open":
					$openTickets++;
				break;
					
				case "closed":
					$closeTickets++;
				break;

				case "answered":
					$answeredTickets++;
				break;
					
				case "in progress":
					$inProgresTickets++;
				break;
					
				default:
					$allTickets;
				break;
			}
		} 
?>
<body>
<script src="http://code.jquery.com/jquery-latest.js"></script>
<script type="text/javascript" src="js/bootstrap.js"></script> 
<script>
function new_captcha()
{
	document.getElementById('captcha').src = 'securimage/securimage_show.php?' + Math.random(); return false
}
</script>
<div class="navbar navbar-inverse" style="position: static;">
              <div class="navbar-inner">
                <div class="container">
                  <a class="btn btn-navbar" data-toggle="collapse" data-target=".subnav-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                  </a>
                  <a class="brand marginTop" href="."><i class="icon-home icon-white"></i></a>
                  <div class="nav-collapse subnav-collapse">
                    <ul class="nav">
                      <li class="dropdown">
		                <a href="#" class="dropdown-toggle brand" data-toggle="dropdown">Tickets <b class="caret"></b></a>
		                <ul class="dropdown-menu">
		                 <li class="divider"></li>
		                  <li><a href="tickets.php?show=<?php echo md5("all");?>">All tickets: <?php echo $startSpan.$allTickets.$endSpan;?></a></li>
		                  <li><a href="tickets.php?show=<?php echo md5("closed");?>">Closed tickets: <?php echo $startSpan.$closeTickets.$endSpan;?></a></li>
		                  <li><a href="tickets.php?show=<?php echo md5("open");?>">Open tickets: <?php echo $startSpan.$openTickets.$endSpan;?></a></li>
		                   <li><a href="tickets.php?show=<?php echo md5("answered");?>">Answered tickets: <?php echo $startSpan.$answeredTickets.$endSpan;?></a></li>
		                    <li><a href="tickets.php?show=<?php echo md5("progress");?>">In progress tickets: <?php echo $startSpan.$inProgresTickets.$endSpan;?></a></li>
							<li class="divider"></li>               
		                </ul>
		              </li>
                      <li class="divider-vertical"></li>
                      <li><a href="setting.php">Edit profile</a></li>
                      <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Send A New Ticket <b class="caret"></b></a>
                        <ul class="dropdown-menu paddingLR">
                          <li class="myForm pull-left">
                          	<p></p>
                            <form class="form-inline navbar-search" action="newTicket.php" method="post" name="newTicket" onsubmit="return IE1()">
                           
                                            <div class="control-group paddingLR">
                                                    <div>
                                                        <input value="<?php echo $userinfo->email; ?>" name="email" type="hidden" required="required" class="meIn">
                                                    </div>
                                            </div>
                                      
                                            <div class="control-group paddingLR">
                                                    <div>
                                                        <input value="<?php echo $userinfo->name; ?>" name="name" type="hidden" required="required" class="meIn">
                                                    </div>
                                            </div>
                                        
                                            <div class="control-group paddingLR">
                                                    <div class="">
                                                         <input value="<?php echo $userinfo->lastname; ?>" name="lname" type="hidden" required="required" class="meIn">
                                                    </div>
                                             </div>
                                       
                                            <div class="control-group paddingLR">
                                                <label class="control-label" for="inputPassword">Department</label>
                                                    <div class="">
                                                         <select name="department" class="meIn">
<?php                                                          
														$get_option = file_get_contents("admin/moderatorRoles.txt");
														$exp_get_option = explode("\n", $get_option);
														foreach ($exp_get_option as $value)
														{
															echo '<option>'.trim($value).'</option>';
														}
?>
	                                                             
                                                         </select>
                                                    </div>   
                                            </div>
                                       
                                             <div class="control-group paddingLR">
                                                 <label class="control-label" for="inputPassword">Priority</label>
                                                     <div class="">
                                                         <select name="priorety" class="meIn">
                                                             <option>Low</option>
                                                             <option>Normal</option>
                                                             <option>High</option>
                                                             <option>Very high</option>
                                                         </select>
                                                     </div>
                                            </div>
                                        
                                            <div class="control-group paddingLR">
                                                <label class="control-label" for="inputPassword">Subject</label>
                                                     <div class="">
                                                          <input placeholder="Subject" name="subject"  type="text" required="required" class="meIn">
                                                     </div>
                                            </div>
                                     
                                            <div class="control-group paddingLR">
                                                <label class="control-label" for="inputPassword">Message</label>
                                                     <div class="">
                                                          <textarea class="myTextarea meIn" rows="5" name="message" placeholder="Message" required="required"></textarea>
                                                     </div>
                                            </div>
                                      
                                      		 <br/>
                                            <div class="control-group paddingLR"><!-- CAPTCHA -->
                                      		<label class="control-label" for="inputPassword">CAPTCHA</label>
                                            <div class="">
                                             <img id="captcha" src="securimage/securimage_show.php" alt="CAPTCHA Image"  /><br/><br/>
											 <input class="input-block-level meIn"  type="text" required="required" name="captcha_code" size="10" maxlength="6" />
											 <a href="#" onclick="return new_captcha();" style="padding-left: 0px;">[ Different Image ]</a>
                                            </div>
                                             </div>
                                      
                                            <div class="control-group">
                                                <div class="paddingLR">
                                                     <button type="submit" name="sendTicket" class="btn">Send</button>
                                                     <span class="pull-right" id="error" style="color: #F33; padding-top:10px;"></span>
                                                </div>
                                            </div>
                            </form>
                            <p></p>	
                          </li>
                        </ul>
                      </li>
                    </ul>
                    <form class="navbar-search" action="search.php" method="GET" >
                    	 <input class="input-large search-query" id="appendedInputButton" placeholder="Search" name="s" size="16" type="text" required="required">
                         <button class="btn" type="submit" style="margin-top:-1px;">Go!</button>
                    </form>
                    
                     <p class="navbar-text pull-right">
			            <a href="#" class="btn btn-large btn-inverse disabled" >Welcome<?php echo " ".$userinfo->name." ".$userinfo->lastname." ";?></a>  
			            <a  href="logout.php?id=<?php echo $_key.":".$userId; ?>" class="btn btn-large  btn-danger ">logout</a>
			         </p>
			         
                  </div>
                </div>
              </div>
            </div>
<?php endif;?>