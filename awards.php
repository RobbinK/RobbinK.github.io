<?php
include('functions.php');
$cache_id='awards';

$page="all";
$p=1;

//
//Edit SEO in the admin cp
//
$stmt = $db->query("SELECT id,name,value FROM settings_pages WHERE page='awards'");
$settings_page = $stmt->fetchAll(PDO::FETCH_ASSOC);
$settings_pg=array();
foreach($settings_page as $s){
$name=$s['name'];	
$settings_pg[$name]=$s['value'];
}

$smarty->assign("title",$settings_pg['title'],true);
$smarty->assign("keywords",$settings_pg['keywords'],true);
$smarty->assign("seodesc",$settings_pg['desc'],true);

$gamesdb='games_'.$settings['lang'];
if(isset($_GET['page'])){
$page=$_GET['page'];
}
$query="(has_achs=1 OR has_scores=1)";
if(!$page || ($page!='scores'&&$page!='awards')){
$page='all';
}
if($page=='scores'){
$query="has_scores=1";
}else if($page=='awards'){
$query="has_achs=1";
}

$stmt = $db->query("SELECT * FROM games WHERE {$query}");
$total_items = $stmt->rowCount();
if(isset($_GET['p'])){
$p = intval($_GET['p']);
}
if((!$p) || (is_numeric($p) == false) || ($p < 0) || ($p > $total_items)){
$p = 1;
}
$per_page = 45;
$total_pages = ceil($total_items / $per_page);
$page_from=($p - 5 > 0 ? $p - 5 : 1);
$page_to=($p + 5 <= $total_pages ? $p + 5 : $total_pages);
$set_limit = $p * $per_page - ($per_page);

$smarty->assign('p',$p,true);
$smarty->assign('total_pages',$total_pages,true);
$smarty->assign('page_from',$page_from,true);
$smarty->assign('page_to',$page_to,true);

try {
$stmt = $db->prepare("SELECT g.*,gen.name,gen.url_key FROM {$gamesdb} gen,games g WHERE {$query} AND g.id=gen.gid ORDER BY g.plays_month DESC LIMIT {$set_limit}, {$per_page}");
$stmt->execute();
$game = $stmt->fetchAll(PDO::FETCH_ASSOC);
$smarty->assign('games',$game,true); 
} catch(PDOException $ex) {
if($smarty->debugging){
echo $ex->getMessage();
}
error_log($ex->getMessage());
}

$db=null;
$s='awardpage';
$smarty->assign('s',$s,true);
$smarty->assign('page',$page,true);
$smarty->assign('game',false,true);
$smarty->assign('tag',false,true);
$smarty->display('awards.tpl',$cache_id);
?>