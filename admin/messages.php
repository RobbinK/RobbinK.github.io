<?php
include('../functions.php');
if(!$user || $user['id']<1){
header('Location: '.$settings['siteurl'].'/login');
die();
}else{
include('check.php');
}

$stmt = $db->query("SELECT * FROM contact ORDER BY id DESC LIMIT 50");
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
$smarty->assign('results',$results,true);

$db->exec("UPDATE contact SET viewed=1");
$smarty->assign('messages',0,true);


$smarty->assign('page','msg',true);
$smarty->display('messages.tpl');
?>