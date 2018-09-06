<?php
include('../functions.php');
if(!$user || $user['id']<1){
header('Location: '.$settings['siteurl'].'/login');
die();
}else{
include('check.php');
}

$type=$_GET['type'];
$userid=intval($_GET['id']);
$errors=false;
$msg=false;

if($type==='delete'){
	
	//delete user
	$sql = "DELETE FROM users WHERE id = $userid";
	$db->exec($sql);
	
	//delete user friends/followers
	$sql = "DELETE FROM users_friends WHERE following_uid = $userid";
	$db->exec($sql);
	
	$sql = "DELETE FROM users_friends WHERE follower_uid = $userid";
	$db->exec($sql);
	
	//delete user scores
	$sql = "DELETE FROM users_scores WHERE uid = $userid";
	$db->exec($sql);
	
	//delete awards
	$sql = "DELETE FROM users_achievemet WHERE uid = $userid";
	$db->exec($sql);
	
	//delete tokens
	$sql = "DELETE FROM users_auth_tokens WHERE uid = $userid";
	$db->exec($sql);
	
	//redirect to users
	header('Location: '.$settings['siteurl'].'/'.$settings['adminfolder'].'/users.php');	
	die();
}else{
//selete user info	
	
//if POST
if($_POST){
	$name=$_POST['username'];
	$email=$_POST['email'];
	$xp=intval($_POST['xp']);
	$avatar=$_POST['avatar'];
	
//update
try{
	$stmt = $db->prepare("UPDATE users SET username=:name,avatar=:avatar,xp=:xp,user_email=:email WHERE id =:uid");
	$stmt->execute(array(':name' => $name,':avatar'=>$avatar,':xp'=>$xp,':email'=>$email,':uid'=>$userid));
} catch(PDOException $ex) {
error_log($ex->getMessage());
	$errors="$ex->getMessage()";
}

$msg="Edits have been saved";
}

$stmt = $db->prepare("SELECT u.id,u.avatar,u.username,u.regdate,u.verified,u.xp,u.user_email FROM users u WHERE u.id=:id LIMIT 1");
$stmt->bindValue(':id', $userid, PDO::PARAM_INT);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
$profile=$results[0];
$smarty->assign('profile',$profile,true);
}

$smarty->assign('page','users',true);
$smarty->assign('errors',$errors,true);
$smarty->assign('msg',$msg,true);
$smarty->display('users_edit.tpl');
?>