<?php
include('functions.php');

//
//Edit SEO in the admin cp
//
$stmt = $db->query("SELECT id,name,value FROM settings_pages WHERE page='privacy'");
$settings_page = $stmt->fetchAll(PDO::FETCH_ASSOC);
$settings_pg=array();
foreach($settings_page as $s){
$name=$s['name'];	
$settings_pg[$name]=$s['value'];
}

$smarty->assign("title",$settings_pg['title'],false);

//
//Edit your privacy message in the admin cp
//
$stmt = $db->query("SELECT id,result FROM settings WHERE name='privacy'");
$settings_page = $stmt->fetchAll(PDO::FETCH_ASSOC);
$smarty->assign("privacyinfo",$settings_page[0]['result'],false);

$smarty->assign('description',false,true);
$smarty->assign('keywords',false,true);
$smarty->assign('game',false,true);
$smarty->assign('tag',false,true);
$smarty->assign('s',false,true);

$smarty->display('privacy.tpl');
?>