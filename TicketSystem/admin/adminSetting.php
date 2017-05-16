<?php
class adminSetting
{
	var $db;
	
	function __construct() 
	{
		$this->db = mysql_connect(DB_HOST,DB_USER,DB_PASS);
		mysql_select_db(DB_NAME);
	}
	
	public function show_users($id)
	{
		$this->_ticketInfo = new ticketInfo();
		$user_name = $this->_ticketInfo->getuser($id);
		$user_name = json_decode($user_name);
		if(@$user_name->id != $id)
		{
			exit();
		}
		
		echo ' <article class="container" style="margin-top:-18px;">
							<section class="well well-small mePadd">
							<h2 class="meFont" style="padding-bottom:30px;"><span class="icon-plus meMarg"></span> Add new Administrator</h2>';
				    echo ' <div class="">
			            <form class="form-actions form-horizontal" action="setting.php" method="post" name="administrator" onsubmit="return IE2()">
			                <div class="control-group">
			                    <label class="control-label" for="inputEmail">Email</label>
			                    <div class="controls">
			                    <input class="input-block-level" type="email" id="inputEmail" placeholder="Email" required="required" name="email">
			                    </div>
			                </div>
			                
			                <div class="control-group">
			                    <label class="control-label" for="">Name</label>
			                    <div class="controls">
			                    <input class="input-block-level" type="text" id="" placeholder="First Name" required="required" name="name">
			                    </div>
			                    <div class="controls paddingTop">
			                    <input class="input-block-level" type="text" id="" placeholder="Last Name" required="required" name="lname">
			                    </div>
			                </div>
			                
			                <div class="control-group">
			                    <label class="control-label" for="inputPassword">Password</label>
			                    <div class="controls">
			                    <input class="input-block-level" type="password" id="inputPassword" placeholder="Password" name="password" required="required">
			                    </div>
			                </div>
			                
			                <div class="control-group">
			                	<label class="control-label" for="">Role</label>
			                    <div class="controls">
			                    <select class="input-block-level" name="moderatorRole">';
			                    $get_option = file_get_contents("moderatorRoles.txt");
								$exp_get_option = explode("\n", $get_option);
								foreach ($exp_get_option as $value)
								{
									echo '<option>'.$value.'</option>';
								}
			                  echo '  </select>
			                	</div>
			                </div>
			                
			                <div class="control-group pull-right">
			                    <div class="controls">
			                     <input type="hidden" name="id" value="'.$id.'" />
			                    <button type="submit"  name="add" class="btn">Add</button>
			                    </div>
			                </div>
			            </form>
			            </div>
			            </section><!--Add new Administrators Ends-->
			            <section class="well well-small mePadd" style="margin-top:-30px;">
								<h2 class="meFont" style="padding-bottom:30px;"><span class="icon-eye-open meMarg"></span> Administrators</h2>
	        					<div class="myTable2">
				                <div class="tableH">
				                <div class="resTable1">';
		
		if ($select = mysql_query("SELECT name,lastname,email,id,role,userKey FROM ticketusers WHERE role='admin'")) 
		{
			$userKey = $_COOKIE["login"];
			
			$j = 0;
			while ($result = mysql_fetch_array($select))
			{
				if($result["id"] != $id and $result["role"] == "admin" )
				{
					$adminIds[] = $result["id"];
					$j++;
				}
			}
			if ($j != 0)
			{
				foreach ($adminIds as $value)
				{
					$this->_ticketInfo = new ticketInfo();
					$user_name = $this->_ticketInfo->getuser($value);
					$user_name = json_decode($user_name);
					echo '<table class="tables" width="100%" style="border:none;">
				          <tbody>
				          <tr>
				           <th style="border-top:none;" scope="row" class="tableFont" align="left">First Name</th>
				           <td style="border-top:none;" class="tableFont2">'.$user_name->name.'</td>
				            </tr>
				          <tr>
				            <th scope="row" class="tableFont" align="left">Last Name</th>
				            <td class="tableFont2">'.$user_name->lastname.'</td>
				            </tr>
				            <tr>
				          <th scope="row" class="tableFont" align="left">Email</th>
				           <td class="tableFont2">'.$user_name->email.'</td>
				            </tr>
				               <tr>
				             <th scope="row" class="tableFont" align="left">Role</th>
				            <td class="tableFont2">'.$user_name->moderatorRole.'</td>
				             </tr>
				             <tr>
				             <th scope="row" class="tableFont" align="left"></th>
				             <td class="tableFont2"><a href="setting.php?method='.md5("fdelete").':'.$value.':'.urlencode($user_name->email).'"><button  title="Remove" class="btn btn-danger" type="submit"><span class="icon-remove icon-white"></span></button></a></td>
				              </tr>
				              </tbody>
				               </table>
			                   <hr style="border-color: #d3d3d3;"/>';
				}
				echo  '</div>
					        <div class="resTable2">
					        <table class="table table-striped table-condensed">
					        <tr class="tableFont">
					        <td>First Name</td>
					        <td>Last Name</td>
					        <td>Email</td>
					        <td>Role</td>
					        <td></td>
					        </tr>
					      ';
				foreach ($adminIds as $value)
				{
					$this->_ticketInfo = new ticketInfo();
					$user_name = $this->_ticketInfo->getuser($value);
					$user_name = json_decode($user_name);
					echo '<tr class="tableFont2">';
					echo '<td>'.$user_name->name.'</td>';
					echo '<td>'.$user_name->lastname.'</td>';
					echo '<td>'.$user_name->email.'</td>';
					echo '<td>'.$user_name->moderatorRole.'</td>';
					echo '<td><a href="setting.php?method='.md5("fdelete").':'.$value.':'.urlencode($user_name->email).'"><button  title="Remove" class="btn btn-danger" type="submit"><span class="icon-remove icon-white"></span></button></a></td>';
					echo '</tr>';
				}
				echo '</table>
                	</div>
					</div>
					</div>';
			}
			echo ' </section><!--Administrators Ends-->
        			</article>';
		}
		else 
		{
			echo '
				<article class="container" style="margin-top:-18px;">
					<section class="well well-small mePadd">
					<div class="alert alert-info">
					<center><b>ERROR!</b></center>
					</div>
					</section>
			    </article>';
		}
		if($j == 0)
		{
			echo '
						<article class="container" style="margin-top:-20px;">
							<section class="well well-small mePadd">
							<div class="alert alert-info">
							<center><b>NO ADMIN</b></center>
							</div>
							</section>
					    </article>';
		}
	}
	
	public function add_user($email,$name,$lastname,$password,$moderatorRole, $id)
	{
		$emailPassword = $password;
		$password = md5($password);
		$date = date('YmdHis');
		$select = mysql_query("SELECT email FROM ticketusers");
		$userExists = false;
		while ($result = mysql_fetch_array($select))
		{
			if($result["email"] == $email)
			{
				$userExists = true;
			}
		}
		if($userExists != true)
		{
			$insert = "INSERT INTO ticketusers (name,lastname,email,password,role,moderatorRole,isLogin,date) VALUES ('$name','$lastname','$email','$password','admin','$moderatorRole','0','$date')";
			if(mysql_query($insert))
			{
				$send_mail = mail($email,"Hi ".$name." ".$lastname." see your ticket username and password","Your username: ".$email." Your password: ".$emailPassword);
				if($send_mail)
				{
					echo '<article class="container" style="margin-top:-18px;">
						<section class="well well-small mePadd">
						<div class="alert alert-success">
		  						<center>New admin has been added</center>
						 </div>
						 </section>
			  			  </article>';
				}
				else 
				{
					echo '<article class="container" style="margin-top:-18px;">
							<section class="well well-small mePadd">
							<div class="alert alert-error">
		  						<center>New admin has been added to database but can not send email to user / username: '.$email.' password: '.$emailPassword.'</center>
						 	</div> 
						 	</section>
			  			  </article>';
				}
			}
			else
			{
				echo '<article class="container" style="margin-top:-18px;">
							<section class="well well-small mePadd">
							<div class="alert alert-error">
	  						<center>Can not save this user!</center>
						 </div>
						 </section>
				  	    </article>';
			}
		}
		else
		{
			echo '<article class="container" style="margin-top:-18px;">
							<section class="well well-small mePadd">
					<div class="alert alert-error">
		  						<center>This user exists!</center>
					</div>
					 </section>
				 </article>';
		}
	}
	
	public function delete_user($id,$userMail)
	{
		$select = mysql_query("SELECT id,email FROM ticketusers WHERE id='$id'");
		while ($result = mysql_fetch_array($select))
		{
			if($result["id"] == $id and md5($result["email"]) == $userMail)
			{
				$delete = "DELETE FROM ticketusers WHERE id='$id'";
				if(mysql_query($delete))
				{
					echo '<article class="container" style="margin-top:-18px;">
							<section class="well well-small mePadd">
						 <div class="alert alert-success">
	  						<center>user has been deleted</center>
						 </div>
						  </section>
						 </article>';
				}
				else 
				{
					echo '<article class="container" style="margin-top:-18px;">
							<section class="well well-small mePadd">
							<div class="alert alert-error">
			  						<center>Can not delete this user!</center>
							</div>
							 </section>
						 </article>';
				}
			}
		}
	}
	
	public function show_edit_profile($id)
	{
		if ($select = mysql_query("SELECT name,lastname,email,id,role,moderatorRole,userKey FROM ticketusers WHERE role='admin'")) 
		{
			$userKey = $_COOKIE["login"];
			$j = 0;
			while ($result = mysql_fetch_array($select))
			{
				if($result["id"] == $id and $result["role"] == "admin" and $result["userKey"] == $userKey)
				{
					echo '
					<article class="container" style="margin-top:-18px;">
					 <section class="well well-small mePadd">
      					  <h2><span class="icon-edit" style="margin-top:7px;"></span> Edit administrator profile</h2>
						 <div class="">
						 <form class="form-actions form-horizontal" action="setting.php" name="adminitratorProfile"  method="post" onsubmit="return IE4()">
		                <div class="control-group">
		                    <label class="control-label" for="inputEmail">Email</label>
		                    <div class="controls">
		                    <input class="input-block-level" type="email" id="inputEmail"  placeholder="'.$result["email"].'" value="'.$result["email"].'" name="email" required="required">
		                    </div>
		                </div>
                
		                <div class="control-group">
		                    <label class="control-label" for="inputEmail">Name</label>
		                    <div class="controls">
		                    <input class="input-block-level" type="text" id="" placeholder="'.$result["name"].'" value="'.$result["name"].'" name="name" required="required">
		                    </div>
		                    <div class="controls paddingTop">
		                    <input class="input-block-level" type="text" id="" placeholder="'.$result["lastname"].'" value="'.$result["lastname"].'" name="lname" required="required">
		                    </div>
		                </div>
		                
		                <div class="control-group">
		                    <label class="control-label" for="inputPassword">Password</label>
		                    <div class="controls">
		                    <input class="input-block-level" type="password" id="inputPassword" placeholder="Old Password" name="oldPassword" >
		                    </div>
		                    <div class="controls paddingTop">
		                    <input class="input-block-level" type="password" id="inputPassword" placeholder="New Password" name="newPassword">
		                    </div>
		              	  </div>
                
		              	  <div class="control-group">
		                	<label class="control-label" for="inputPassword">Role</label>
		                    <div class="controls">
		                    <select class="input-block-level" name="moderatorRole">';
		                       $get_option = file_get_contents("moderatorRoles.txt");
								$exp_get_option = explode("\n", $get_option);
								foreach ($exp_get_option as $value)
								{
									if(trim($value) == $result["moderatorRole"])
									{
										echo '<option selected="selected">'.$result["moderatorRole"].'</option>';
									}
									else 
									{
										echo '<option>'.trim($value).'</option>';
									}
								}
		                   echo ' </select>
		                		</div>
		                		</div>
                
	                <div class="control-group pull-right">
	                    <div class="controls">
	                    <input type="hidden" name="id" value="'.$id.'" />
	                    <button type="submit" name="editUser" class="btn">Save</button>
                    </div>
			                </div>
			            </form>
			            </div>
			        </section><!--Edit administrator profile Ends-->
			        </article>';
				}
			}
		}
	}
	
	public function edit_profile($email,$name,$lastname,$newPassword,$oldPassword,$moderatorRole,$id,$changePass)
	{
		if ($changePass == false)
		{
			if ($select = mysql_query("SELECT id,role,userKey FROM ticketusers WHERE role='admin'")) 
			{
				$userKey = $_COOKIE["login"];
				$j = 0;
				while ($result = mysql_fetch_array($select))
				{
					if($result["id"] == $id and $result["role"] == "admin" and $result["userKey"] == $userKey)
					{
						$update = "UPDATE ticketusers SET name='$name',email='$email',lastname='$lastname',moderatorRole='$moderatorRole' WHERE id='$id' AND role='admin'";
						if(mysql_query($update))
						{
							echo '<article class="container" style="margin-top:-18px;">
									 <section class="well well-small mePadd">
									<div class="alert alert-success">
	  								<center>Your profile has been edited.</center>
								 </div></section></article>';
						}
						else
						{
							echo '<article class="container" style="margin-top:-18px;">
									 <section class="well well-small mePadd">
									<div class="alert alert-error">
	  								<center>ERROR! can not edit your profile.</center>
								 </div></section></article>';
						}
					}
				}
			}
		}
		else 
		{
			if ($select = mysql_query("SELECT password,id,role,userKey FROM ticketusers WHERE role='admin'")) 
			{
				$userKey = $_COOKIE["login"];
				$j = 0;
				$oldPassword = md5($oldPassword);
				$newPassword = md5($newPassword);
				while ($result = mysql_fetch_array($select))
				{
					if($result["id"] == $id and $result["role"] == "admin" and $result["userKey"] == $userKey)
					{
						if($result["password"] == $oldPassword)
						{
							$update = "UPDATE ticketusers SET name='$name',email='$email',lastname='$lastname',moderatorRole='$moderatorRole',password='$newPassword' WHERE id='$id' AND role='admin'";
							if(mysql_query($update))
							{
								echo '
									<article class="container" style="margin-top:-18px;">
										 <section class="well well-small mePadd">
										<div class="alert alert-success">
		  								<center>Your profile has been edited.</center>
									 </div>
									 </section></article>';
							}
							else
							{
								echo '<article class="container" style="margin-top:-18px;">
										 <section class="well well-small mePadd">
										<div class="alert alert-error">
		  								<center>ERROR! can not edit your profile.</center>
									 </div>
									 </section></article>';
							}
						}
						else 
						{
							echo '<article class="container" style="margin-top:-18px;">
										 <section class="well well-small mePadd">
										<div class="alert alert-error">
		  								<center>Invalide password.</center>
									 </div>
									 </section></article>';
						}
					}
				}
			}
		}
	}
	
	public function show_moderators()
	{
		$moderators = file_get_contents("moderatorRoles.txt");
		$moderators = explode("\n", $moderators);
		echo '
		 <article class="container" style="margin-top:-18px;">
	         <section class="well well-small mePadd">
      		  <h2 class="meFont"><span class="icon-edit meMarg"></span> Edit your user roles</h2>
      		  <div class="">
      		  <hr/>
			<form action="setting.php" method="POST"  class="" style="padding-left: 20px;">
	 			<div class="input-append">
                <input class="span2" id="appendedInputButton" size="16" type="text" name="addUser" required="required"><button class="btn" type="submit" name="addModerator">Add</button>
                </div>	 
			</form>
		</div>';
		echo '<div class="">
                <div class="tableH">
                    <table width="100%" style="border:none;">
					  <tr class="tableFont">
                        <td class="paddingLR paddingTop"><h2 class="meFont" style="color:#333; padding-left:0px;">Roles</h2></td>
                      </tr>
						';
		
		for($j = 0; $j < count($moderators); $j++)
		{
			echo '<tr class="tableFont2">
					<td style="background-color:#eeeeee;">
						<form action="setting.php" method="POST"  style="padding-left: 20px; padding-top: 20px;">';
			echo ' 		<div class="input-append">
						<input type="hidden" name="oldName" value="'.trim($moderators[$j]).'">
 			 			<input class="span2" id="appendedInputButton" size="16" name="edited" type="text" value="'.trim($moderators[$j]).'" required="required"><button class="btn" type="submit" name="editModerator">Edit!</button>
						</div>
						</form>
					</td>
                 ';
			if($j != 0)
			{
				echo '		<td style="background-color:#eeeeee;">
								<form action="setting.php" method="POST" class="formRes">
								<input type="hidden" name="name" value="'.trim($moderators[$j]).'">
								<button title="Remove" class="btn btn-danger btnRes" type="submit" name="deleteSelected">Remove</button>
								</form>
							 </td>
						';
			}
			else 
			{
				echo '<td style="background-color:#eeeeee;"></td>';
			}
		}
		echo '</tr></table> </div></div>
			</section></article>';
		
	}
	
	public function edit_role_in_dataBase($oldName,$newName) 
	{
		mysql_query("UPDATE ticketusers SET moderatorRole='$newName' WHERE moderatorRole='$oldName'");
		mysql_query("UPDATE tickets SET department='$newName' WHERE department='$oldName'");
		mysql_query("UPDATE ticketanswer SET moderator='$newName' WHERE moderator='$oldName'");
	}
	
}
$adminSetting = new adminSetting();
?>