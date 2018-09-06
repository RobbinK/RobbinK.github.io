<?php
class resetPlays {
var $db;

public function attempt(){
$response=true;

	try {
	$this->db->exec("UPDATE games SET plays_month=0");
	} catch(PDOException $ex) {
	error_log($ex->getMessage());
	$response=false;
	}

return $response;
}

}
?>