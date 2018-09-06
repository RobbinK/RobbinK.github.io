<?php
include('functions.php');

//
// Go to http://pub.lagged.com/contact for support, feature requests and bug reporting
//

//
//Build home page
//
$gamesdb='games_'.$settings['lang'];
$home_games=array();
$ignore=false;
$page='home';

//
//SEO page titles, descript etc. Edit in admin cp
//
$stmt = $db->query("SELECT id,name,value FROM settings_pages WHERE page='home'");
$settings_page = $stmt->fetchAll(PDO::FETCH_ASSOC);
$settings_pg=array();
foreach($settings_page as $s){
$name=$s['name'];	
$settings_pg[$name]=$s['value'];
}

$smarty->assign("title",$settings_pg['title'],true);
$smarty->assign("keywords",$settings_pg['keywords'],true);
$smarty->assign("seodesc",$settings_pg['desc'],true);

//
//get 6 new games
//
try {
$query="SELECT g.id, g.thumb, g.has_achs,g.has_scores, gen.name, gen.url_key FROM $gamesdb gen,games g WHERE gen.gid=g.id AND g.feature=1 ORDER BY g.id DESC LIMIT 6";
$stmt = $db->query($query);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

//
//Add a 'new' banner to icon
//
foreach($results as &$r){
	$r['isnew']=1;
	$ignore=$ignore.','.$r['id'];
}

} catch(PDOException $ex) {
if($smarty->debugging){
	echo $ex->getMessage();
}
	error_log($ex->getMessage());
}

//
//get popular games to fill home page
//
$ignore=trim($ignore,',');
try {
$query="SELECT g.id, g.thumb, g.has_achs,g.has_scores, gen.name, gen.url_key FROM $gamesdb gen,games g WHERE gen.gid=g.id AND g.feature=1 AND g.id NOT IN ($ignore) ORDER BY g.plays_month DESC, g.id DESC LIMIT 39";
$stmt = $db->query($query);
$results2 = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach($results2 as &$r){
	$r['isnew']=0;
}
} catch(PDOException $ex) {
if($smarty->debugging){
	echo $ex->getMessage();
}
	error_log($ex->getMessage());
}

if(count($results)>0 && count($results2)>0){
$home_games=array_merge($results, $results2);
}else{
$home_games=$results;	
}
$smarty->assign('games',$home_games);

//
//close database
//
$db=null;

//
//Tells header.tpl you are home
//
$smarty->assign('s',$page,true);
$smarty->assign('tag',false,true);
$smarty->assign('game',false,true);
$smarty->display('index.tpl');
?>
