<?php
include('functions.php');
$smarty->caching = 0;
$gamesdb='games_'.$settings['lang'];

if(isset($_GET['s'])){
$s=$_GET['s'];
}else{
$s='popular';
}
if($s!='new'){
$s='popular';
}
switch ($s){
case 'new':
$sort = "id";
$s_name = "Recent Games";
$s='recent';
break;
default:
$sort = "g.feature DESC, g.plays_month";
$s_name = "Popular Games";
break;
}
$smarty->assign('s',$s,true);

$smarty->assign('s_name',$s_name,true);


//
// Get game count
//
$stmt = $db->query("SELECT * FROM games");
$total_items = $stmt->rowCount();

$p = 1;

$per_page = 45;
$total_pages = ceil($total_items / $per_page);
if($p>$total_pages){
$p=$total_pages;
}
$page_from=($p - 5 > 0 ? $p - 5 : 1);
$page_to=($p + 5 <= $total_pages ? $p + 5 : $total_pages);
$set_limit = $p * $per_page - ($per_page);
$smarty->assign('p',$p,true);
$smarty->assign('total_pages',$total_pages,true);
$smarty->assign('page_from',$page_from,true);
$smarty->assign('page_to',$page_to,true);

//
// Query games
//
try {
$stmt = $db->prepare("SELECT g.id, g.thumb, gen.name, g.has_achs,g.has_scores,gen.url_key FROM {$gamesdb} gen,games g WHERE gen.gid=g.id ORDER BY {$sort} DESC LIMIT {$set_limit}, {$per_page}");
$stmt->execute();
$content = $stmt->fetchAll(PDO::FETCH_ASSOC);
$smarty->assign('res',$content,true); 
} catch(PDOException $ex) {
if($smarty->debugging){
echo $ex->getMessage();
}
error_log($ex->getMessage());
}

//
//Edit SEO in the admin cp
//
$stmt = $db->query("SELECT id,name,value FROM settings_pages WHERE page='category'");
$settings_page = $stmt->fetchAll(PDO::FETCH_ASSOC);
$settings_pg=array();
foreach($settings_page as $se){
$name=$se['name'];	
$settings_pg[$name]=$se['value'];
}

$find_var = array(":-sort:", ":-page:");
$replace_var = array(ucfirst($s),$p);
$title=str_replace($find_var,$replace_var,$settings_pg['title']);
$keywords=str_replace($find_var,$replace_var,$settings_pg['keywords']);
$seodesc=str_replace($find_var,$replace_var,$settings_pg['desc']);
$smarty->assign("title",$title,true);	
$smarty->assign("keywords",$keywords,true);
$smarty->assign("seodesc",$seodesc,true);
$smarty->assign('game',false,true);
$smarty->assign('tag',false,true);

$db=null;
$smarty->display('games.tpl');
?>