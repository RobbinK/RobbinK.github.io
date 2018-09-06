<?php
require_once('../mysql.php');
$db = new PDO('mysql:host='.HOST.';dbname='.DATABASE.';charset=utf8mb4',USER,PASSWORD);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
require_once('includes/game_feeds.php');
require_once('includes/auto_install.php');
require_once('includes/admin_stats.php');
require_once('includes/check_refs.php');
require_once('includes/delete_expires.php');
require_once('includes/reset_plays.php');
require_once('includes/reset_plays.php');
require_once('../libs/Smarty.class.php');
$smarty = new Smarty();
$smarty->debugging = false;
$smarty->caching = 0;
require '../classes/phpmail/PHPMailerAutoload.php';
$mail = new PHPMailer;

//
// email verify = 5 minutes
// auto install/feeds = 30 minuts
// admin stats = 1 hour
// check_ref = 1 hour
// delete expires = 1 day
// reset plays = 1 month
//

$cron=false;

//00 = top of hour
$mins=date('i');

//00 = midnight
$hour=date('H');
$day=date('j');

if($mins==00 || $mins%5==0){
$admin_stats=new adminStats();
$admin_stats->db =& $db;
$admintry=$admin_stats->init();
if($admintry){
$cron=true;
}else{
error_log("Cron Job Error: Games not installed");
}
}

if($mins%30==0){
	//
	// installs new games/awards etc into feeds
	//
$game_feeds=new gameFeeds();
$game_feeds->db =& $db;
$installs=$game_feeds->init();
if($installs){
$cron=true;
}else{
error_log("Cron Job Error: Games not installed");
}	
}


if($mins==05||$mins==35){
	//
	// auto installs from game feed
	//
$game_feeds=new autoInstall();
$game_feeds->db =& $db;
$installs=$game_feeds->init();
if($installs){
$cron=true;
}else{
error_log("Cron Job Error: Auto install error");
}
}

if($mins===00){
$check_refs=new checkRefs();
$check_refs->db =& $db;
$refstry=$check_refs->init();
if($refstry){
$cron=true;
}else{
error_log("Cron Job Error: Games not installed");
}
}

if($hour==00 && $mins==00){
$delete_expires=new deleteExpires();
$delete_expires->db =& $db;
$deleteexpire=$delete_expires->init();
if($deleteexpire){
$cron=true;
}else{
error_log("Cron Job Error: Games not installed");
}
}

if($day==1 && $hour==00 && $mins==00){
$reset_plays=new resetPlays();
$reset_plays->db =& $db;
$resetplay=$reset_plays->attempt();
if($resetplay){
$cron=true;
}else{
error_log("Cron Job Error: Games not installed");
}
}

if($cron){
try {
$db->exec("UPDATE admin_cron SET lastupdate=CURRENT_TIMESTAMP WHERE id=1");
} catch(PDOException $ex) {
error_log($ex->getMessage());
}
}

$db=null;

?>