<?php
include('../functions.php');
if(!$user || $user['id']<1){
header('Location: '.$settings['siteurl'].'/login');
die();
}else{
include('check.php');
}
require_once('../libs/bulletproof.php');
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
$type=$_GET['type'];
$gid=intval($_GET['id']);
$game;
$gamesdb='games_'.$settings['lang'];
$tagsdb='tags_'.$settings['lang'];
$error=false;
$msg=false;

if($type==='delete'){
	
	//delete
	$sql = "DELETE FROM games WHERE id = $gid";
	$db->exec($sql);
	
	//delete language
	$sql = "DELETE FROM $gamesdb WHERE gid = $gid";
	$db->exec($sql);
	
	//delete tags
	$sql = "DELETE FROM tags_games WHERE gid = $gid";
	$db->exec($sql);
	
	header('Location: '.$settings['siteurl'].'/'.$settings['adminfolder'].'/games.php?msg=delete');
	die();
}else{
	
//grab game info from feed
$stmt = $db->prepare("SELECT g.*,gen.name,gen.description,gen.instructions,gen.tags,gen.url_key FROM {$gamesdb} gen,games g WHERE g.id=:id AND g.id=gen.gid LIMIT 1");
$stmt->bindValue(':id', $gid, PDO::PARAM_INT);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
$game=$results[0];

$check_cat=explode(",",$game['tags']);
$catname=$check_cat[0];
$game['cat_id']=$catname;
unset($check_cat[0]);
$game['tags']=implode(',',$check_cat);

if($game['has_achs']==1){
try {
$stmt = $db->prepare("SELECT a.ach_id,a.id,a.points,a.name,a.textdesc FROM achievements a WHERE gid=:gid ORDER BY a.points ASC,a.id ASC LIMIT 12");
$stmt->bindValue(':gid', $gid, PDO::PARAM_INT);
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
$stmt = $db->prepare("SELECT h.id,h.name,h.score_order,h.board_id FROM highscore_boards h WHERE h.gid = :gid LIMIT 5");
$stmt->bindValue(':gid', $gid, PDO::PARAM_INT);
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

if($_POST){
$continue=true;
$game_name=$_POST['name'];
$game_tags = $_POST['tags'];
$game_description = $_POST['description'];
$game_instructions = $_POST['instructions'];
$cat_id=intval($_POST['cat_id']);
$swf = $_POST['embed'];
$cat_id=$_POST['cat_id'];

$ads=0;
$hasscores=0;
$feature=0;
$hasawards=0;

if(isset($_POST['hasscores'])){
$hasscores=intval($_POST['hasscores']);	
}
if(isset($_POST['feature'])){
$feature=intval($_POST['feature']);	
}
if(isset($_POST['hasawards'])){
$hasawards=intval($_POST['hasawards']);	
}
if(isset($_POST['ads'])){
$ads=intval($_POST['ads']);	
}

$thumb=$game['thumb'];

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

$newthumb=false;
if($continue){
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

if($continue){
//install
//insert sql
$gameid;

//update games
$stmt = $db->prepare("UPDATE games SET swf=:swf,thumb=:thumb,has_achs=:has_achs,has_scores=:has_scores,feature=:feature,ads=:ads WHERE id=:id");
$stmt->bindValue(':swf', $swf, PDO::PARAM_INT);
$stmt->bindValue(':thumb', $thumb, PDO::PARAM_INT);
$stmt->bindValue(':has_achs', $hasawards, PDO::PARAM_INT);
$stmt->bindValue(':has_scores', $hasscores, PDO::PARAM_INT);
$stmt->bindValue(':feature', $feature, PDO::PARAM_INT);
$stmt->bindValue(':ads', $ads, PDO::PARAM_INT);
$stmt->bindValue(':id', $gid, PDO::PARAM_INT);
$stmt->execute();

//$gamesdb='games_'.$lang;
//$tagsdb='tags_'.$lang;

$stmt = $db->prepare("UPDATE {$gamesdb} SET name=:name,tags=:tags,description=:description,instructions=:instructions WHERE gid=:id");
$stmt->bindValue(':name', $game_name, PDO::PARAM_INT);
$stmt->bindValue(':tags', $game_tags, PDO::PARAM_INT);
$stmt->bindValue(':description', $game_description, PDO::PARAM_INT);
$stmt->bindValue(':instructions', $game_instructions, PDO::PARAM_INT);
$stmt->bindValue(':id', $gid, PDO::PARAM_INT);
$stmt->execute();


if($hasscores){
foreach($_POST['scoreboard'] as $brd){
if($brd[0] && strlen($brd[0])>1){
	if($brd[2]){
		//update
		$stmt = $db->prepare("UPDATE highscore_boards SET name=:name,board_id=:board_id WHERE id=:id");
		$stmt->bindValue(':name', $brd[0], PDO::PARAM_INT);
		$stmt->bindValue(':board_id', $brd[1], PDO::PARAM_INT);
		$stmt->bindValue(':id', $brd[2], PDO::PARAM_INT);
		$stmt->execute();
	}else{
		//insert
		$stmt = $db->prepare("INSERT IGNORE INTO highscore_boards (name,board_id,gid) VALUES(:field1,:field2,:field3)");
		$stmt->execute(array(':field1' => $brd[1], ':field2' => $brd[0], ':field3' => $gid));
	}
}
}
}

if($hasawards){
	foreach($_POST['award'] as $ard){
		if($ard[0] && strlen($ard[0])>1){
			if($ard[4]){
				//update
				$stmt = $db->prepare("UPDATE achievements SET ach_id=:aid,points=:xp,name=:name,textdesc=:tdesc WHERE id=:id");
				$stmt->bindValue(':aid', $ard[0], PDO::PARAM_INT);
				$stmt->bindValue(':xp', $ard[2], PDO::PARAM_INT);
				$stmt->bindValue(':name', $ard[1], PDO::PARAM_INT);
				$stmt->bindValue(':tdesc', $ard[3], PDO::PARAM_INT);
				$stmt->bindValue(':id', $ard[4], PDO::PARAM_INT);
				$stmt->execute();
			}else{
				//insert
$stmt = $db->prepare("INSERT IGNORE INTO achievements (ach_id,points,name,textdesc,gid) VALUES(:field1,:field2,:field3,:field4,:field5)");
$stmt->execute(array(':field1' => $ard[0], ':field2' => $ard[2], ':field3' => $ard[1], ':field4' => $ard[3], ':field5' => $gid));
			}	
		}	
	}	
}


//break down tags
$tags_trans=array();
$tag_break=explode(",",$game_tags);

//
//update tags
//

$stmt = $db->prepare("SELECT t.name,t.id FROM {$tagsdb} t,tags_games tg WHERE tg.gid=:gid AND t.id=tg.tid ORDER BY tg.id ASC");
$stmt->bindValue(':gid', $gid, PDO::PARAM_STR);
$stmt->execute();
$oldtags = $stmt->fetchAll(PDO::FETCH_ASSOC);


foreach($tag_break as $tag){
	
//check if exists
$stmt = $db->prepare("SELECT id FROM {$tagsdb} WHERE name = :tag LIMIT 1");
$stmt->bindValue(':tag', $tag, PDO::PARAM_STR);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
$tager=$results[0];
if(!$tager['id'] || $tager['id']<1){
$stmt = $db->prepare("INSERT IGNORE INTO {$tagsdb} (name) VALUES(:field1)");
$stmt->execute(array(':field1' => $tag));
$tagid=$db->lastInsertId();

//
//insert into tags_games
//
$stmt = $db->prepare("INSERT IGNORE INTO tags_games (gid,tid) VALUES(:field1,:field2)");
$stmt->execute(array(':field1' => $gid,':field2' => $tagid));

continue;
}
	
$nomatch=true;
foreach($oldtags as $old){
if($old['name']==$tag){
$nomatch=false;
continue;
}	
}
if($nomatch){
//insert new tag
$tag_id=$tager['id'];
$stmt = $db->prepare("INSERT IGNORE INTO tags_games (gid,tid) VALUES(:field1,:field2)");
$stmt->execute(array(':field1' => $gid,':field2' => $tag_id));
}
}

foreach($oldtags as $old){
	if (in_array($old['name'], $tag_break)) {
		//skip
	}else{
		//delete
		$id=$old['id'];
		$sql = "DELETE FROM tags_games WHERE id = $id";
		$db->exec($sql);
	}
}

header('Location: '.$settings['siteurl'].'/'.$settings['adminfolder'].'/games.php?msg=edit');
die();
}
}
}

$smarty->assign('msg',$msg,true); 
$smarty->assign('errors',$error,true); 
$smarty->assign('game',$game,true);
$smarty->assign('page','games',true);
$smarty->display('games_edit.tpl');
?>
