<?php
require_once('../classes/admin.class.php');
$admin =new admin();
$admin->db =& $db;
$admin = $admin->sync();

if(!$admin || $admin['id']<1){
header('Location: '.$settings['siteurl'].'/'.$settings['adminfolder'].'/login.php');
die();
}

$stmt = $db->query("SELECT id FROM contact WHERE viewed=0");
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
$messages = $stmt->rowCount();
$smarty->assign('messages',$messages,true);

?>