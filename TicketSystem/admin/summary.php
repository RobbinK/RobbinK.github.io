<?php 
include 'userLogin.php';
include 'header.php';
include 'menu.php';
class summary
{
	var $db;
	
	function __construct() 
	{
		$this->db = mysql_connect(DB_HOST,DB_USER,DB_PASS);
		mysql_select_db(DB_NAME);
	}
	
	public function select_summary($from_date,$to_date)
	{
		$query = mysql_query("SELECT status FROM tickets WHERE dateAded BETWEEN '" . $from_date . "' AND  '" . $to_date . "' ORDER by dateAded DESC");
		$allTickets = 0;
		$closeTickets = 0;
		$openTickets = 0;
		$answeredTickets = 0;
		$inProgresTickets = 0;
		while ($result = mysql_fetch_array($query))
		{
			$allTickets++;
			if($result["status"] == "closed")
			{
				$closeTickets++;
			}
			if ($result["status"] == "open")
			{
				$openTickets++;
			}
			if($result["status"] == "in progress")
			{
				$inProgresTickets++;
			}
			if($result["status"] == "answered")
			{
				$answeredTickets++;
			}
		}
		
		$array = array
		(
			"all" => $allTickets,
			"open" => $openTickets,
			"closed" => $closeTickets,
			"inProgress" => $inProgresTickets,
			"answered" => $answeredTickets
		);
		
		$json = json_encode($array);
		$json = json_decode($json);
		
		return $json;
	}
	
	public function DateSelector($inName, $useDate=0) 
    { 
        $monthName = array
        (
        1=> "January", "February", "March", 
            "April", "May", "June", "July", "August", 
            "September", "October", "November", "December"
        ); 
 
        if($useDate == 0) 
        { 
            $useDate = TIME(); 
        } 
 
        echo "<select name=" . $inName . "Month>\n"; 
        for($currentMonth = 1; $currentMonth <= 12; $currentMonth++) 
        { 
            echo "<option value=\""; 
            echo INTVAL($currentMonth); 
            echo "\""; 
            if(INTVAL(DATE( "m", $useDate))==$currentMonth) 
            { 
                echo " selected"; 
            } 
            echo ">" . $monthName[$currentMonth] . "\n"; 
        } 
        echo "</select>"; 
 
        echo "<select name=" . $inName . "Day>\n"; 
        for($currentDay=1; $currentDay <= 31; $currentDay++) 
        { 
            echo "<option value=\"$currentDay\""; 
            if(INTVAL(DATE( "d", $useDate))==$currentDay) 
            { 
                echo " selected"; 
            } 
            echo ">$currentDay\n"; 
        } 
        echo "</select>"; 
 
        echo "<select name=" . $inName . "Year>\n"; 
        $startYear = DATE( "Y", $useDate); 
        for($currentYear = $startYear - 5; $currentYear <= $startYear+5;$currentYear++) 
        { 
            echo "<option value=\"$currentYear\""; 
            if(DATE( "Y", $useDate)==$currentYear) 
            { 
                echo " selected"; 
            } 
            echo ">$currentYear\n"; 
        } 
        echo "</select>"; 
 
    }

	
}
if(@$isLogin and $userinfo->role == "admin"):
$summary = new summary();
if(isset($_GET["method"]))
{
	$method = $_GET["method"];
	switch ($_GET["method"]) 
	{
		case md5("#lA"):
			$date = date("Y-m-d"); 
      		$sevendays = date ( "Y-m-d", strtotime ( '-7 day' . $date ) );
      		$json = $summary->select_summary($sevendays, $date);
			$desc = "Seven days ago";
		break;
		
		case md5("#lB"):
			$date = date("Y-m-d"); 
      		$onemonth = date ( "Y-m-d", strtotime ( '-1 month' . $date ) );
      		$json = $summary->select_summary($onemonth, $date);
			$desc = "One month ago";
		break;
		
		case md5("#lC"):
			$desc = "Customization";
		break;
		
		default:
			$json = "";
			$desc = "ERROR!";
		break;
	}
}
?>
 
        <?php 
        if(!isset($_POST["summary"]) and $method != md5("#lC"))
        {
        	echo '<article class="container" style="margin-top:-18px;">
					 <section class="well well-small mePadd">
       				  <h2 class="meFont"><span class="icon-list-alt meMarg"></span> Summary: '.$desc.'</h2>';
        				
        	echo '<div class="myTable2"><div class="row res">
                        <div class="span4 paddings paddingBs">
                        	<table id="chartData" style="border:none;">
                                <tr style="color: #3a87ad">
                                  <td>Open</td><td>'.$json->open.'</td>
                                </tr>
                            
                                <tr style="color: #cf453f">
                                  <td>Close</td><td>'.$json->closed.'</td>
                                </tr>
                            
                                <tr style="color: #333333">
                                  <td>Pending</td><td>'.$json->inProgress.'</td>
                                </tr>
                            
                                <tr style="color: #468847">
                                  <td>Replied</td><td>'.$json->answered.'</td>
                                </tr>
                            </table>
                        </div>
                      	<div class="span4" style="padding-top: 60px;">
                           <canvas id="chart" width="380" height="300"></canvas>
                        </div>
                   </div></div> </section><!--Summary Ends-->
        	 
    			</article>';
        }
        
        if(!isset($_POST["summary"]) and $method == md5("#lC"))
        {
        	echo '<article class="container" style="margin-top:-18px;">
					 <section class="well well-small mePadd">
       				  <h2 class="meFont"><span class="icon-list-alt meMarg"></span> Summary: '.$desc.'</h2>
       				 <table width="100%" border="0" cellspacing="0" cellpadding="0">
       				 <form action="summary.php" method="POST">
       				  <tr>
                        <td class="paddingLRB">
                        	<label>From This Date:</label> ';
        					$summary->DateSelector("from_");
        	echo '
       				   </td>
                      </tr>
                       <tr>
                        <td class="paddingLRB">
                        	<label>To This Date:</label>';
                        	$summary->DateSelector("to_");
            echo '       </td>
                      </tr>
                      <tr>
                      	<td class="paddingLRB">
                          <button class="btn btn-info" type="submit" name="summary">View summary</button>
                        </td>
                      </tr>';
        	echo '</form></table></section><!--Summary Ends-->
	        </article>';
        }
?>

<?php 
if(isset($_POST["summary"]))
{
	$from_Month = $_POST["from_Month"];
	$from_Day = $_POST["from_Day"];
	$from_Year = $_POST["from_Year"];
	$from_date = $from_Year."-".$from_Month."-".$from_Day;
	
	$to_Month = $_POST["to_Month"];
	$to_Day = $_POST["to_Day"];
	$to_Year = $_POST["to_Year"];
	$to_date = $to_Year."-".$to_Month."-".$to_Day;
	
	$json = $summary->select_summary($from_date, $to_date);
	echo '<article class="container" style="margin-top:-18px;">
					 <section class="well well-small mePadd">
       				  <h2 class="meFont"><span class="icon-list-alt meMarg"></span> Summary: Customization</h2>';
	echo '<div class="myTable2">
	       <div class="row res">
                        <div class="span4 paddings paddingBs">
                        	<table id="chartData" style="border:none;">
                                <tr style="color: #3a87ad">
                                  <td>Open</td><td>'.$json->open.'</td>
                                </tr>
                            
                                <tr style="color: #cf453f">
                                  <td>Close</td><td>'.$json->closed.'</td>
                                </tr>
                            
                                <tr style="color: #333333">
                                  <td>Pending</td><td>'.$json->inProgress.'</td>
                                </tr>
                            
                                <tr style="color: #468847">
                                  <td>Replied</td><td>'.$json->answered.'</td>
                                </tr>
                            </table>
                        </div>
                      	<div class="span4" style="padding-top: 60px;">
                           <canvas id="chart" width="380" height="300"></canvas>
                        </div>
                      </div> </div>
            </table>
	        ';
	echo ' <table width="100%" border="0" cellspacing="0" cellpadding="0">
       				 <form action="summary.php" method="POST">
       				  <tr>
                        <td class="paddingLRB">
                        	<label>From This Date:</label> ';
        					$summary->DateSelector("from_");
   echo '
       				   </td>
                      </tr>
                       <tr>
                        <td class="paddingLRB">
                        	<label>To This Date:</label>';
                        	$summary->DateSelector("to_");
    echo '       </td>
                      </tr>
                      <tr>
                      	<td class="paddingLRB">
                          <button class="btn btn-info" type="submit" name="summary">View summary</button>
                        </td>
                      </tr>';
     echo '</form></table></section><!--Summary Ends-->
	        </article>';
}
?>
<?php include 'footer.php'; endif;?>