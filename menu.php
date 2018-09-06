<?php
include('functions.php');
$smarty->caching = 0;

$smarty->assign('description',false,true);
$smarty->assign('keywords',false,true);
$smarty->assign('game',false,true);
$smarty->assign('tag',false,true);
$smarty->assign('s',false,true);

$smarty->display('menu.tpl');
?>