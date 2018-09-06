<?php
include('functions.php');
$profile_uid=intval($_GET['uid']);
$page=$_GET['page'];
$uid=intval($user['id']);
$is_profile=false;
$awards=null;
$isfriend=null;
$friends=null;
$followers=null;
$gamesdb='games_'.$settings['lang'];
if(!$profile_uid || $profile_uid==0){
exit('this user does not exist');
}
if($profile_uid==$uid){
$is_profile=true;	
}

if($page!='friends'&&$page!='followers'){
$page='awards';
}
try {
$stmt = $db->prepare("SELECT u.id,u.avatar,u.username,u.regdate,u.verified,u.xp,ul.id as level,ul.xpmax,ul.points FROM users u,users_levels ul WHERE u.id=:id AND ul.points <= u.xp ORDER BY ul.points DESC LIMIT 1");
$stmt->bindValue(':id', $profile_uid, PDO::PARAM_INT);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
$profile=$results[0];
} catch(PDOException $ex) {
if($smarty->debugging){
echo $ex->getMessage();
}
error_log($ex->getMessage());
}
if(!$profile['id']||$profile['id']==0){
exit('this user does not exist');
}
$smarty->assign('profile',$profile,true);

if($profile_uid!=$uid && $uid>0){
try {
$stmt = $db->prepare("SELECT id FROM users_friends WHERE follower_uid=:uid AND following_uid=:pid LIMIT 1");
$stmt->bindValue(':uid', $uid, PDO::PARAM_INT);
$stmt->bindValue(':pid', $profile_uid, PDO::PARAM_INT);
$stmt->execute();
$is_friends = $stmt->rowCount();
} catch(PDOException $ex) {
if($smarty->debugging){
echo $ex->getMessage();
}
error_log($ex->getMessage());
}
if($is_friends>0){
$isfriend=true;
}
$smarty->assign('isfriend',$isfriend,true);	
}

try {
$stmt = $db->prepare("SELECT id FROM users_achievemet WHERE uid=:pid");
$stmt->bindValue(':pid', $profile_uid, PDO::PARAM_INT);
$stmt->execute();
$award_count = $stmt->rowCount();
$smarty->assign('award_count',$award_count,true);
} catch(PDOException $ex) {
if($smarty->debugging){
echo $ex->getMessage();
}
error_log($ex->getMessage());
}

try {
$stmt = $db->prepare("SELECT id FROM users_friends WHERE follower_uid=:pid");
$stmt->bindValue(':pid', $profile_uid, PDO::PARAM_INT);
$stmt->execute();
$friends_count = $stmt->rowCount();
$smarty->assign('friends_count',$friends_count,true);
} catch(PDOException $ex) {
if($smarty->debugging){
echo $ex->getMessage();
}
error_log($ex->getMessage());
}

try {
$stmt = $db->prepare("SELECT id FROM users_friends WHERE following_uid=:pid");
$stmt->bindValue(':pid', $profile_uid, PDO::PARAM_INT);
$stmt->execute();
$followers_count = $stmt->rowCount();
$smarty->assign('followers_count',$followers_count,true);
} catch(PDOException $ex) {
if($smarty->debugging){
echo $ex->getMessage();
}
error_log($ex->getMessage());
}

if($page=='awards'){
if($award_count>0){
try {
$stmt = $db->prepare("SELECT a.id,a.points,a.name,a.textdesc,g.url_key,g.name as gname,game.thumb FROM achievements a, users_achievemet ua,{$gamesdb} g,games game WHERE ua.uid=:id AND ua.aid=a.ach_id AND game.id=a.gid AND g.id=a.gid ORDER BY ua.id DESC LIMIT 100");
$stmt->bindValue(':id', $profile_uid, PDO::PARAM_INT);
$stmt->execute();
$awards = $stmt->fetchAll(PDO::FETCH_ASSOC);
$smarty->assign('awards',$awards,true);
} catch(PDOException $ex) {
if($smarty->debugging){
echo $ex->getMessage();
}
error_log($ex->getMessage());
}
}
}else if($page=='friends'){
if($friends_count>0){
try {
$stmt = $db->prepare("SELECT u.id,u.avatar,u.username FROM users u, users_friends f WHERE f.follower_uid=:id AND u.id=f.following_uid ORDER BY f.id DESC LIMIT 50");
$stmt->bindValue(':id', $profile_uid, PDO::PARAM_INT);
$stmt->execute();
$friends = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $ex) {
if($smarty->debugging){
echo $ex->getMessage();
}
error_log($ex->getMessage());
}
}
}else if($page=='followers'){
if($followers_count>0){
try {
$stmt = $db->prepare("SELECT u.id,u.avatar,u.username FROM users u, users_friends f WHERE f.following_uid=:id AND u.id=f.follower_uid ORDER BY f.id DESC LIMIT 50");
$stmt->bindValue(':id', $profile_uid, PDO::PARAM_INT);
$stmt->execute();
$followers = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $ex) {
if($smarty->debugging){
echo $ex->getMessage();
}
error_log($ex->getMessage());
}
}
}


//
//Edit SEO in the admin cp
//
$stmt = $db->query("SELECT id,name,value FROM settings_pages WHERE page='profile'");
$settings_page = $stmt->fetchAll(PDO::FETCH_ASSOC);
$settings_pg=array();
foreach($settings_page as $s){
$name=$s['name'];	
$settings_pg[$name]=$s['value'];
}

$find_var = array(":-username:");
$replace_var = array(ucwords($profile['username']));
$title=str_replace($find_var,$replace_var,$settings_pg['title']);
$smarty->assign("title",$title,false);

$smarty->assign('followers',$followers,true);
$smarty->assign('friends',$friends,true);
$smarty->assign('isfriend',$isfriend,true);	
$smarty->assign('awards',$awards,true);
$smarty->assign('description',false,true);
$smarty->assign('keywords',false,true);
$smarty->assign('game',false,true);
$smarty->assign('tag',false,true);

$db=null;

$smarty->assign('s','profile',true);
$smarty->assign('page',$page,true);
$smarty->assign('is_profile',$is_profile,true);
$smarty->display('profile_more.tpl');
?>