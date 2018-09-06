<?php
include('functions.php');
$smarty->caching = 0;
$id=htmlentities($_GET['id']);
$id=utf8_encode($id);
$smarty->assign('tag',$id,true); 
$query2=false;
$gamesdb='games_'.$settings['lang'];
$p=1;
$total_pages=1;
$page_from=1;
$page_to=1;
$content=null;


if($id===''||!$id){
$head1="Search";
$query2=true;
}
//clean up
$id=str_replace(array(' games',' game'), '', $id);


if($id){
try {
$stmt = $db->prepare("SELECT g.id FROM {$gamesdb} gen,games g WHERE g.id=gen.gid AND (gen.name LIKE ? OR gen.tags LIKE ?)");
$stmt->bindValue(1, "%$id%", PDO::PARAM_STR);
$stmt->bindValue(2, "%$id%", PDO::PARAM_STR);
$stmt->execute();
$total_items = $stmt->rowCount();
$smarty->assign('total_items',$total_items,false);
} catch(PDOException $ex) {
if($smarty->debugging){
echo $ex->getMessage();
}
error_log($ex->getMessage());
}

if(isset($_GET['p'])){
$p = intval($_GET['p']);
}
if ((!$p) || (is_numeric($p) == false) || ($p < 0) || ($p > $total_items)){
$p = 1;
}

$per_page = 45;
$total_pages = ceil($total_items / $per_page);
$page_from=($p - 5 > 0 ? $p - 5 : 1);
$page_to=($p + 5 <= $total_pages ? $p + 5 : $total_pages);
$set_limit = $p * $per_page - ($per_page);

try {
$stmt = $db->prepare("SELECT g.id, g.thumb,g.has_achs,g.has_scores, gen.name, gen.url_key FROM {$gamesdb} gen,games g WHERE g.id=gen.gid AND (gen.name LIKE ? OR gen.tags LIKE ?) ORDER BY g.feature DESC, g.plays_month DESC LIMIT {$set_limit}, {$per_page}");
$stmt->bindValue(1, "%$id%", PDO::PARAM_STR);
$stmt->bindValue(2, "%$id%", PDO::PARAM_STR);
$stmt->execute();
$content = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
$smarty->display('search_page.tpl');
?>