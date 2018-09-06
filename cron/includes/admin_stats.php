<?php
class adminStats {
var $db;

public function init(){
$response=true;

$stmt = $this->db->query("SELECT sum(play_count) as totalplays FROM games");
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
$total_plays=intval($result[0]['totalplays']);

//subtract from total plays in admin_stats
$stmt = $this->db->query("SELECT sum(play_count) as totalplays FROM stats_plays");
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
$stat_plays=intval($result[0]['totalplays']);

$daily_plays=$total_plays-$stat_plays;
//insert into todays date (run this script at 11:45 PM)
$todays_date=date("Y-m-d");

$stmt = $this->db->prepare("SELECT play_count FROM stats_plays WHERE dayofweek=:day");
$stmt->bindValue(':day', $todays_date, PDO::PARAM_STR);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
$checkexist=false;
if($result){
$checkexist = $result[0];
}
if($checkexist){
$daily_plays=$daily_plays+intval($checkexist['play_count']);
try{
$stmt = $this->db->prepare("UPDATE stats_plays SET play_count=:plays WHERE dayofweek =:day");
$stmt->execute(array(':plays' => $daily_plays,':day'=>$todays_date));
} catch(PDOException $ex) {
error_log($ex->getMessage());
$response=false;
}
}else{
try{
$stmt = $this->db->prepare("INSERT IGNORE INTO stats_plays (play_count,dayofweek) VALUES(:field1,:field2)");
$stmt->execute(array(':field1' => $daily_plays, ':field2' => $todays_date));
} catch(PDOException $ex) {
error_log($ex->getMessage());
$response=false;
}
}

return $response;
}



}
?>