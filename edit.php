<?php
include('functions.php');
$smarty->caching = 0;
$page=$_GET['page'];
$title="Error";
$uid=intval($user['id']);
$error=false;
$success=false;
if($uid==0||!$uid){
header('Location: '.$settings['siteurl']);
die();
}
function generateRandomString($length = 10) {
$characters = '0123456789abcdefghijklmnopqrstuvwxyz';
$charactersLength = strlen($characters);
$randomString = '';
for ($i = 0; $i < $length; $i++) {
$randomString .= $characters[rand(0, $charactersLength - 1)];
}
return $randomString;
}

if($page=='profile'){
$title="Edit Profile";
if($_POST){
$continue=true;
$ext;
$name=preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', $_POST['name']);
$thumb=null;
$newavatar=true;

if(!$name){
$error='Please enter your name';
$continue=false;
}
if(strlen($name) < 2 OR strlen($name) > 30){
$error='Nickname must be between 2-30 characters.';
$continue=false;
}

if($continue){
//avatar here
require_once('libs/bulletproof.php');
$bulletProof = new ImageUploader\BulletProof;
	
if($_FILES && $_FILES['avaimage']['name']){
$newavatar=true;
$name3=strtolower($name);
$name3=str_replace(' ', '-',$name3);
$thumbnail=$name3.'-'.generateRandomString();
$pic1=$bulletProof->uploadDir("images/avatars")->shrink(array("height"=>100, "width"=>100))->upload($_FILES['avaimage'],$thumbnail);
if($pic1){
$thumb=$pic1;	
}
}else{
	
$newavatar=false;
$thumb=$user['avatar'];
}
}

if($continue){
$stmt = $db->prepare("UPDATE users SET username=:name,avatar=:thumb WHERE id =:uid");
$stmt->execute(array(':name' => $name,':thumb'=>$thumb,':uid'=>$uid));
$user['username']=$name;
$user['avatar']=$thumb;
$smarty->assign("user", $user, true);
$success="Your profile has been updated!";
}
}
}else if($page=='email'){
$title="Change email";	
if($_POST){
$continue=true;
$email=$_POST['email'];
if ( !preg_match('/^[_A-z0-9-]+((\.|\+)[_A-z0-9-]+)*@[A-z0-9-]+(\.[A-z0-9-]+)*(\.[A-z]{2,4})$/', $email) ){
$error='Please enter a valid email';
$continue=false;
} elseif (!$email){
$error='Please enter a valid email';
$continue=false;
}
$changedemail=0;
if($email != $user['user_email'] && $continue){
	$stmt = $db->prepare("SELECT id FROM users WHERE user_email = :email LIMIT 1");
	$stmt->bindValue(':email', $email, PDO::PARAM_STR);
	$stmt->execute();
	$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
	$email_check=$result[0];
if ($email_check['id']>0){
$error='Email is already registered. Try another email.';
$continue = 0;
}
$changedemail=1;
}
if($continue){
$stmt = $db->prepare("UPDATE users SET user_email=:email,changedemail=:changedemail WHERE id = :uid");
$stmt->execute(array(':email' => $email,':changedemail'=>$changedemail,':uid'=>$uid));
$user['user_email']=$email;
$smarty->assign("user", $user, true);
$success="Your profile has been updated!";
if($changedemail==1){
$_SESSION = array();
session_destroy();
$u->remove_cookie();
header('Location: '.$settings['siteurl'].'/login');
die();
}
}	
}	
}else if($page=='password'){
$title="Change Password";	
if($_POST){
$continue=true;
$current=$_POST['currentpass'];
$pass1=$_POST['password_1'];
$pass2=$_POST['password_2'];
//check current password
$stmt = $db->prepare("SELECT user_password_hash FROM users WHERE id=:uid LIMIT 1");
$stmt->bindValue(':uid', $uid, PDO::PARAM_INT);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
$ucheck=$result[0];
if (password_verify($current,$ucheck['user_password_hash'])) {
//password is right
}else{
$error='Incorrect current password.';
$continue=false;	
}
if (!$pass1 OR strlen($pass1) < 6  OR strlen($pass1) > 30 ){
$error='Password must be 6-30 characters.';
$continue=false;
}
//check if passwords are same
if($pass1!=$pass2){
$error="Passwords do not match. Try again";
$continue=false;
}

if($continue){
session_regenerate_id(true);
$user_password_hash = password_hash($pass1, PASSWORD_DEFAULT);
$stmt = $db->prepare("UPDATE users SET user_password_hash=:user_password_hash WHERE id = :uid");
$stmt->execute(array(':user_password_hash' => $user_password_hash,':uid'=>$uid));
$success="Your password has been changed!";
}
}
}
$db=null;

$smarty->assign('errors',$error,true);	
$smarty->assign('title',$title,true);
$smarty->assign('s','edit',true);
$smarty->assign('success',$success,true);
$smarty->assign('description',false,true);
$smarty->assign('keywords',false,true);
$smarty->assign('game',false,true);
$smarty->assign('tag',false,true);

$smarty->assign('page',$page,true);
$smarty->display('edit.tpl');
?>