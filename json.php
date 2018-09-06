<?php
include('functions.php');
header('Content-Type: application/json');

//
// Go to http://pub.lagged.com/contact for support, feature requests and bug reporting
//


//
//Loads new home page games
//
if($_GET['action']=='homeload'){
//load new games here
$p=intval($_GET['page']);
if(!$p||$p<1){
	$p=1;
}
$per_page = 18;
$set_limit = ($p * $per_page - ($per_page))+40;
$response=array();
$response['errors']=false;
$gamesdb='games_'.$settings['lang'];
$ignore="";

//
//get 6 new games
//
$query="SELECT g.id FROM games g ORDER BY g.id DESC LIMIT 6";
$stmt = $db->query($query);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach($results as &$r){
	$ignore=$ignore.','.$r['id'];
}
$ignore=trim($ignore,',');

try {
$query="SELECT g.id, g.thumb, gen.name, g.has_achs,g.has_scores,gen.url_key FROM $gamesdb gen,games g WHERE g.id NOT IN ($ignore) AND gen.gid=g.id AND g.feature=1 ORDER BY g.plays_month DESC, g.id DESC LIMIT $set_limit,$per_page";
$stmt = $db->query($query);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $ex) {
if($smarty->debugging){
	echo $ex->getMessage();
}
	error_log($ex->getMessage());
	$response['errors']=true;
	$response['msg']="Database error";
}

//
//if no games return 'done' so no more requests
//
if(count($results)==0){
$response['errors']=true;
$response['msg']="No games";	
}else{
$response['games']=$results;
}

//
// return JSON
//
echo json_encode($response);

}else if($_GET['action']=='catload'){
	$p=intval($_GET['page']);
	$s=$_GET['sort'];
	$sort='g.plays_month';
	if($s==='recent'){
	$sort='g.id';
	}
	if(!$p||$p<1){
		$p=1;
	}
	$per_page = 18;
	$set_limit = ($p * $per_page - ($per_page))+46;
	$response=array();
	$response['errors']=false;
	$gamesdb='games_'.$settings['lang'];
	
	try {
	$query="SELECT g.id, g.thumb, gen.name, g.has_achs,g.has_scores,gen.url_key FROM $gamesdb gen,games g WHERE gen.gid=g.id ORDER BY $sort DESC LIMIT $set_limit,$per_page";
	$stmt = $db->query($query);
	$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
	} catch(PDOException $ex) {
	if($smarty->debugging){
		echo $ex->getMessage();
	}
		error_log($ex->getMessage());
		$response['errors']=true;
		$response['msg']="Database error";
	}
	
	
	//
	//if no games return 'done' so no more requests
	//
	if(count($results)==0){
	$response['errors']=true;
	$response['msg']="No games";	
	}else{
	$response['games']=$results;
	}

	//
	// return JSON
	//
	echo json_encode($response);

}else if($_GET['action']=='friend'){

//
// Add/Remove Friend
//

$friend_id=intval($_GET['fid']);	
$uid=intval($user['id']);
$method=$_GET['method'];
if($uid>0 && $friend_id>0){
//check if user exists
$stmt = $db->prepare("SELECT id FROM users WHERE id=:friend_id LIMIT 1");
$stmt->bindValue(':friend_id', $friend_id, PDO::PARAM_INT);
$stmt->execute();
$usercheck = $stmt->rowCount();
if ($usercheck>0){
if($method=='add'){
//insert
$stmt = $db->prepare("SELECT id FROM users_friends WHERE following_uid=:friend_id AND follower_uid=:uid LIMIT 1");
$stmt->bindValue(':friend_id', $friend_id, PDO::PARAM_INT);
$stmt->bindValue(':uid', $uid, PDO::PARAM_INT);
$stmt->execute();
$frncheck = $stmt->rowCount();
if(!$frncheck||$frncheck==0){
$stmt = $db->prepare("INSERT IGNORE INTO users_friends (following_uid,follower_uid) VALUES(:field1,:field2)");
$stmt->execute(array(':field1' => $friend_id, ':field2' => $uid));
}
}else{
//delete
$stmt = $db->prepare("DELETE FROM users_friends WHERE following_uid=:friend_id AND follower_uid=:uid");
$stmt->bindValue(':friend_id', $friend_id, PDO::PARAM_INT);
$stmt->bindValue(':uid', $uid, PDO::PARAM_INT);
$stmt->execute();	
}
}
}
}else if($_GET['action']=='signup'){
//
//In game sign up
//
$response=array();
$data=json_decode(base64_decode($_GET['data']),true);
session_regenerate_id(true);
$response['success']=true;
$response['errors']=false;
$response['uid']=0;
$go_with_the_flow = 1;
$email=strip_tags($data['email']);
$password=$data['password'];
$name=strip_tags($data['name']);
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
$error='Email is already registered. <a href="http://lagged.com/login">Log in here</a>.';
$go_with_the_flow = 0;
}
}
if($go_with_the_flow == 1){
$ip=$_SERVER['REMOTE_ADDR'];
$user_password_hash = password_hash($password, PASSWORD_DEFAULT);
$stmt = $db->prepare("INSERT IGNORE INTO users (user_email,user_password_hash,username,ipaddress) VALUES(:field1,:field2,:field3,:field4)");
$stmt->execute(array(':field1' => $email, ':field2' => $user_password_hash, ':field3' => $name, ':field4' => $ip));
$realuid=$db->lastInsertId();
$response['uid']=$realuid;
if($ach_count>0 && $realuid>0){
$sql = array();
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
if(count($_SESSION["scores"])>0 && $realuid>0){
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
$u->regen_cookie();

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
}else{
$response['errors']=$error;	
$response['success']=false;	
}	
echo json_encode($response);
}
$db=null;
?>