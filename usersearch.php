<?php
include('functions.php');
$smarty->caching = 0;
$id=null;
if(isset($_POST['query'])){
$id=htmlentities($_POST['query']);
$id=utf8_encode($id);
}
if(strlen($id)<2){
$id=false;
}
$smarty->assign('tag',$id,true);

$p=1;
$total_pages=1;
$page_from=1;
$page_to=1;
$content=null;

if($id){
try {
$stmt = $db->prepare("SELECT u.id,u.avatar,u.username FROM users u WHERE (u.username LIKE ?) ORDER BY u.xp DESC LIMIT 25");
$stmt->bindValue(1, "%$id%", PDO::PARAM_STR);
$stmt->execute();
$content = $stmt->fetchAll(PDO::FETCH_ASSOC);
$smarty->assign('res',$content,true);
} catch(PDOException $ex) {
if($smarty->debugging){
echo $ex->getMessage();
}
error_log($ex->getMessage());
}
}
$realid = ucwords(strtr($id, '-', ' '));
//
//Edit SEO in the admin cp
//
$stmt = $db->query("SELECT id,name,value FROM settings_pages WHERE page='search'");
$settings_page = $stmt->fetchAll(PDO::FETCH_ASSOC);
$settings_pg=array();
foreach($settings_page as $s){
$name=$s['name'];	
$settings_pg[$name]=$s['value'];
}

$find_var = array(":-term:");
$replace_var = array($realid);
$title=str_replace($find_var,$replace_var,$settings_pg['title']);
$smarty->assign("title",$title,true);

$smarty->assign('description',false,true);
$smarty->assign('keywords',false,true);
$smarty->assign('game',false,true);
$smarty->assign('tag',false,true);
$smarty->assign('s',false,true);
$smarty->assign('res',$content,true);
$smarty->assign('p',$p,true);
$smarty->assign('total_pages',$total_pages,true);
$smarty->assign('page_from',$page_from,true);
$smarty->assign('page_to',$page_to,true);

$db=null;
$smarty->display('usersearch.tpl');
?>