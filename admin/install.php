<?php
include('../functions.php');
if(!$user || $user['id']<1){
header('Location: '.$settings['siteurl'].'/login');
die();
}else{
include('check.php');
}
require_once('../libs/bulletproof.php');

$gamesdb='games_'.$settings['lang'];
$tagsdb='tags_'.$settings['lang'];
$error=false;
$msg=false;
$game=false;
$source=false;

function generateRandomString($length = 3) {
$characters = '0123456789abcdefghijklmnopqrstuvwxyz';
$charactersLength = strlen($characters);
$randomString = '';
for ($i = 0; $i < $length; $i++) {
$randomString .= $characters[rand(0, $charactersLength - 1)];
}
return $randomString;
}
function myUrlEncode($s) {
$result = str_replace(array('"', "'"), '', $s);
return $result;
}
$unwanted_array = array('Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E','Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U','Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c','è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o','ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y' );

//check source + id
//check if already installed and give warning
if(isset($_GET['source'])){
$source=$_GET['source'];	
}
if(isset($_GET['id'])){
$gid=intval($_GET['id']);	
}

$game;

//grab game info from feed
if($source=='feed'&&$gid>0){
$stmt = $db->prepare("SELECT * FROM feeds WHERE id=:id LIMIT 1");
$stmt->bindValue(':id', $gid, PDO::PARAM_INT);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
$game=$results[0];
$lid=$game['lagid'];

$check_cat=explode(",",$game['tags']);
$catname=$check_cat[0];
$game['cat_id']=$catname;
unset($check_cat[0]);
$game['tags']=implode(',',$check_cat);

if($game['has_achs']==1){
try {
$stmt = $db->prepare("SELECT a.ach_id,a.id,a.points,a.name,a.textdesc FROM feeds_achievements a WHERE lid=:lid ORDER BY a.points ASC,a.id ASC LIMIT 12");
$stmt->bindValue(':lid', $lid, PDO::PARAM_INT);
$stmt->execute();
$awards = $stmt->fetchAll(PDO::FETCH_ASSOC);
$smarty->assign('awards',$awards,true);
} catch(PDOException $ex) {
if($smarty->debugging){
echo $ex->getMessage();
}
error_log($ex->getMessage());
}
}

if($game['has_scores']==1){	
try {
$stmt = $db->prepare("SELECT h.id,h.name,h.score_order,h.board_id FROM feeds_boards h WHERE h.lid = :lid LIMIT 5");
$stmt->bindValue(':lid', $lid, PDO::PARAM_INT);
$stmt->execute();
$boards = $stmt->fetchAll(PDO::FETCH_ASSOC);
$smarty->assign('boards',$boards,true);
} catch(PDOException $ex) {
if($smarty->debugging){
echo $ex->getMessage();
}
error_log($ex->getMessage());
}
}
}

if($_POST){
$continue=true;
$game_name=$_POST['name'];
$game_tags = $_POST['tags'];
$game_description = $_POST['description'];
$game_instructions = $_POST['instructions'];
$swf = $_POST['embed'];
$feed = $_POST['feed'];
$hasscores=intval($_POST['hasscores']);
$hasawards=intval($_POST['hasawards']);
$feature=intval($_POST['feature']);
$ads=intval($_POST['ads']);
$cat_id=$_POST['cat_id'];

$thumb;

$checktags=array();
$checktags[]=$cat_id;
foreach(explode(',',$game_tags) as $t){
$tag=strtolower(trim($t));
$tag=trim(str_replace('-',' ',$tag));
$tag=preg_replace("/[^A-Za-z0-9 ]/", "", $tag);
$tag=trim(str_replace(' games','',$tag));
$tag=trim(str_replace(' ','-',$tag));
$tag=trim(str_replace('-games','',$tag));
$checktags[]=$tag;
}
if(count($checktags)>6){
$checktags=array_splice($checktags,0,6);
}
$checktags=array_unique($checktags);
$game_tags=implode($checktags,",");

if(!$game_name){
$error='Please enter a game name';
$continue=false;
}
if(strlen($game_name) < 2 OR strlen($name) > 128){
$error='Name must be between 2-128 characters.';
$continue=false;
}

if($continue){
if($source=='feed'&&$gid>0){
	//copy thumb to server
	$thumurl=$game['thumb'];
	$filePath = pathinfo($thumurl);
	$thumb=$filePath['basename'];
	copy($thumurl,'../thumbs/'.$thumb);
}else{
$bulletProof = new ImageUploader\BulletProof;

try{	
if($_FILES && $_FILES['avaimage']['name']){
$name3=strtolower($game_name);
$name3=str_replace(' ', '-',$name3);
$thumbnail=$name3.'-'.generateRandomString();
$pic1=$bulletProof->uploadDir("../thumbs")->shrink(array("height"=>200, "width"=>200))->upload($_FILES['avaimage'],$thumbnail);
if($pic1){
$thumb=$pic1;
}
}
}catch(\ImageUploader\ImageUploaderException $e){
 $error=$e->getMessage();
$continue=false;
}
}
}

if($continue){

//install
//insert sql
$gameid;

$url_key=strtolower(str_replace(" ", "-",$game_name));
$url_key=trim(strtr($url_key,$unwanted_array));
$url_key=trim(myUrlEncode($url_key));
$url_key=str_replace(" ", "-",$url_key);
$url_key=str_replace(":", "",$url_key);
$url_key=str_replace("'", "",$url_key);
//check url key
$stmt = $db->prepare("SELECT url_key FROM {$gamesdb} WHERE url_key = :url_key LIMIT 1");
$stmt->bindValue(':url_key', $url_key, PDO::PARAM_STR);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
$urlcheck=$results[0];
if($urlcheck['url_key']){
$url_key = $url_key.rand(10,999);
}

$stmt = $db->prepare("INSERT IGNORE INTO games (swf,thumb,has_achs,has_scores,feature,ads) VALUES(:field1,:field2,:field3,:field4,:field5,:field6)");
$stmt->execute(array(':field1' => $swf, ':field2' => $thumb, ':field3' => $hasawards, ':field4' => $hasscores, ':field5' => $feature, ':field6' => $ads));
$real_game_id=$db->lastInsertId();

$stmt = $db->prepare("INSERT IGNORE INTO {$gamesdb} (gid,name,tags,description,instructions,url_key) VALUES(:field1,:field2,:field3,:field4,:field5,:field6)");
$stmt->execute(array(':field1' => $real_game_id, ':field2' => $game_name, ':field3' => $game_tags, ':field4' => $game_description, ':field5' => $game_instructions, ':field6' => $url_key));

if($hasscores){
foreach($_POST['scoreboard'] as $brd){
	//insert boards
if($brd[0] && strlen($brd[0])>1){
	$stmt = $db->prepare("INSERT IGNORE INTO highscore_boards (name,board_id,gid) VALUES(:field1,:field2,:field3)");
	$stmt->execute(array(':field1' => $brd[1], ':field2' => $brd[0], ':field3' => $real_game_id));
}
}
}

if($hasawards){
	foreach($_POST['award'] as $ard){
		if($ard[0] && strlen($ard[0])>1){
		//insert boards
		$stmt = $db->prepare("INSERT IGNORE INTO achievements (ach_id,points,name,textdesc,gid) VALUES(:field1,:field2,:field3,:field4,:field5)");
		$stmt->execute(array(':field1' => $ard[0], ':field2' => $ard[2], ':field3' => $ard[1], ':field4' => $ard[3], ':field5' => $real_game_id));	
		}	
	}	
}

//break down tags
$tag_break=explode(",",$game_tags);

foreach($tag_break as $t){
$stmt = $db->prepare("SELECT id FROM {$tagsdb} WHERE name = :tag LIMIT 1");
$stmt->bindValue(':tag', $t, PDO::PARAM_STR);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
$tag=$results[0];
if(!$tag['id'] || $tag['id']<1){
$stmt = $db->prepare("INSERT IGNORE INTO {$tagsdb} (name) VALUES(:field1)");
$stmt->execute(array(':field1' => $t));
$tagid=$db->lastInsertId();
}else{
$tagid=$tag['id'];
}
$stmt = $db->prepare("INSERT IGNORE INTO tags_games (gid,tid) VALUES(:field1,:field2)");
$stmt->execute(array(':field1' => $real_game_id,':field2' => $tagid));
}

if($source=='feed'&&$gid>0){
$stmt = $db->prepare("UPDATE feeds SET installed=1 WHERE id=:gid");
$stmt->execute(array(':gid' => $gid));
}


$msg='Success! This game has been installed.';
$smarty->assign('gamelink',$url_key,true);
$smarty->assign('game_name',$game_name,true);
}
}

$smarty->assign('errors',$error,true);	
$smarty->assign('msg',$msg,true);
$smarty->assign('source',$source,true);
$smarty->assign('game',$game,true);
$smarty->assign('page','games',true);
$smarty->display('install.tpl');
?>
