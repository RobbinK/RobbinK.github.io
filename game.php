<?php
include('functions.php');
$smarty->caching = 0;
$id=preg_replace("/[^A-Za-z0-9-]/", "", $_GET['id']);
$error=false;
$game=null;
$awards=null;
$gamesdb='games_'.$settings['lang'];
$boards=null;
$highscores=null;

try {
$stmt = $db->prepare("SELECT g.*,gen.name,gen.description,gen.instructions,gen.tags,gen.url_key,v.install_id,v.thumb as vidthumb FROM {$gamesdb} gen,games g LEFT JOIN videos v ON v.gid = g.id WHERE gen.url_key=:id AND g.id=gen.gid LIMIT 1");
$stmt->bindValue(':id', $id, PDO::PARAM_STR);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
$game=$results[0];
$smarty->assign('game',$game);
} catch(PDOException $ex) {
if($smarty->debugging){
echo $ex->getMessage();
}
error_log($ex->getMessage());
}

$gid = intval($game['id']);
$uid=intval($user['id']);
if($gid==0||!$game['name']){
$error=true;
}else{

$gtags=explode(',',$game['tags']);
if(count($gtags)>5){
$gtags=array_splice($gtags,0,5);
}
$smarty->assign('gtags',$gtags,true);

//
//Edit SEO in the admin cp
//
$stmt = $db->query("SELECT id,name,value FROM settings_pages WHERE page='game'");
$settings_page = $stmt->fetchAll(PDO::FETCH_ASSOC);
$settings_pg=array();
foreach($settings_page as $s){
$name=$s['name'];	
$settings_pg[$name]=$s['value'];
}

$find_var = array(":-gamename:",":-tag1:",":-tag2:");
$replace_var = array($game['name'],str_replace('-',' ', $gtags[0]),str_replace('-',' ', $gtags[1]));
$title=str_replace($find_var,$replace_var,$settings_pg['title']);
$keywords=str_replace($find_var,$replace_var,$settings_pg['keywords']);
$seodesc=str_replace($find_var,$replace_var,$settings_pg['desc']);
$smarty->assign("title",$title,true);
$smarty->assign("keywords",$keywords,true);
$smarty->assign("seodesc",$seodesc,true);


if($game['has_achs']==1){
try {
$stmt = $db->prepare("SELECT a.id,a.points,a.name,a.textdesc,ua.id as saved FROM achievements a LEFT JOIN users_achievemet ua ON a.ach_id = ua.aid AND ua.uid=:uid WHERE gid=:gid ORDER BY a.points ASC,a.id ASC LIMIT 12");
$stmt->bindValue(':gid', $gid, PDO::PARAM_INT);
$stmt->bindValue(':uid', $uid, PDO::PARAM_INT);
$stmt->execute();
$awards = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $ex) {
if($smarty->debugging){
echo $ex->getMessage();
}
error_log($ex->getMessage());
}
}

if($game['has_scores']==1){	
try {
$stmt = $db->prepare("SELECT h.id,h.name,h.score_order,h.board_id FROM highscore_boards h WHERE h.gid = :gid LIMIT 5");
$stmt->bindValue(':gid', $gid, PDO::PARAM_INT);
$stmt->execute();
$boards = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $ex) {
if($smarty->debugging){
echo $ex->getMessage();
}
error_log($ex->getMessage());
}

$sort='DESC';
$minmax="max(s.score)";
$highscores=array();
foreach($boards as $board){
if($board['score_order']==0 || !$board['score_order']){
$sort='ASC';
$minmax="min(s.score)";	
}
$board=$board['board_id'];
try {
$stmt = $db->prepare("SELECT s.id,s.uid,{$minmax} as scores,s.timestamped,u.username,u.avatar FROM users_scores s,users u WHERE s.board_id = :board AND u.id=s.uid GROUP BY s.uid ORDER BY scores {$sort},s.id ASC LIMIT 10");
$stmt->bindValue(':board', $board, PDO::PARAM_INT);
$stmt->execute();
$scores = $stmt->fetchAll(PDO::FETCH_ASSOC);
$rank=1;
$i=1;
$prevscore=0;
foreach($scores as &$s){
if($s['scores']==$prevscore){
$s['rank']=$rank;	
}else{
$prevscore=$s['scores'];
$rank=$i;
$s['rank']=$rank;
}
$i++;	
}
$highscores[]=$scores;
} catch(PDOException $ex) {
if($smarty->debugging){
echo $ex->getMessage();
}
error_log($ex->getMessage());
}
}
}

$tags = $game['tags'];
try {
$quoted_search_text = $db->quote($tags);
$stmt = $db->prepare("SELECT g.id, g.thumb,  g.has_achs,g.has_scores, gen.name, gen.url_key, MATCH (gen.tags) AGAINST ($quoted_search_text) AS score FROM games g,{$gamesdb} gen WHERE MATCH (gen.tags) AGAINST ($quoted_search_text in boolean mode) AND g.id != :gid AND gen.gid=g.id ORDER BY feature DESC,score DESC, play_count DESC LIMIT 18");
$stmt->bindValue(':gid', $gid, PDO::PARAM_INT);
$stmt->execute();
$related = $stmt->fetchAll(PDO::FETCH_ASSOC);
$smarty->assign('related',$related,true);
} catch(PDOException $ex) {
if($smarty->debugging){
echo $ex->getMessage();
}
error_log($ex->getMessage());
}

}
$db=null;
$smarty->assign('awards',$awards,true);
$smarty->assign('boards',$boards,true);
$smarty->assign('highscores',$highscores,true);
$smarty->assign('error',$error);
$smarty->assign('s',false);
$smarty->display('game.tpl');
?>