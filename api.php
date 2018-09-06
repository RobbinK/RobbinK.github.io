<?php
include('functions.php');
header('Content-type: application/json');

//
//Full api docs at pub.lagged.com/api
//

$uid=intval($user['id']);
$return=array();
$gamesdb="games_".$settings['lang'];

$type=$_POST['type'];
$action=$_POST['action'];
$data=json_decode(base64_decode($_POST['data']),true);
$hash=$_POST['hash'];
$hast_check=md5(base64_decode($_POST['data']).'lgb8gb');

if($hash!=$hast_check){
$return['errormsg']="token mismatch";
echo ")]}',\n".json_encode($return);
die();
}

if($type=='achievement'){
//save is only action currently
if($action=='save'){
$showachcc=true;
$returndata=array();
$returndata['aid']=array();
$addcookie=true;

if(isset($_SESSION['awards'])){
$cookie_value=$_SESSION['awards'];
}else{
$cookie_value="";
}
$ach_ids="";

foreach(array_reverse($data['awards']) as $key=>$ach){
$check_ach=preg_match('/^[A-Za-z0-9_]+$/', $ach);
if(!$check_ach){
$return['errormsg']="token mismatch";
echo ")]}',\n".json_encode($return);
die();	
}

$datanew=$ach;
$returndata['aid'][]=$datanew;
$returndata['achdata']=array();
$returndata['show']=true;
$returndata['login']=false;
if($uid==0){
$returndata['login']=true;
//check cookie if already saved
if(isset($_SESSION['awards'])){	
$addit=true;
$listarr=explode(",",$_SESSION['awards']);
foreach($listarr as $key=>$f){
if($f==$datanew){
$addit=false;
break;
}
}

if($addit){	
$cookie_value = $datanew.','.$cookie_value;
$cookie_value = trim($cookie_value,',');
$returndata['show']=true;
$showachcc=true;
$addcookie=true;
}else{
$returndata['show']=false;	
$showachcc=false;
$addcookie=false;
}
}else{
$cookie_value = $datanew.','.$cookie_value;
$cookie_value = trim($cookie_value,',');
$returndata['show']=true;
$showachcc=true;
$addcookie=true;
}
if($addcookie){
$ach_ids=$datanew.",".$ach_ids;
$_SESSION['awards']=$cookie_value;
}
}else{
//check SQL if already saved
try {
$stmt = $db->prepare("SELECT id FROM users_achievemet WHERE aid = :datanew AND uid=:uid");
$stmt->bindValue(':datanew', $datanew, PDO::PARAM_STR);
$stmt->bindValue(':uid', $uid, PDO::PARAM_INT);
$stmt->execute();
$total_items = $stmt->rowCount();
} catch(PDOException $ex) {
error_log($ex->getMessage());
}

if($total_items>0){
$returndata['show']=false;
$showachcc=false;
}else{
$returndata['show']=true;
$showachcc=true;
$ach_ids=$datanew.",".$ach_ids;
try {
$stmt = $db->prepare("INSERT IGNORE INTO users_achievemet (uid,aid) VALUES(:field1,:field2)");
$stmt->execute(array(':field1' => $uid, ':field2' => $datanew));
} catch(PDOException $ex) {
error_log($ex->getMessage());
}
}
}
}
if($showachcc){
$ach_ids=trim($ach_ids,',');
$ach_values=explode(',',$ach_ids);
$sql = 'SELECT id,textdesc,name,sum(points) as earnedpts,points FROM achievements WHERE ach_id IN ('.str_pad('',count($ach_values)*2-1,'?,').')';
$stmt = $db->prepare($sql);
$stmt->execute($ach_values);
$content = $stmt->fetchAll(PDO::FETCH_ASSOC);
if(count($ach_values)>1){
$returndata['achdata']=array();
$returndata['achdata']['id']=1;
$returndata['achdata']['name']=count($ach_values)." Awards Unlocked";
$returndata['achdata']['points']=$content[0]['earnedpts'];
}else{
$returndata['achdata']=$content[0];
}
$addit=intval($content[0]['earnedpts']);
if($uid>0&&$addit>0){
//give user points
$stmt = $db->prepare("UPDATE users SET xp = xp+:addit WHERE id = :uid");
$stmt->bindValue(':addit', $addit, PDO::PARAM_INT);
$stmt->bindValue(':uid', $uid, PDO::PARAM_INT);
$stmt->execute();
}
}
//return should be json array. achievement can be show:true/false with achievement data
$return['data']=$returndata;
echo ")]}',\n".json_encode($return);
}
}

if($type=='scores'){
$returndata=array();
$returndata['scoredata']=array();
$returndata['login']=false;
if($action=='save'){
$score=intval($data['score']);
$board=$data['board'];
$check_ach=preg_match('/^[A-Za-z0-9_]+$/', $board);
if(!$check_ach){
$return['errormsg']="token mismatch";
echo ")]}',\n".json_encode($return);
die();	
}
//board + game data
try {
$stmt = $db->prepare("SELECT h.id,h.name as bname,h.score_order,gm.name,g.thumb,gm.url_key FROM games g,{$gamesdb} gm, highscore_boards h WHERE h.board_id = :board AND g.id=h.gid AND gm.id=h.gid LIMIT 1");
$stmt->bindValue(':board', $board, PDO::PARAM_STR);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
$game=$results[0];
} catch(PDOException $ex) {
error_log($ex->getMessage());
}
if($uid>0){
$game['uid']=$user['id'];
$game['username']=$user['username'];
}
$returndata['gamedata']=$game;
$sort='DESC';
$minmax="max(s.score)";
if($game['score_order']==0 || !$game['score_order']){
$sort='ASC';
$minmax="min(s.score)";	
}
$limit=50;
if($uid==0){
$limit=25;
}
if($uid>0){
//check if score exists
$stmt = $db->prepare("SELECT id,{$minmax} as scores FROM users_scores s WHERE s.board_id = :board AND s.uid=:uid ORDER BY scores {$sort} LIMIT 1");
$stmt->bindValue(':board', $board, PDO::PARAM_STR);
$stmt->bindValue(':uid', $uid, PDO::PARAM_INT);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
$userscore=$results[0];
if(isset($userscore['scores'])){
if($score>$userscore['scores']){
$sidd=intval($userscore['id']);
$stmt = $db->prepare("UPDATE users_scores SET score = :score,timestamped=CURRENT_TIMESTAMP WHERE id = :sidd AND uid=:uid");
$stmt->bindValue(':score', $score, PDO::PARAM_INT);
$stmt->bindValue(':sidd', $sidd, PDO::PARAM_INT);
$stmt->bindValue(':uid', $uid, PDO::PARAM_INT);
$stmt->execute();
}
}else{
try {
$stmt = $db->prepare("INSERT IGNORE INTO users_scores (uid,score,board_id) VALUES(:field1,:field2,:field3)");
$stmt->execute(array(':field1' => $uid, ':field2' => $score, ':field3' => $board));
} catch(PDOException $ex) {
error_log($ex->getMessage());
}
}	
}else{
$returndata['login']=true;
if(isset($_SESSION["scores"])){
if(isset($_SESSION["scores"][$board])){
if($score>$_SESSION["scores"][$board]){
$_SESSION["scores"][$board]=$score;		
}
}else{
$_SESSION["scores"][$board]=$score;	
}
}else{
$_SESSION["scores"]=array();
$_SESSION["scores"][$board]=$score;
}
}
}
if($action=='save' || $action=='load'){
$stmt = $db->prepare("SELECT s.id,s.uid,{$minmax} as scores,s.timestamped,u.username,u.avatar FROM users_scores s,users u WHERE s.board_id = :board AND u.id=s.uid GROUP BY s.uid ORDER BY scores {$sort},s.id ASC LIMIT {$limit}");
$stmt->bindValue(':board', $board, PDO::PARAM_INT);
$stmt->execute();
$scores = $stmt->fetchAll(PDO::FETCH_ASSOC);

$returndata['scoredata']=$scores;
if($uid>0){
$sortnew='>';
if($game['score_order']==0 || !$game['score_order']){
$sortnew='<';
}	
$stmt = $db->prepare("SELECT 1 + (SELECT count( * ) FROM users_scores a WHERE a.score {$sortnew} b.score AND a.board_id=:board ) AS rank,score,id FROM users_scores b WHERE uid =:id AND board_id=:boarda ORDER BY rank LIMIT 1");
$stmt->bindValue(':board', $board, PDO::PARAM_STR);
$stmt->bindValue(':id', $uid, PDO::PARAM_STR);
$stmt->bindValue(':boarda', $board, PDO::PARAM_STR);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
$utop=$results[0];
$returndata['utop']=$utop;
}
if($uid>0){
//if log in get friend board
$stmt = $db->prepare("SELECT GROUP_CONCAT(DISTINCT following_uid SEPARATOR ',') AS id_list FROM users_friends WHERE follower_uid=:uid GROUP BY follower_uid LIMIT 1");
$stmt->bindValue(':uid', $uid, PDO::PARAM_INT);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
if($results){
$profile_friends = $results[0];
$fids=$profile_friends['id_list'].','.$uid;
$fids=trim($fids, ',');
$stmt = $db->query("SELECT s.id,s.uid,$minmax as scores,s.timestamped,u.username,u.avatar FROM users_scores s,users u WHERE s.board_id = '".$board."' AND u.id IN (".$fids.") AND u.id=s.uid GROUP BY s.uid ORDER BY scores $sort,s.id ASC LIMIT $limit");
$friend_board = $stmt->fetchAll(PDO::FETCH_ASSOC);
$returndata['frndboard']=$friend_board;
}else{
$returndata['frndboard']=array();	
}
}
}
$return['data']=$returndata;
echo ")]}',\n".json_encode($return);
}


$db=null;
?>