<?php
include('../functions.php');
if(!$user || $user['id']<1){
header('Location: '.$settings['siteurl'].'/login');
die();
}else{
include('check.php');
}

$query="";
if(isset($_GET['query'])){
$query=$_GET['query'];
}
if(strlen($query)<2){
$query=null;
}


$sort = "id";

if($query){
$sql = "SELECT * FROM users WHERE username LIKE ?";
$params = array("%$query%");
$stmt = $db->prepare($sql);
$stmt->execute($params);
$total_items = $stmt->rowCount();	
}else{
$stmt = $db->query("SELECT * FROM users");
$total_items = $stmt->rowCount();
}
$smarty->assign('query',$query,true);
$smarty->assign('total_items',$total_items,true);

if($total_items>0){
$p=1;
if(isset($_GET['p'])){
$p = intval($_GET['p']);
}
if ((!$p) || (is_numeric($p) == false) || ($p < 0) || ($p > $total_items)){
$p = 1;
}

$per_page = 50;
$total_pages = ceil($total_items / $per_page);
if($p>$total_pages){
$p=$total_pages;
}
$page_from=($p - 15 > 0 ? $p - 15 : 1);
$page_to=($p + 15 <= $total_pages ? $p + 15 : $total_pages);
$set_limit = $p * $per_page - ($per_page);
$smarty->assign('p',$p,true);
$smarty->assign('total_pages',$total_pages,true);
$smarty->assign('page_from',$page_from,true);
$smarty->assign('page_to',$page_to,true);

if($query){
$sql = "SELECT * FROM users WHERE username LIKE ? ORDER BY xp DESC LIMIT $set_limit, $per_page";
$params = array("%$query%");
$stmt = $db->prepare($sql);
$stmt->execute($params);
$content = $stmt->fetchAll(PDO::FETCH_ASSOC);	
}else{
$stmt = $db->prepare("SELECT * FROM users ORDER BY {$sort} DESC LIMIT {$set_limit}, {$per_page}");
$stmt->execute();
$content = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
$smarty->assign('users',$content,true);
}
$smarty->assign('page','users',true);
$smarty->display('users.tpl');
?>