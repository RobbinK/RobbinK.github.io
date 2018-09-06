<?php
include('functions.php');
if($user['id']>0){
header('Location: '.$settings['siteurl'].'/profile/'.$user['id']);
}
$name=false;
$email=false;
$error=false;
if($_POST){
session_regenerate_id(true);
$go_with_the_flow = 1;
$email=strip_tags($_POST['email']);
$password=$_POST['password'];
$name=strip_tags($_POST['name']);
$name=preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', $name);

if(!$name){
$error='Please enter your name';
$go_with_the_flow = 0;	
}
if(strlen($name) < 2 OR strlen($name) > 30){
$error='Nickname must be between 2-30 characters.';
$go_with_the_flow = 0;
}
if ( !preg_match('/^[_A-z0-9-]+((\.|\+)[_A-z0-9-]+)*@[A-z0-9-]+(\.[A-z0-9-]+)*(\.[A-z]{2,4})$/', $email) ){
$error='Please enter a valid email';
$email="";
$go_with_the_flow = 0;
} elseif (!$email){
$error='Please enter a valid email';
$go_with_the_flow = 0;
}
if ( !$password OR strlen($password) < 6  OR strlen($password) > 30 ){
$error='Password must be 6-30 characters.';
$go_with_the_flow = 0;
}
if($go_with_the_flow == 1){

$stmt = $db->prepare("SELECT id FROM users WHERE user_email = :email LIMIT 1");
$stmt->bindValue(':email', $email, PDO::PARAM_STR);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
$email_check=null;
if($result){
$email_check=$result[0];
}
if($email_check && intval($email_check['id'])>0){
$error='Email is already registered. Try another email.';
$go_with_the_flow = 0;
}
}
if($go_with_the_flow == 1){
$ip=$_SERVER['REMOTE_ADDR'];
$user_password_hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $db->prepare("INSERT IGNORE INTO users (user_email,user_password_hash,username,ipaddress) VALUES(:field1,:field2,:field3,:field4)");
$stmt->execute(array(':field1' => $email, ':field2' => $user_password_hash, ':field3' => $name, ':field4' => $ip));
$realuid=$db->lastInsertId();

if($ach_count>0 && $realuid>0){
$insertQuery = array();
$insertData = array();
$safeids="";
$realuid=intval($realuid);
if(isset($_SESSION['awards'])){
foreach(explode(",",$_SESSION['awards']) as $a) {
$achid=$a;
$check_ach=preg_match('/^[A-Za-z0-9_]+$/', $achid);
if($check_ach){
$safeids=$safeids.",".$achid;
$insertQuery[] = '(?, ?)';
$insertData[] = $realuid;
$insertData[] = $achid;
}
}
if (!empty($insertQuery)) {

$db->beginTransaction();
$sql = 'INSERT INTO users_achievemet (uid,aid) VALUES ';
$sql .= implode(', ', $insertQuery);
$stmt = $db->prepare($sql);
$stmt->execute($insertData);
$db->commit();

//give user points for awards
$ach_ids=trim($safeids,',');
$ach_values=explode(',',$ach_ids);
$sql = 'SELECT sum(points) as addit FROM achievements WHERE ach_id IN ('.str_pad('',count($ach_values)*2-1,'?,').')';
$stmt = $db->prepare($sql);
$stmt->execute($ach_values);
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
$addit=intval($result[0]['addit']);

$stmt = $db->prepare("UPDATE users SET xp=xp+:count WHERE id=:id");
$stmt->execute(array(':count' => $addit,':id'=>$realuid));
}
unset($_SESSION['awards']);
}
}

//check high scores
if(isset($_SESSION["scores"]) && count($_SESSION["scores"])>0 && $realuid>0){
$insertQuery = array();
$insertData = array();
foreach($_SESSION["scores"] as $key=>$score){
$rscore=intval($score);
$board=$key;
$check_ach=preg_match('/^[A-Za-z0-9_]+$/', $board);
if(!$check_ach){
return;	
}
$insertQuery[] = '(?, ?,?)';
$insertData[] = $realuid;
$insertData[] = $board;
$insertData[] = $rscore;
}
if (!empty($insertQuery)) {
$db->beginTransaction();	
$sql = 'INSERT INTO users_scores (uid,board_id,score) VALUES ';
$sql .= implode(', ', $insertQuery);
$stmt = $db->prepare($sql);
$stmt->execute($insertData);
$db->commit();
}
unset($_SESSION["scores"]);
}

$_SESSION['user_email'] = $email;
$_SESSION['user_login_status'] = 1;

$ref=null;
if(isset($_SESSION['ref'])){
$ref=intval($_SESSION['ref']);
}
if(isset($ref) && $ref>0){
$stmt = $db->prepare("INSERT IGNORE INTO users_ref (uid,refby_uid) VALUES(:field1,:field2)");
$stmt->execute(array(':field1' => $realuid, ':field2' => $ref));
$stmt = $db->prepare("INSERT IGNORE INTO users_friends (following_uid,follower_uid) VALUES(:field1,:field2)");
$stmt->execute(array(':field1' => $ref, ':field2' => $realuid));
}
usleep(200);
$u->regen_cookie();
header('Location: '.$settings['siteurl'].'/welcome');
die();
}
}

//
//Edit SEO in the admin cp
//
$stmt = $db->query("SELECT id,name,value FROM settings_pages WHERE page='signup'");
$settings_page = $stmt->fetchAll(PDO::FETCH_ASSOC);
$settings_pg=array();
foreach($settings_page as $s){
$name=$s['name'];	
$settings_pg[$name]=$s['value'];
}

$smarty->assign("title",$settings_pg['title'],false);
$smarty->assign('errors',$error,true);
$smarty->assign('name',$name,true);
$smarty->assign('email',$email,true);
$smarty->assign('s','signup',true);
$smarty->assign('description',false,true);
$smarty->assign('keywords',false,true);
$smarty->assign('game',false,true);
$smarty->assign('tag',false,true);
$smarty->display('signup.tpl');
?>