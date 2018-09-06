<?php
include('functions.php');
$smarty->caching = 0;
$id=preg_replace("/[^A-Za-z0-9-]/", "", $_GET['id']);
$smarty->assign('tag',$id,true);
$continue=true;
$gamesdb='games_'.$settings['lang'];
$tagsdb='tags_'.$settings['lang'];
$parentname=false;
$p=1;
if(isset($_GET['p'])){
$p=intval($_GET['p']);
}
if($id===''||!$id){$id='puzzle';}
$tid=0;
try {
$stmt = $db->prepare("SELECT t.id, t.pid, t.isparent, t.name,t.seodesc FROM {$tagsdb} t WHERE t.name =:id LIMIT 1");
$stmt->bindValue(':id', $id, PDO::PARAM_STR);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
$taginfo=$results[0];
} catch(PDOException $ex) {
if($smarty->debugging){
echo $ex->getMessage();
}
error_log($ex->getMessage());
}
if($taginfo){
$tid=intval($taginfo['id']);
}else{
$continue=false;
}
if(!$tid || $tid <1){
$continue=false;
}

if($continue){
$stmt = $db->prepare("SELECT tg.id FROM tags_games tg WHERE tg.tid = :tid");
$stmt->bindValue(':tid', $tid, PDO::PARAM_INT);
$stmt->execute();
$total_items = $stmt->rowCount();
if ((!$p) || (is_numeric($p) == false) || ($p < 0) || ($p > $total_items)){
$p = 1;
}

$per_page = 90;
$total_pages = ceil($total_items / $per_page);
$page_from=($p - 9 > 0 ? $p - 9 : 1);
$page_to=($p + 9 <= $total_pages ? $p + 9 : $total_pages);
$set_limit = $p * $per_page - ($per_page);

$smarty->assign('p',$p,true);
$smarty->assign('total_pages',$total_pages,true);
$smarty->assign('page_from',$page_from,true);
$smarty->assign('page_to',$page_to,true);
$smarty->assign('total_items',$total_items,false);

//Grab games
try {
$stmt = $db->prepare("SELECT g.id, g.thumb, g.has_achs,g.has_scores,gen.name, gen.url_key FROM {$gamesdb} gen,games g,tags_games tg WHERE g.id=tg.gid AND tg.tid = :tid AND gen.gid=g.id ORDER BY g.feature DESC, g.plays_month DESC LIMIT {$set_limit}, {$per_page}");
$stmt->bindValue(':tid', $tid, PDO::PARAM_INT);
$stmt->execute();
$content = $stmt->fetchAll(PDO::FETCH_ASSOC);
$smarty->assign('res',$content,true); 
} catch(PDOException $ex) {
if($smarty->debugging){
echo $ex->getMessage();
}
error_log($ex->getMessage());
}

}else{
$p = 1;
}

$realid = ucwords(strtr($id, '-', ' '));
//
//Edit SEO in the admin cp
//
$stmt = $db->query("SELECT id,name,value FROM settings_pages WHERE page='tags'");
$settings_page = $stmt->fetchAll(PDO::FETCH_ASSOC);
$settings_pg=array();
foreach($settings_page as $s){
$name=$s['name'];	
$settings_pg[$name]=$s['value'];
}

$find_var = array(":-tag:");
$replace_var = array($realid);
$title=str_replace($find_var,$replace_var,$settings_pg['title']);
$smarty->assign("title",$title,true);
$keywords=str_replace($find_var,$replace_var,$settings_pg['keywords']);
$smarty->assign("keywords",$keywords,true);

if($taginfo['seodesc']){
$seodesc=$taginfo['seodesc'];
}else{
$seodesc=str_replace($find_var,$replace_var,$settings_pg['desc']);
}
$smarty->assign('description',$seodesc,true);
$smarty->assign('game',false,true);
$smarty->assign('tag',$realid,true);
$smarty->assign('s',false,true);

$db=null;
$smarty->display('tag.tpl');
?>