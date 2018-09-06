<?php
include('functions.php');
$errors=false;
$email=false;
if($user['id']>0){
header('Location: '.$settings['siteurl'].'/profile/'.$user['id']);
die();
}

//
//Edit SEO in the admin cp
//
$stmt = $db->query("SELECT id,name,value FROM settings_pages WHERE page='login'");
$settings_page = $stmt->fetchAll(PDO::FETCH_ASSOC);
$settings_pg=array();
foreach($settings_page as $s){
$name=$s['name'];	
$settings_pg[$name]=$s['value'];
}

$smarty->assign("title",$settings_pg['title'],false);

if($_POST){
$email=$_POST['user_name'];
if (empty($email)) {
$errors='Please enter a valid email';
}elseif (!preg_match('/^[_A-z0-9-]+((\.|\+)[_A-z0-9-]+)*@[A-z0-9-]+(\.[A-z0-9-]+)*(\.[A-z]{2,4})$/', $email) ){
$errors='Please enter a valid email';
$email="";	
}elseif (empty($_POST['user_password'])) {
$errors='Please enter a valid passord';
} elseif (!empty($_POST['user_name']) && !empty($_POST['user_password'])) {
try {
$stmt = $db->prepare("SELECT id,user_email,user_password_hash FROM users WHERE user_email = :email LIMIT 1");
$stmt->bindValue(':email', $email, PDO::PARAM_STR);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
$user=$result[0];
} catch(PDOException $ex) {
if($smarty->debugging){
echo $ex->getMessage();
}
error_log($ex->getMessage());
}

if(intval($user['id'])>0){
if (password_verify($_POST['user_password'],$user['user_password_hash'])) {
session_regenerate_id(true);
$_SESSION['user_email'] = $user['user_email'];
$_SESSION['user_login_status'] = 1;
$uid=intval($user['id']);
usleep(200);
$u->regen_cookie();

if($ach_count>0 && $uid>0){
$insertQuery = array();
$insertData = array();
$safeids="";
if(isset($_SESSION['awards'])){
//select already earned achievements
try {
$stmt = $db->prepare("SELECT aid FROM users_achievemet WHERE uid= :uid");
$stmt->bindValue(':uid', $uid, PDO::PARAM_INT);
$stmt->execute();
$awards = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $ex) {
if($smarty->debugging){
echo $ex->getMessage();
}
error_log($ex->getMessage());
}
$achcheck=array();
foreach($awards as $ac){
$achcheck[]=$ac['aid'];
}
foreach(explode(",",$_SESSION['awards']) as $a) {
$achid=$a;
$check_ach=preg_match('/^[A-Za-z0-9_]+$/', $achid);
if($check_ach){
if (!in_array($achid, $achcheck)) {
$safeids=$safeids.",".$a;
$insertQuery[] = '(?, ?)';
$insertData[] = $uid;
$insertData[] = $achid;
}
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
$stmt->execute(array(':count' => $addit,':id'=>$uid));
}
unset($_SESSION['awards']);
}
}

//check high scores
if(isset($_SESSION["scores"]) && count($_SESSION["scores"])>0 && $uid>0){
$insertQuery = array();
$insertData = array();
foreach($_SESSION["scores"] as $key=>$score){
$rscore=intval($score);
$board=$key;
$checkboard=preg_match('/^[A-Za-z0-9_]+$/', $board);
if(!$checkboard){
return;	
}
//select score from user_scores
try {
$stmt = $db->prepare("SELECT score FROM users_scores WHERe uid=:uid AND board_id=:board LIMIT 1");
$stmt->bindValue(':uid', $uid, PDO::PARAM_INT);
$stmt->bindValue(':board', $board, PDO::PARAM_INT);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
$scorecheck=$result[0];
} catch(PDOException $ex) {
if($smarty->debugging){
echo $ex->getMessage();
}
error_log($ex->getMessage());
}

if($scorecheck && $scorecheck['score']){
if($rscore>$scorecheck['score']){
//update
$stmt = $db->prepare("UPDATE users_scores SET score=:score WHERE uid=:uid AND board_id=:board");
$stmt->execute(array(':score' => $rscore,':uid'=>$uid,':board'=>$board));
}
}else{
//insert
$insertQuery[] = '(?, ?,?)';
$insertData[] = $uid;
$insertData[] = $board;
$insertData[] = $rscore;
}
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

if(!isset($user['ipaddress'])){
$ip=$_SERVER['REMOTE_ADDR'];
$stmt = $db->prepare("UPDATE users SET ipaddress=? WHERE id=?");
$stmt->execute(array($ip, $uid));
}

header('Location: '.$settings['siteurl']);
die();
}else{
$errors='Incorrect Password';
}
}else{
$errors='Email not registered';
}
}
}

$smarty->assign('email',$email,true);
$smarty->assign('errors',$errors,true);
$smarty->assign('description',false,true);
$smarty->assign('keywords',false,true);
$smarty->assign('game',false,true);
$smarty->assign('tag',false,true);
$smarty->assign('s',false,true);

$smarty->display('login.tpl');
?>