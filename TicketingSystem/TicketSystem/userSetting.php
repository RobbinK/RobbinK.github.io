<?php
class userSetting
{
	var $db;
	
	function __construct() 
	{
		$this->db = mysql_connect(DB_HOST,DB_USER,DB_PASS);
		mysql_select_db(DB_NAME);
	}
	
	public function show_edit_user($id)
	{
		$select = mysql_query("SELECT id,name,lastname,email,userKey FROM ticketusers WHERE id='$id'");
		$userKey = $_COOKIE["login"];
		while ($result = mysql_fetch_array($select)) 
		{
			if($result["id"] == $id and $result["userKey"] == $userKey)
			{
				echo '
					<article class="container" style="margin-top:-18px;">
					  <section class="well well-small mePadd">
				        <h2 class="meFont" style="padding-bottom:30px;"><span class="icon-comment meMarg"></span> Edit profile</h2>
				            <div class="">
				            <form class="form-actions form-horizontal" action="setting.php" method="post" name="userProfile" onsubmit="return IE3()">
				                <div class="control-group">
				                    <label class="control-label" for="">Email</label>
				                    <div class="controls">
				                    <input class="input-block-level" type="email" id="inputEmail" placeholder="'.$result["email"].'" required="required" value="'.$result["email"].'" name="email">
				                    </div>
				                </div>
				                
				                <div class="control-group">
				                    <label class="control-label" for="">Name</label>
				                    <div class="controls">
				                    <input class="input-block-level" type="text" id="" placeholder="'.$result["name"].'" value="'.$result["name"].'" name="name" required="required">
				                    </div>
				                    <div class="controls paddingTop">
				                    <input class="input-block-level" type="text" id="" placeholder="'.$result["lastname"].'" value="'.$result["lastname"].'" name="lname" required="required">
				                    </div>
				                </div>
				                
				                <div class="control-group">
				                    <label class="control-label" for="">Password</label>
				                    <div class="controls">
				                    <input class="input-block-level" type="password" id="inputPassword" placeholder="Old Password" name="oldPassword">
				                    </div>
				                    <div class="controls paddingTop">
				                    <input class="input-block-level" type="password" id="inputPassword" placeholder="New Password" name="newPassword">
				                    </div>
				                </div>
				                
				                <div class="control-group pull-right">
				                    <div class="controls">
				                    <input type="hidden" name="id" value="'.$id.'" />
				                    <button type="submit" class="btn" name="editUser">Save</button>
				                    </div>
				                </div>
				            </form>
				            </div>
				        </section><!--Edit user profile Ends-->
				        </article>
						';
			}
		}
	}
	
	public function edit_profile($id ,$name , $lastname, $email, $oldPassword, $newPassword, $changePass)
	{
		$userKey = $_COOKIE["login"];
		if($changePass == true)
		{
			$oldPassword = md5($oldPassword);
			$newPassword = md5($newPassword);
			
			$select = mysql_query("SELECT id,name,lastname,email,userKey,password FROM ticketusers WHERE id='$id'");
			while ($result = mysql_fetch_array($select))
			{
				if($result["id"] == $id and $result["userKey"] == $userKey and $result["password"] == $oldPassword)
				{
					if(mysql_query("UPDATE ticketusers SET name='$name',lastname='$lastname',email='$email',password='$newPassword' WHERE id='$id' and userKey='$userKey'"))
					{
						$while = true;
						return true;
					}
					else 
					{
						return false;
					}
				}
				else 
				{
					$while = false;
				}
			}
			if(@$while == false)
			{
				return false;
			}
		}
		else
		{
			$select = mysql_query("SELECT id,name,lastname,email,userKey FROM ticketusers WHERE id='$id'");
			while ($result = mysql_fetch_array($select))
			{
				if($result["id"] == $id and $result["userKey"] == $userKey)
				{
					if(mysql_query("UPDATE ticketusers SET name='$name',lastname='$lastname',email='$email' WHERE id='$id' and userKey='$userKey'"))
					{
						$while = true;
						return true;
					}
					else 
					{
						return false;
					}
				}
				else 
				{
					$while = false;
				}
			}
			if(@$while == false)
			{
				return false;
			}
		}
		
	}
}
$userSetting = new userSetting();
?>