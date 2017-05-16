<?php
include 'admin/userLogin.php';
include 'userSetting.php';
include 'header.php';
include 'menu.php';
if(@$isLogin and $userinfo->role == "user"):
?>

<?php 
	if(isset($_POST["editUser"]))
	{
		$id = $_POST["id"];
		$name = $_POST["name"];
		$email = $_POST["email"];
		$lastname = $_POST["lname"];
		@$oldPassword = $_POST["oldPassword"];
		@$newPassword = $_POST["newPassword"];
		if(empty($oldPassword) or empty($newPassword))
		{
			$changePass = false;
		}
		else 
		{
			$changePass = true;
		}
		if($userSetting->edit_profile($id, $name, $lastname, $email, $oldPassword, $newPassword, $changePass))
		{
			echo '<article class="container" style="margin-top:-18px;">
					<section class="well well-small mePadd">
					  <div class="alert alert-success">
	  					<center>Profile has been edited</center>
					  </div>
					  </section>
					</article>';
			$userSetting->show_edit_user($userId);
		}
		else
		{
			echo '<article class="container" style="margin-top:-18px;">
					<section class="well well-small mePadd">
					<div class="alert alert-error">
  					<center>Profile has not been edited</center>
				    </div>
				    </section>
				</article>';
			$userSetting->show_edit_user($userId);
		}
	}
	else 
	{
		$userSetting->show_edit_user($userId);
	}
?>

<?php include 'footer.php'; endif;?>