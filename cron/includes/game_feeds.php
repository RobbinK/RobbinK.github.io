<?php
class gameFeeds {
var $db;

public function init(){
	$response=true;

	$stmt = $this->db->query("SELECT result FROM settings WHERE name='lang' LIMIT 1");
	$lang_sett = $stmt->fetchAll(PDO::FETCH_ASSOC);
	$lang=$lang_sett[0]['result'];
	
	if(!$lang || $lang=='en'){
	$json_url="http://lagged.com/cron/feed.json";
	}else{
	$json_url="http://lagged.com/cron/".$lang."/feed.json";	
	}
	$feed=$this->geetfeed($json_url);

	if($feed && count($feed)>0){
	$games=$feed['games'];
	$videos=$feed['videos'];
	$scores=$feed['scores'];
	$awards=$feed['awards'];

	if(count($games)>0){
	$install_games=$this->buildInstall($games);
	if (!empty($install_games)) {
		$this->db->beginTransaction();
		$sql = 'INSERT IGNORE INTO feeds (feed_id,name,description,instructions,tags,swf,thumb,has_achs,has_scores,feature,feed_name,lagid,ads) VALUES ';
		$sql .= implode(', ', $install_games['query']);
		$stmt = $this->db->prepare($sql);
		try {
		$stmt->execute($install_games['data']);
		$this->db->commit();
		} catch (PDOException $e){
		error_log($e->getMessage());
		}
	}
	}

	//install feeds scores to feed
	if(count($scores)>0){
	$install_scores=$this->buildInstall($scores);
	if (!empty($install_scores)) {
		$this->db->beginTransaction();
		$sql = 'INSERT IGNORE INTO feeds_boards (lid,name,score_order,board_id) VALUES ';
		$sql .= implode(', ', $install_scores['query']);
		$stmt = $this->db->prepare($sql);
		try {
		$stmt->execute($install_scores['data']);
		$this->db->commit();
		} catch (PDOException $e){
		error_log($e->getMessage());
		}
	}
	}

	//install feeds awards to feed
	if(count($awards)>0){
	$install_awards=$this->buildInstall($awards);
	if (!empty($install_awards)) {
		$this->db->beginTransaction();
		$sql = 'INSERT IGNORE INTO feeds_achievements (lid,ach_id,points,name,textdesc) VALUES ';
		$sql .= implode(', ', $install_awards['query']);
		$stmt = $this->db->prepare($sql);
		try {
		$stmt->execute($install_awards['data']);
		$this->db->commit();
		} catch (PDOException $e){
		error_log($e->getMessage());
		}
	}
	}

	//install feeds videos to feed
	if(count($videos)>0){
	$install_videos=$this->buildInstall($videos);
	if (!empty($install_videos)) {
		$this->db->beginTransaction();
		$sql = 'INSERT IGNORE INTO feeds_videos (video_id,feed_id,lid,file) VALUES ';
		$sql .= implode(', ', $install_videos['query']);
		$stmt = $this->db->prepare($sql);
		try {
		$stmt->execute($install_videos['data']);
		$this->db->commit();
		} catch (PDOException $e){
		error_log($e->getMessage());
		}
	}
	}
	
	}

	return $response;
}

private function buildInstall($arr){
	$return=array();
	$return['query'] = array();
	$return['data'] = array();
	
	foreach($arr as $at) {
	$return['query'][]='(' . implode(', ', array_fill(0, count($at), '?')) . ')';;
	foreach($at as $r){
		$return['data'][]=$r;
	}
	}	
	return $return;
}

private function geetfeed($feed){
$response=true;
	
try {
 
	$json = file_get_contents($feed);
	$response = json_decode($json,true);

} catch (Exception $e) {
   error_log($e->getMessage());
$response=false;
}


return $response;
}
}
?>