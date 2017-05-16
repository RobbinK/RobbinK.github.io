<?php
include 'userLogin.php';
include 'header.php';
include 'menu.php';
include 'adminSetting.php';
if(@$isLogin and $userinfo->role == "admin"):
?>
<?php 
	if(isset($_GET["method"]))
	{
		$method = $_GET["method"];
		$exp = explode(":", $method);
		$method = $exp[0];
		$id = $exp[1];
		$add = md5("add");
		$editUser = md5("editU");
		$delete = md5("delete");
		$firstdelete = md5("fdelete");
		$moderators =  md5("moderators");
		switch ($method)
		{
			case $add:
				$adminSetting->show_users($id);
			break;
			
			case $editUser:
				$adminSetting->show_edit_profile($id);
			break;
			
			case $firstdelete:
				$userMail = $exp[2];
				echo '<article class="container" style="margin-top:-18px;">
						<section class="well well-small mePadd">
						<h2 class="meFont" style="padding-bottom:30px;"><span class="icon-eye-open meMarg"></span> Email: '.$userMail.'</h2>
						<div class="alert alert-error">
						<center><p><b>Do you want to delete this admin? </b> </p>
						<a href="setting.php?method='.md5("delete").':'.$id.':'.md5($userMail).'" class="btn btn-danger">Yes </a>
						 <a href="setting.php?method='.$add.":".$userId.'"class="btn" type="button">No</a></center>
					  </div>
					  </section>
					  </article>';
			break;
			
			case $delete:
				$userMail = $exp[2];
				$adminSetting->delete_user($id, $userMail);
				$adminSetting->show_users($userId);
			break;
			
			case $moderators:
				$adminSetting->show_moderators();
			break;	
			
			default:
				echo '<article class="container hero-unit" style="margin-top:-18px;">
						<section class="well well-small"><!--Users Strats-->
						<div class="alert alert-error">
  						<center>ERROR!</center>
				 	</div>
				 	</section>
				 	</article>';
			break;	
		}
	}
	
	if(isset($_POST["add"]))
	{
		$email = $_POST["email"];
		$name = $_POST["name"];
		$lastname = $_POST["lname"];
		$password = $_POST["password"];
		$moderatorRole = $_POST["moderatorRole"];
		$id = $_POST["id"];
		$adminSetting->add_user($email, $name, $lastname, $password, $moderatorRole, $id);
		$adminSetting->show_users($id);
	}
	
	if(isset($_POST["editUser"]))
	{
		$email = $_POST["email"];
		$name = $_POST["name"];
		$lastname = $_POST["lname"];
		@$newPassword = $_POST["newPassword"];
		@$oldPassword = $_POST["oldPassword"];
		if(empty($newPassword) or empty($oldPassword) or $oldPassword == "" or $newPassword == "")
		{
			$changePass = false;
		}
		else 
		{
			$changePass = true;
		}
		$moderatorRole = $_POST["moderatorRole"];
		$id = $_POST["id"];
		$adminSetting->edit_profile($email, $name, $lastname, $newPassword, $oldPassword, $moderatorRole, $id, $changePass);
		$adminSetting->show_edit_profile($id);
	}
	
	if(isset($_POST["addModerator"]))
	{
		$newModerator = $_POST["addUser"];
		if($newModerator != "user")
		{
			$moderators = file_get_contents("moderatorRoles.txt");
			$moderators .= "\n".$newModerator;
			file_put_contents("moderatorRoles.txt",$moderators);
			echo '<article class="container" style="margin-top:-18px;">
				<section class="well well-small mePadd">
				<div class="alert alert-success">
					<center>New role has been aded</center>
				</div>
				</section>
				</article>';
			$adminSetting->show_moderators();
		}
		else 
		{
			echo '<article class="container" style="margin-top:-18px;">
				<section class="well well-small mePadd">
				<div class="alert alert-error">
				<center>Can not add role by "user" name.</center>
				</div>
				</section>
				</article>';
			$adminSetting->show_moderators();
		}
	}
	
	if(isset($_POST["editModerator"]))
	{
		$edited = $_POST["edited"];
		$oldName = $_POST["oldName"];
		$moderators = file_get_contents("moderatorRoles.txt");
		$moderators = str_replace($oldName, $edited, $moderators);
		file_put_contents("moderatorRoles.txt",$moderators);
		$adminSetting->edit_role_in_dataBase($oldName,$edited);
		echo '<article class="container" style="margin-top:-18px;">
				<section class="well well-small mePadd">
				<div class="alert alert-success">
					<center>The role has been edited</center>
			</div>
			</section>
			</article>';
		$adminSetting->show_moderators();
	}
	
	if(isset($_POST["deleteSelected"]))
	{
		$name = $_POST["name"];
		$all = file_get_contents("moderatorRoles.txt");
		$all = explode("\n", $all);
		unlink("moderatorRoles.txt");
		$fp = fopen("moderatorRoles.txt", "w");
		fwrite($fp, $all[0]);
		for($j = 1; $j < count($all); $j++)
		{
			if(trim($all[$j]) != $name)
			{
				fwrite($fp, "\n".$all[$j]);
			}
		}
		fclose($fp);
		echo '<article class="container" style="margin-top:-18px;">
				<section class="well well-small mePadd">
				<div class="alert alert-success">
					<center>The role has been deleted</center>
			</div>
			</section>
			</article>';
		$adminSetting->show_moderators();
	}


?>
<?php include 'footer.php'; endif;?>