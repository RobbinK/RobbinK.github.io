<?php
include('functions.php');

try {
$stmt = $db->query("SELECT id,username,xp,avatar,max(xp) as scores FROM users GROUP BY id ORDER BY scores DESC LIMIT 10");
$content = $stmt->fetchAll(PDO::FETCH_ASSOC);
$i=1;
$rank=1;
$last=0;
foreach($content as &$c){
if($c['xp']==$last){
$c['rank']=$rank;	
}else{
$last=$c['xp'];
$c['rank']=$i;
$rank=$i;
}
$i++;
}
$smarty->assign('res',$content);
} catch(PDOException $ex) {
if($smarty->debugging){
echo $ex->getMessage();
}
error_log($ex->getMessage());
}

//
//Edit SEO in the admin cp
//
$stmt = $db->query("SELECT id,name,value FROM settings_pages WHERE page='top'");
$settings_page = $stmt->fetchAll(PDO::FETCH_ASSOC);
$settings_pg=array();
foreach($settings_page as $s){
$name=$s['name'];	
$settings_pg[$name]=$s['value'];
}

$smarty->assign("title",$settings_pg['title'],false);
$smarty->assign('description',false,true);
$smarty->assign('keywords',false,true);
$smarty->assign('game',false,true);
$smarty->assign('tag',false,true);
$smarty->assign('s',false,true);

$db=null;
$smarty->display('top.tpl');
?>