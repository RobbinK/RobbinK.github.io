<?php
class autoInstall {
var $db;

public function init(){
	$unwanted_array = array('Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E','Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U','Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c','è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o','ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y' );
	
	$response=true;
	
	$stmt = $this->db->query("SELECT result FROM settings WHERE name='lang' LIMIT 1");
	$lang_sett = $stmt->fetchAll(PDO::FETCH_ASSOC);
	$lang=$lang_sett[0]['result'];
	
	$stmt = $this->db->query("SELECT result FROM settings WHERE name='autoads' LIMIT 1");
	$autoadsgb = $stmt->fetchAll(PDO::FETCH_ASSOC);
	$autoads=$autoadsgb[0]['result'];
	$auto_ads = ($autoads === 'true');
	
	$gamesdb='games_'.$lang;
	$tagsdb='tags_'.$lang;
	
	//auto install from feed
	//get settings here, with aff codes
	$stmt = $this->db->query("SELECT aa.feedid,ars.aff_id FROM admin_autoinstall aa LEFT JOIN admin_rev_share ars ON ars.feed=aa.feedid WHERE aa.auto=1");
	$aff_settings = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
	//array with AFF
	//generate (FEEDS,LIST)
	$feed_list="";
	$aff_codes=array();
	$replace_affs=array();
	$replace_affs['poki']=302600;
	$replace_affs['cloud']=55;
	$replace_affs['famobi']="A-0D1OG";
	$replace_affs['soft']="pub-12406-12406";
	$replace_affs['gamepix']=30059;
	
	foreach($aff_settings as $as){
		$feed=$as['feedid'];
		if($as['aff_id']){
		$aff_codes[$feed]=$as['aff_id'];
		}
		$feed_list=$feed_list.",".$feed;
	}
	
	
	
	//get games from feed where installed=0 and feed_name IN (FEEDS,LIST)
	// $feed_list=trim($feed_list,',');
	// $query="SELECT id,swf,name,description,instructions,thumb,tags,feed_id,lagid,has_achs,has_scores,feature,ads,feed_name FROM feeds WHERE installed=1 AND feed_name IN ($feed_list) ORDER BY id ASC LIMIT 25";
	// $stmt = $this->db->query($query);
	// $games = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
	$feed_list=trim($feed_list,',');
	$ach_values=explode(',',$feed_list);
	$sql = 'SELECT id,swf,name,description,instructions,thumb,tags,feed_id,lagid,has_achs,has_scores,feature,ads,feed_name FROM feeds WHERE installed=0 AND feed_name IN ('.str_pad('',count($ach_values)*2-1,'?,').')  ORDER BY id ASC LIMIT 25';
	$stmt = $this->db->prepare($sql);
	$stmt->execute($ach_values);
	$games = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
	
	//must install each game seperately
	foreach($games as $g){
		$swf=$g['swf'];
		$name=$g['name'];
		$description=$g['description'];
		$instructions=$g['instructions'];
		$thumb_url=$g['thumb'];
		$thumb=str_replace('http://lagged.com/images/feed/crop/','',$thumb_url);
		$tags=$g['tags'];
		$feed_id=$g['feed_id'];
		$lagid=$g['lagid'];
		$has_achs=$g['has_achs'];
		$has_scores=$g['has_scores'];
		$feature=$g['feature'];
		$ffid=$g['id'];
		$ads=$g['ads'];
		if($auto_ads){
			$ads=1;
		}
		
		//replace swf links with correct AFF CODE
		$feed=$g['feed_name'];
		if(!empty($aff_codes[$feed])){
			$affcode=$aff_codes[$feed];
			$swf=str_replace('/'.$replace_affs[$feed].'/','/'.$aff_codes[$feed].'/',$swf);
		}
		
		if($lang!='en'){
			$swf=substr($swf, 0, -2);
			$swf=$swf.$lang;
		}
		
		//install into games
		$url_key=preg_replace("/[^[:alnum:][:space:]]/u", '', $name);
		$url_key=strtolower(str_replace(" ", "-",$url_key));
		$url_key=trim(strtr($url_key,$unwanted_array));
		$url_key=trim($this->myUrlEncode($url_key));
		$url_key=str_replace(" ", "-",$url_key);
		$url_key=str_replace(":", "",$url_key);
		$url_key=str_replace("'", "",$url_key);
		//check url key
		$stmt = $this->db->prepare("SELECT url_key FROM {$gamesdb} WHERE url_key = :url_key LIMIT 1");
		$stmt->bindValue(':url_key', $url_key, PDO::PARAM_STR);
		$stmt->execute();
		$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
		if($results){
		$urlcheck=$results[0];
		if($urlcheck['url_key']){	
		$url_key = $url_key.rand(10,999);
		}
		}
		
		$stmt = $this->db->prepare("INSERT IGNORE INTO games (swf,thumb,has_achs,has_scores,feature,install_id,ads) VALUES(:field1,:field2,:field3,:field4,:field5,:field6,:field7)");
		$stmt->execute(array(':field1' => $swf, ':field2' => $thumb, ':field3' => $has_achs, ':field4' => $has_scores, ':field5' => $feature, ':field6' => $feed_id, ':field7' => $ads));
		$real_game_id=$this->db->lastInsertId();

		//install into $gamesdb
		$stmt = $this->db->prepare("INSERT IGNORE INTO {$gamesdb} (gid,name,tags,description,instructions,url_key) VALUES(:field1,:field2,:field3,:field4,:field5,:field6)");
		$stmt->execute(array(':field1' => $real_game_id, ':field2' => $name, ':field3' => $tags, ':field4' => $description, ':field5' => $instructions, ':field6' => $url_key));

		//add tags to tag_games $tagsdb
		//break down tags
		$tag_break=explode(",",$tags);
		foreach($tag_break as $t){
		$stmt = $this->db->prepare("SELECT id FROM {$tagsdb} WHERE name = :tag LIMIT 1");
		$stmt->bindValue(':tag', $t, PDO::PARAM_STR);
		$stmt->execute();
		$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$tag=$results[0];
		if(!$tag['id'] || $tag['id']<1){
		$stmt = $this->db->prepare("INSERT IGNORE INTO {$tagsdb} (name) VALUES(:field1)");
		$stmt->execute(array(':field1' => $t));
		$tagid=$this->db->lastInsertId();
		}else{
		$tagid=$tag['id'];
		}
		$stmt = $this->db->prepare("INSERT IGNORE INTO tags_games (gid,tid) VALUES(:field1,:field2)");
		$stmt->execute(array(':field1' => $real_game_id,':field2' => $tagid));
		}

		//if has_scores or has_awards install with correct GID
		//insert select on feed_id
		if($has_achs){
			$query="INSERT IGNORE INTO achievements (gid,ach_id,points,name,textdesc) SELECT $real_game_id,ach_id,points,name,textdesc FROM feeds_achievements WHERE lid=$lagid";
			$this->db->exec($query);	
		}
		
		if($has_scores){
			$query="INSERT IGNORE INTO highscore_boards (gid,name,score_order,board_id) SELECT $real_game_id,name,score_order,board_id FROM feeds_boards WHERE lid=$lagid";
			$this->db->exec($query);
		}


		//update installed=1
		$stmt = $this->db->prepare("UPDATE feeds SET installed=1 WHERE id=:ffid");
		$stmt->execute(array(':ffid' => $ffid));
		
		//copy thumb to server
		//cmd to root
		chdir("../thumbs");
		//echo getcwd();
		copy($thumb_url,$thumb);
		
	}
	
	//check for new videos
	$query="INSERT IGNORE INTO videos (gid,install_id,thumb) SELECT g.id, v.video_id,v.file FROM feeds_videos v, games g WHERE v.installed=0 AND v.feed_id=g.install_id";
	$this->db->exec($query);
	$this->db->exec("UPDATE feeds_videos SET installed=1");


	return $response;
}

private function generateRandomString($length = 3) {
$characters = '0123456789abcdefghijklmnopqrstuvwxyz';
$charactersLength = strlen($characters);
$randomString = '';
for ($i = 0; $i < $length; $i++) {
$randomString .= $characters[rand(0, $charactersLength - 1)];
}
return $randomString;
}
private function myUrlEncode($s) {
$result = str_replace(array('"', "'"), '', $s);
return $result;
}

}
?>