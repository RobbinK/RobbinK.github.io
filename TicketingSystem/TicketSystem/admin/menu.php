<?php
	$_key = $_COOKIE['login']; 
	$userinfo = $Login->getUserInfo($_key);
	$userinfo = json_decode($userinfo); 
	if(@$isLogin and $userinfo->role == "admin"): 
		include 'ticketInfo.php';
		if($userinfo->result == "true")
		{
			$userId = $userinfo->id;
		}
		else 
		{
			$userId = "0";
		}
		
		$ticketInfo = $_ticketInfo->getTickets();
		$ticketInfo = json_decode($ticketInfo);
		
		$ticketAnswerInfo = $_ticketInfo->getAnswerTickets();
		$ticketAnswerInfo = json_decode($ticketAnswerInfo);
		
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
<script type="text/javascript" src="../js/bootstrap.js"></script>
  <script type="text/javascript" src="../js/chart.js"></script><!-- *** Chart JS *** -->
<script type="text/javascript">
function checkAll(bx) 
{
	var cbs = document.getElementsByTagName('input');
	for(var i=0; i < cbs.length; i++) 
	{
	  if(cbs[i].type == 'checkbox') 
	  {
	    cbs[i].checked = bx.checked;
	  }
	}
}
</script>
    <div class="navbar navbar-inverse" style="position: static;"><!--Header Starts-->
      <div class="navbar-inner">
        <div class="container">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
           <a href="." class="brand marginTop"><span class="icon-home icon-white"></span></a>
          <div class="nav-collapse collapse">
            <ul class="nav">
            <li class="dropdown">
                <a href="#" class="dropdown-toggle brand" data-toggle="dropdown">Tickets <b class="caret"></b></a>
                <ul class="dropdown-menu">
                 <li class="divider"></li>
                  <li><a href="tickets.php?method=<?php echo md5("all");?>">All tickets: <?php echo $startSpan.$allTickets.$endSpan;?></a></li>
                  <li><a href="tickets.php?method=<?php echo md5("closed");?>">Closed tickets: <?php echo $startSpan.$closeTickets.$endSpan;?></a></li>
                  <li><a href="tickets.php?method=<?php echo md5("open");?>">Open tickets: <?php echo $startSpan.$openTickets.$endSpan;?></a></li>
                   <li><a href="tickets.php?method=<?php echo md5("answered");?>">Answered tickets: <?php echo $startSpan.$answeredTickets.$endSpan;?></a></li>
                    <li><a href="tickets.php?method=<?php echo md5("progress");?>">In progress tickets: <?php echo $startSpan.$inProgresTickets.$endSpan;?></a></li>
					<li class="divider"></li>               
                </ul>
              </li>
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Admin setting <b class="caret"></b></a>
                <ul class="dropdown-menu">
                  <li class="divider"></li>
                  <li><a href="setting.php?method=<?php echo md5("add").":".$userId;?>">Admins</a></li>
                  <li><a href="setting.php?method=<?php echo md5("editU").":".$userId;?>">Edit profile</a></li>
                  <li><a href="setting.php?method=<?php echo md5("moderators").":".$userId;?>">Roles</a></li>
               <li class="divider"></li>
                </ul>
              </li>
               <li><a href="users.php">Users</a></li>
                <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Summary <b class="caret"></b></a>
                <ul class="dropdown-menu">
                  <li class="divider"></li>
                  <li><a href="summary.php?method=<?php echo md5("#lA");?>">Seven days ago</a></li>
                  <li><a href="summary.php?method=<?php echo md5("#lB");?>">One month ago</a></li>
                  <li><a href="summary.php?method=<?php echo md5("#lC");?>">Customization</a></li>
               <li class="divider"></li>
                </ul>
              </li>
            </ul>
              <form class="navbar-search" action="search.php" method="GET">
                    <input class="input-large search-query" id="appendedInputButton" placeholder="Search" size="16" name="s"  type="text" required="required">
                    <button class="btn" type="submit" style="margin-top:-1px;">Go!</button>
               </form>
            <p class="navbar-text pull-right">
            <a href="#" class="btn btn-large btn-inverse disabled" >Welcome<?php echo " ".$userinfo->name." ".$userinfo->lastname." ";?></a>  
            <a  href="logout.php?id=<?php echo $_key.":".$userId; ?>" class="btn btn-large  btn-danger ">logout</a>
            </p>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>
<?php endif; ?>