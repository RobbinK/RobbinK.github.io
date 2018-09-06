<?php
include('../functions.php');
include('../inserts.php');
if(!$user || $user['id']<1){
header('Location: '.$settings['siteurl'].'/login');
die();
}else{
include('check.php');
}

$stmt = $db->query("SELECT id,regdate FROM users");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
$usercount = $stmt->rowCount();
$smarty->assign('usercount',$usercount,true);

$stmt = $db->query("SELECT sum(play_count) as totalplays FROM stats_plays");
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
$gameplays=intval($result[0]['totalplays']);
$smarty->assign('gameplays',$gameplays,true);

$stmt = $db->query("SELECT count(id) as counter,CAST(regdate AS DATE) as dater FROM users GROUP BY dater ORDER BY id DESC LIMIT 15");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
$smarty->assign('users',array_reverse($users),true);

$stmt = $db->query("SELECT lastupdate FROM admin_cron LIMIT 1");
$lastupdate = $stmt->fetchAll(PDO::FETCH_ASSOC);
$smarty->assign('lastupdate',$lastupdate[0]['lastupdate'],true);

$stmt = $db->query("SELECT play_count,dayofweek FROM stats_plays ORDER BY id DESC LIMIT 15");
$games = $stmt->fetchAll(PDO::FETCH_ASSOC);
$smarty->assign('games',array_reverse($games),true);

$now = new DateTime;
$ago = new DateTime($lastupdate[0]['lastupdate']);
$diff = $now->diff($ago);


$smarty->assign('page','home',true);
$smarty->display('index.tpl');
?>
