<?php
include('../functions.php');
if(!$user || $user['id']<1){
header('Location: '.$SiteUrl.'/login');
die();
}else{
include('check.php');
}

$smarty->assign('page','support',true);
$smarty->display('support.tpl');
?>