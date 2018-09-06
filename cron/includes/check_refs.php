<?php
class checkRefs {
var $db;

public function init(){
$response=true;

//
//recommended to run once every 60 minutes
//

//give points to users for valid referrels
//select ids fromusers_ref  checked=0
$stmt = $this->db->query('SELECT ref.id,ref.uid,ref.refby_uid,u.ipaddress as uipa FROM users u,users_ref ref WHERE ref.checked=0 AND u.id=ref.uid LIMIT 25');
$refs = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach($refs as $r){
$rid=intval($r['id']);
$ruid=intval($r['uid']);
$refby=intval($r['refby_uid']);
$rip=$r['uipa'];
//get ref by user info	
$stmt = $this->db->prepare("SELECT ipaddress FROM users WHERE id=:refby LIMIT 1");
$stmt->bindValue(':refby', $refby, PDO::PARAM_STR);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
$refuser = $result[0];

//check ip address
if($refuser['ipaddress']!=$rip){
//check ref count (if less then 5 give 25 points)
$stmt = $this->db->prepare("SELECT ref.id FROM users_ref ref WHERE ref.refby_uid=:refby AND checked=1");
$stmt->bindValue(':refby', $refby, PDO::PARAM_INT);
$stmt->execute();
$counter = $stmt->rowCount();
if($counter<5){
//give user 25 xp
$stmt = $this->db->prepare("UPDATE users SET xp=xp+25 WHERE id = :refby");
$stmt->bindValue(':refby', $refby, PDO::PARAM_INT);
$stmt->execute();
}
}
//add checked to users_ref
$stmt = $this->db->prepare("UPDATE users_ref SET checked=1 WHERE id = :rid");
$stmt->bindValue(':rid', $rid, PDO::PARAM_INT);
$stmt->execute();
}


return $response;
}
}
?>