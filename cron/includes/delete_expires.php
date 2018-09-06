<?php
class deleteExpires {
var $db;

public function init(){
	$response=true;

	$sql = "DELETE FROM users_pwrecovery WHERE expire < CURRENT_DATE()";
	$this->db->exec($sql);

	$sql = "DELETE FROM users_emailverify WHERE expires < CURRENT_DATE()";
	$this->db->exec($sql);

	$sql = "DELETE FROM users_auth_tokens WHERE expires < CURRENT_DATE()";
	$this->db->exec($sql);


	return $response;
}



}
?>