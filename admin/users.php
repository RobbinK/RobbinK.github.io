<?php
include 'userLogin.php';
include 'header.php';
include 'menu.php';
class users
{
	var $db;
	
	function __construct() 
	{
		$this->db = mysql_connect(DB_HOST,DB_USER,DB_PASS);
		mysql_select_db(DB_NAME);
	}
	
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
	public function show_users($users)
	{
		$count = count($users->id);
		$co_tot = 0;
		$co_show = 10;
		for($c = 0; $c < $count; $c++)
		{
			$co_tot++;
		}
		if(isset($_GET["page"]))
		{
			$showPage = $_GET["page"];
		}
		else
		{
			$showPage = 1;
		}
		$_page = self::_numpage($co_tot, $co_show);
		
		if($users->result == "true")
		{
			echo ' <div class="modal alert-danger hide fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"><!--Modal 2 Starts-->
        	<div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h3 id="myModalLabel">Warning!!!</h3>
            </div>
            <form action="users.php" method="POST" class="form-inline">
            <div class="modal-body">
            <span>Do you really want to delete selected user(s)?</span>
            </div>
            <div class="modal-footer">
            <button class="btn btn-danger" type="submit" name="delete"><span class="icon-remove icon-white"></span> Delete</button>
            <button class="btn" data-dismiss="modal" aria-hidden="true"><span class="icon-thumbs-down"></span> Cancel</button>
            </div>
            
        </div><!--Modal 3 Ends-->
        <div class="modal alert-block hide fade" id="myModal3" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"><!--Modal Starts-->
        	<div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h3 id="myModalLabel">Warning!!</h3>
            </div>
            <form action="users.php" method="POST" class="form-inline">
            <div class="modal-body">
            <span>Do you really want to block selected user(s)?</span>
            </div>
            <div class="modal-footer">
            <button class="btn btn-warning" type="submit" name="BLOCK"><span class="icon-ban-circle icon-white"></span> Block</button>
            <button class="btn" data-dismiss="modal" aria-hidden="true"> <span class="icon-thumbs-down"></span> Cancel</button>
            </div>
            
        </div><!--Modal 3 Ends-->
        <div class="modal alert-success hide fade" id="myModal4" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"><!--Modal 4 Starts-->
        	<div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h3 id="myModalLabel">Warning!</h3>
            </div>
            <form action="users.php" method="POST" class="form-inline">
            <div class="modal-body">
            <span>Do you really want to unblock selected user(s)?</span>
            </div>
            <div class="modal-footer">
            <button class="btn btn-success" type="submit" name="UNBLOCK"><span class="icon-ok-sign icon-white"></span> Unblock</button>
            <button class="btn" data-dismiss="modal" aria-hidden="true"><span class="icon-thumbs-down"></span> Cancel</button>
            </div>
            
        </div><!--Modal 4 Ends--><!--Administrator panel Ends-->';
			echo '<article class="container" style="margin-top:-18px;">
					 <section class="well well-small mePadd">
					 <h2 class="meFont"><span class="icon-user meMarg"></span> Users</h2>
        				<div class="myTable">
		               <div class="tableH resTable2">
		                    <table class="table table-striped table-condensed">
		                      <tr class="tableFont">
		                        <td>First Name</td>
		                        <td>Last Name</td>
		                        <td>Email</td>
		                        <td>Status</td>
		                        <td>Number of tickets</td>
		                        <td>Registration date</td>
		                        <td><input type="checkbox" onclick="checkAll(this)"></td>
		                      </tr>';
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
				if($users->isBlock[$i] == "1")
				{
					$status = '<div class="badge badge-warning" style="width:58px;">Blocked</div>';
				}
				else 
				{
					$status = '<div class="badge badge-success" style="width:58px;">Unlocked</div>';
				}
				$dateActivity = self::change_date_format($users->date[$i]);
				echo '<tr class="tableFont2">
                        <td>'.$users->name[$i].'</td>
                        <td>'.$users->lastname[$i].'</td>
                        <td>'.$users->email[$i].'</td>
                        <td><center>'.$status.'</center></td>
                        <td>'.$users->numTickets[$i].'</td>
                        <td>'.$dateActivity.'</td>
                        <td><input type="checkbox" name="id[]" value="'.$users->id[$i].'"></td>
                      </tr>';
			}
			echo '</table>
                </div><div class="resTable1">';
			
				for ($i=$start;$i<$end;$i++)
					{
						if($users->isBlock[$i] == "1")
						{
							$status = '<div class="badge badge-warning" style="width:58px;">Blocked</div>';
						}
						else 
						{
							$status = '<div class="badge badge-success" style="width:58px;">Unlocked</div>';
						}
						$dateActivity = self::change_date_format($users->date[$i]);
						echo '<table class="tables" width="100%" style="border:none;">
			                    <tbody>
			                    <tr>
			                    <th style="border-top:none;" scope="row" class="tableFont" align="left">First Name</th>
			                    <td style="border-top:none;" class="tableFont2">'.$users->name[$i].'</td>
			                    </tr>
			                    <tr>
			                    <th scope="row" class="tableFont" align="left">Last Name</th>
			                    <td class="tableFont2">'.$users->lastname[$i].'</td>
			                    </tr>
			                    <tr>
			                    <th scope="row" class="tableFont" align="left">Email</th>
			                    <td class="tableFont2">'.$users->email[$i].'</td>
			                    </tr>
			                    <tr>
			                    <th scope="row" class="tableFont" align="left">Status</th>
			                    <td class="tableFont2">'.$status.'</td>
			                    </tr>
			                    <tr>
			                    <th scope="row" class="tableFont" align="left">Number of tickets</th>
			                    <td class="tableFont2">'.$users->numTickets[$i].'</td>
			                    </tr>
			                    <tr>
			                    <th scope="row" class="tableFont" align="left">Last Activity</th>
			                    <td class="tableFont2">'.$dateActivity.'</td>
			                    </tr>
			                    <tr>
			                    <tr>
			                    <th scope="row" class="tableFont" align="left"><input type="checkbox" onclick="checkAll(this)"></th>
			                    <td class="tableFont2"><input type="checkbox" name="id[]" value="'.$users->id[$i].'"></td>
			                    </tr>
			                    <tr>
			                    </tbody>
			                  </table>';
					}
                
            echo '  </div><div class="pull-right paddingRL">
                    <button class="btn btn-danger" type="button" data-toggle="modal" data-target="#myModal2">Delete</button>
                    <button class="btn btn-warning" type="button" data-toggle="modal" data-target="#myModal3">Block</button>
                    <button class="btn btn-success" type="button" data-toggle="modal" data-target="#myModal4">Unblock</button>
                </div>
			</div>';
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
						echo '<li><a href="users.php?page='.$i.'">'.$i.'</a></li>';
					}
				}
				echo '</ul></div>';
			}
			echo '  </section></article>';
			
		}
		else
		{
			echo '<article class="container" style="margin-top:-18px;">
					<section class="well well-small mePadd">
					<div class="alert alert-error">
						<center>NO USER!</center>
					</div>
				</section></article>';
		}
	}
	
	public function delete_users($id)
	{
		foreach ($id as $value)
		{
			$delete = "DELETE FROM ticketusers WHERE id='$value'";
			if(!mysql_query($delete))
			{
				return false;
			}
		}
		return true;
	}
	
	public function block_users($id)
	{
		foreach ($id as $value)
		{
			$block = "UPDATE ticketusers SET isBlock='1' WHERE id='$value'";
			if(!mysql_query($block))
			{
				return false;
			}
		}
		return true;
	}
	
	public function unblock_users($id)
	{
		foreach ($id as $value)
		{
			$block = "UPDATE ticketusers SET isBlock='0' WHERE id='$value'";
			if(!mysql_query($block))
			{
				return false;
			}
		}
		return true;
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
if(@$isLogin and $userinfo->role == "admin"):
$users = $_ticketInfo->getUsers();
$users = json_decode($users);
$show_users = new users();
?>
<?php 
	
	if(isset($_POST["id"]) and is_array($_POST["id"]) and isset($_POST["delete"]))
	{
		$id = $_POST["id"];
		if($show_users->delete_users($id))
		{
			echo '<article class="container" style="margin-top:-18px;">
					<section class="well well-small mePadd">
					<div class="alert alert-success">
					<center>selected user(s) has been deleted</center>
				</div></section></article>
			';
			$users = $_ticketInfo->getUsers();
			$users = json_decode($users);
			$show_users->show_users($users);
		}
		else 
		{
			echo '<article class="container" style="margin-top:-18px;">
					<section class="well well-small mePadd">
					<div class="alert alert-error">
				<center>Can not delete selected user(s)</center>
				</div></section></article>
			';
			$users = $_ticketInfo->getUsers();
			$users = json_decode($users);
			$show_users->show_users($users);
		}
	}
	elseif(isset($_POST["BLOCK"]) and isset($_POST["id"]) and is_array($_POST["id"]))
	{
		$id = $_POST["id"];
		if($show_users->block_users($id))
		{
			echo '<article class="container" style="margin-top:-18px;">
					<section class="well well-small mePadd">
					<div class="alert alert-success">
						<center>selected user(s) has been block</center>
					</div>
					</section></article>
				';
			$users = $_ticketInfo->getUsers();
			$users = json_decode($users);
			$show_users->show_users($users);
		}
		else 
		{
			echo '<article class="container" style="margin-top:-18px;">
					<section class="well well-small mePadd">
					<div class="alert alert-error">
				<center>Can not block selected user(s)</center>
				</div></section></article>
			';
			$users = $_ticketInfo->getUsers();
			$users = json_decode($users);
			$show_users->show_users($users);
		}
	}
	
	elseif(isset($_POST["UNBLOCK"]) and isset($_POST["id"]) and is_array($_POST["id"]))
	{
		$id = $_POST["id"];
		if($show_users->unblock_users($id))
		{
			echo '<article class="container" style="margin-top:-18px;">
					<section class="well well-small mePadd">
					<div class="alert alert-success">
						<center>selected user(s) has been UNblock</center>
					</div>
					</section></article>
				';
			$users = $_ticketInfo->getUsers();
			$users = json_decode($users);
			$show_users->show_users($users);
		}
		else 
		{
			echo '<article class="container" style="margin-top:-18px;">
					<section class="well well-small mePadd">
					<div class="alert alert-error">
				<center>Can not UNblock selected user(s)</center>
				</div>
				</section></article>
			';
			$users = $_ticketInfo->getUsers();
			$users = json_decode($users);
			$show_users->show_users($users);
		}
	}
	else 
	{
		$show_users->show_users($users);
	}
		
?>
<?php include 'footer.php'; endif;?>