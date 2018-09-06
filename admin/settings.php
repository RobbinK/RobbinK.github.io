<?php
include('../functions.php');
if(!$user || $user['id']<1){
header('Location: '.$settings['siteurl'].'/login');
die();
}else{
include('check.php');
}

$tab=false;
if(isset($_GET['view'])){
$tab=$_GET['view'];
}
if(!$tab||strlen($tab)<3){
$tab='home';
}
$msg=false;
$error=false;

//
//Save settings
//
if($_POST){
if($tab=='home'){
$siteurl=$_POST['siteurl'];
$siteurl = rtrim($siteurl,"/");
$sitename=$_POST['sitename'];
$sitename=preg_replace("/[^A-Za-z0-9 ]/", '', $sitename);
$adminfolder=$_POST['adminfolder'];
$imgcdn=$_POST['imgcdn'];
$imgcdn = rtrim($imgcdn,"/");
$analytics=$_POST['analytics'];
$twitter=$_POST['twitter'];
$facebook=$_POST['facebook'];
$veedi=$_POST['veedi'];
$privacy=strip_tags($_POST['privacy']);
try {
$stmt = $db->prepare("UPDATE settings SET result=:siteurl WHERE name='siteurl'");
$stmt->bindValue(':siteurl', $siteurl, PDO::PARAM_STR);
$stmt->execute();

$stmt = $db->prepare("UPDATE settings SET result=:sitename WHERE name='sitename'");
$stmt->bindValue(':sitename', $sitename, PDO::PARAM_STR);
$stmt->execute();

$stmt = $db->prepare("UPDATE settings SET result=:adminfolder WHERE name='adminfolder'");
$stmt->bindValue(':adminfolder', $adminfolder, PDO::PARAM_STR);
$stmt->execute();

$stmt = $db->prepare("UPDATE settings SET result=:imgcdn WHERE name='imgcdn'");
$stmt->bindValue(':imgcdn', $imgcdn, PDO::PARAM_STR);
$stmt->execute();

$stmt = $db->prepare("UPDATE settings SET result=:analytics WHERE name='analytics'");
$stmt->bindValue(':analytics', $analytics, PDO::PARAM_STR);
$stmt->execute();

$stmt = $db->prepare("UPDATE settings SET result=:twitter WHERE name='twitter'");
$stmt->bindValue(':twitter', $twitter, PDO::PARAM_STR);
$stmt->execute();

$stmt = $db->prepare("UPDATE settings SET result=:veedi WHERE name='veedi'");
$stmt->bindValue(':veedi', $veedi, PDO::PARAM_STR);
$stmt->execute();

$stmt = $db->prepare("UPDATE settings SET result=:facebook WHERE name='facebook'");
$stmt->bindValue(':facebook', $facebook, PDO::PARAM_STR);
$stmt->execute();

$stmt = $db->prepare("UPDATE settings SET result=:privacy WHERE name='privacy'");
$stmt->bindValue(':privacy', $privacy, PDO::PARAM_STR);
$stmt->execute();
} catch(PDOException $ex) {
$error="SQL error when updating.";
error_log($ex->getMessage());
}

if(!$error){
$msg="Settings Updated";
}
}else if($tab=='feeds'){
foreach($_POST['aff'] as $key=>$f){
try {
$stmt = $db->prepare("UPDATE admin_rev_share SET aff_id=:aff_id WHERE feed=:feed");
$stmt->bindValue(':aff_id', $f, PDO::PARAM_STR);
$stmt->bindValue(':feed', $key, PDO::PARAM_INT);
$stmt->execute();
} catch(PDOException $ex) {
$error="SQL error when updating.";
error_log($ex->getMessage());
}
}

$autoinstall=array();
if(!$_POST['auto']['lagged']){
$autoinstall['lagged']=0;	
}
if(!$_POST['auto']['spil']){
$autoinstall['spil']=0;	
}
if(!$_POST['auto']['poki']){
$autoinstall['poki']=0;	
}
if(!$_POST['auto']['trending']){
$autoinstall['trending']=0;	
}
if(!$_POST['auto']['cloud']){
$autoinstall['cloud']=0;	
}
if(!$_POST['auto']['famobi']){
$autoinstall['famobi']=0;	
}
if(!$_POST['auto']['soft']){
$autoinstall['soft']=0;	
}
if(!$_POST['auto']['gamepix']){
$autoinstall['gamepix']=0;	
}

foreach($autoinstall as $key=>$f){	
try {
$stmt = $db->prepare("UPDATE admin_autoinstall SET auto=:auto WHERE feedid=:id");
$stmt->bindValue(':auto', $f, PDO::PARAM_STR);
$stmt->bindValue(':id', $key, PDO::PARAM_INT);
$stmt->execute();
} catch(PDOException $ex) {
$error="SQL error when updating.";
error_log($ex->getMessage());
}
}

foreach($_POST['auto'] as $key=>$f){	
try {
$stmt = $db->prepare("UPDATE admin_autoinstall SET auto=:auto WHERE feedid=:id");
$stmt->bindValue(':auto', $f, PDO::PARAM_STR);
$stmt->bindValue(':id', $key, PDO::PARAM_INT);
$stmt->execute();
} catch(PDOException $ex) {
$error="SQL error when updating.";
error_log($ex->getMessage());
}
}

if(!$error){
$msg="Settings Updated";
}
}else if($tab=='ads'){

	$adcode=$_POST['adcode'];
	$pagelevel=$_POST['pagelevel'];
	$gamesid=$_POST['gamesid'];
	$channel=$_POST['channel'];
	
	try {
	$stmt = $db->prepare("UPDATE settings SET result=:adcode WHERE name='adcode'");
	$stmt->bindValue(':adcode', $adcode, PDO::PARAM_STR);
	$stmt->execute();

	$stmt = $db->prepare("UPDATE settings SET result=:pagelevel WHERE name='adsense-pagelevel'");
	$stmt->bindValue(':pagelevel', $pagelevel, PDO::PARAM_STR);
	$stmt->execute();
	
	$stmt = $db->prepare("UPDATE settings SET result=:gamesid WHERE name='adsense-games'");
	$stmt->bindValue(':gamesid', $gamesid, PDO::PARAM_STR);
	$stmt->execute();
	
	$stmt = $db->prepare("UPDATE settings SET result=:channel WHERE name='ad-games-channel'");
	$stmt->bindValue(':channel', $channel, PDO::PARAM_STR);
	$stmt->execute();
	} catch(PDOException $ex) {
	$error="SQL error when updating.";
	error_log($ex->getMessage());
	}
	
	if($_POST['autoads']&&$_POST['autoads']==='true'){
		$stmt = $db->prepare("UPDATE settings SET result=:channel WHERE name='autoads'");
		$stmt->bindValue(':channel', 'true', PDO::PARAM_STR);
		$stmt->execute();
		
		$db->exec("UPDATE games SET ads=1");
	}else{
		$stmt = $db->prepare("UPDATE settings SET result=:channel WHERE name='autoads'");
		$stmt->bindValue(':channel', 'false', PDO::PARAM_STR);
		$stmt->execute();
	}

if(!$error){
$msg="Settings Updated";
}
}else if($tab=='seo'){

foreach($_POST as $key=>$p){
	$pcc = explode("_", $key);
	$page=$pcc[0];
	$name=$pcc[1];
	$value=$p;
	
	try{
	$stmt = $db->prepare("UPDATE settings_pages SET value=:value WHERE page=:page AND name=:name");
	$stmt->bindValue(':value', $value, PDO::PARAM_STR);
	$stmt->bindValue(':page', $page, PDO::PARAM_STR);
	$stmt->bindValue(':name', $name, PDO::PARAM_STR);
	$stmt->execute();
	} catch(PDOException $ex) {
	$error="SQL error when updating.";
	error_log($ex->getMessage());
	break;
	}
}

if(!$error){
$msg="Settings Updated";
}
}else if($tab=='email'){
	$email_host=$_POST['email_host'];
	$email_address=$_POST['email_address'];
	$email_password=$_POST['email_password'];
	
	try {
	$stmt = $db->prepare("UPDATE settings SET result=:email_host WHERE name='email_host'");
	$stmt->bindValue(':email_host', $email_host, PDO::PARAM_STR);
	$stmt->execute();

	$stmt = $db->prepare("UPDATE settings SET result=:email_address WHERE name='email_address'");
	$stmt->bindValue(':email_address', $email_address, PDO::PARAM_STR);
	$stmt->execute();
	
	$stmt = $db->prepare("UPDATE settings SET result=:email_password WHERE name='email_password'");
	$stmt->bindValue(':email_password', $email_password, PDO::PARAM_STR);
	$stmt->execute();
	} catch(PDOException $ex) {
	$error="SQL error when updating.";
	error_log($ex->getMessage());
	}

if(!$error){
$msg="Settings Updated";
}
}else if($tab=='admin'){

	$password=$_POST['password'];
	$new_password=$_POST['new_password'];
$uid=$user['id'];

//check if current password is current
$email=$user['user_email'];
$stmt = $db->prepare("SELECT admin_password FROM users_admin,users WHERE users.user_email=:email AND users.id=users_admin.uid LIMIT 1");
$stmt->bindValue(':email', $email, PDO::PARAM_STR);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
$admin=$result[0];
if($admin){
if (password_verify($_POST['password'],$admin['admin_password'])) {
	session_regenerate_id(true);
	$user_password_hash = password_hash($new_password, PASSWORD_DEFAULT);
	$stmt = $db->prepare("UPDATE users_admin SET admin_password=:user_password_hash WHERE uid = :uid");
	$stmt->execute(array(':user_password_hash' => $user_password_hash,':uid'=>$uid));
}else{
$error="Current password is not correct";
}
}else{
$error="Current password is not correct";
}

if(!$error){
$msg="Password Updated";
}
}	
}

//
// Get all settings
//

//page settings
$stmt = $db->query("SELECT * FROM settings_pages ORDER BY id ASC LIMIT 100");
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
$psettings=array();
foreach($results as $r){
$value=$r['name'];
$page=$r['page'];
$psettings[$page][$value]=$r['value'];
}
$smarty->assign('pagesettings',$psettings,true);


//auto install settings
$stmt = $db->query("SELECT a.*,aff.aff_id FROM admin_autoinstall a LEFT JOIN admin_rev_share aff ON a.feedid=aff.feed ORDER BY a.orderid ASC LIMIT 15");
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
$smarty->assign('gamefeeds',$results,true);

if(isset($msg)){
$stmt = $db->query("SELECT name,result FROM settings");
$settings_pre = $stmt->fetchAll(PDO::FETCH_ASSOC);
$settings=array();
foreach($settings_pre as $s){
$name=$s['name'];	
$settings[$name]=$s['result'];
}
$smarty->assign("settings",$settings,false);
}

$smarty->assign('error',$error,true);
$smarty->assign('msg',$msg,true);
$smarty->assign('tab',$tab,true);
$smarty->assign('page','settings',true);
$smarty->display('settings.tpl');
?>