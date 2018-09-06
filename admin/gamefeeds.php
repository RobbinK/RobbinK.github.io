<?php
include('../functions.php');
if(!$user || $user['id']<1){
header('Location: /login');
die();
}else{
include('check.php');
}

$feed=$_GET['feed'];

//select games from feed
$stmt = $db->prepare("SELECT * FROM feeds WHERE feed_name=:feed ORDER BY id DESC LIMIT 100");
$stmt->bindValue(':feed', $feed, PDO::PARAM_STR);
$stmt->execute();
$games = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach($games as &$g){
$tagg=explode(",",$g['tags']);
$tagg=array_slice($tagg,0,3);
$g['tags']=$tagg;
}
$smarty->assign('games',$games,true);

$db=null;
$smarty->assign('page','games',true);
$smarty->assign('feed',$feed,true);
$smarty->display('gamefeeds.tpl');
?>
