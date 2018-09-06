<?php
include('functions.php');
$smarty->caching = 0;
$uid=intval($user['id']);
if(!$uid||$uid==0){
header('Location: '.$SiteUrl.'/login');
die();
}
$page=1;
$p=1;
if(isset($_GET['page'])){
$page=$_GET['page'];
}
if($page==0||$page>2){
$page=1;
}
if($page==1){
try {
$stmt = $db->prepare("SELECT u.id,u.username,u.regdate,u.xp,ul.id as level,ul.xpmax, ul.points FROM users u,users_levels ul WHERE u.id=:uid AND ul.points <= u.xp ORDER BY ul.points DESC LIMIT 1");
$stmt->bindValue(':uid', $uid, PDO::PARAM_INT);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
$profile=$results[0];
} catch(PDOException $ex) {
if($smarty->debugging){
echo $ex->getMessage();
}
error_log($ex->getMessage());
}
if(!$profile){
header('Location: '.$SiteUrl);
die();
}
if(($profile['xp']-$profile['points'])==0){
$profile['user_perc']=0;
}else{
$profile['user_perc'] = round((($profile['xp'] - $profile['points']) / ($profile['xpmax'] - $profile['points'])) * 100);
}
$profile['xpmax']=$profile['xpmax']-$profile['xp'];
$smarty->assign('profile',$profile,true);
try {
$stmt = $db->prepare("SELECT id FROM users_achievemet WHERE uid=:uid");
$stmt->bindValue(':uid', $uid, PDO::PARAM_INT);
$stmt->execute();
$award_count = $stmt->rowCount();
$smarty->assign('award_count',$award_count,true);
} catch(PDOException $ex) {
if($smarty->debugging){
echo $ex->getMessage();
}
error_log($ex->getMessage());
}
}

//
//Edit SEO in the admin cp
//
$stmt = $db->query("SELECT id,name,value FROM settings_pages WHERE page='welcome'");
$settings_page = $stmt->fetchAll(PDO::FETCH_ASSOC);
$settings_pg=array();
foreach($settings_page as $s){
$name=$s['name'];	
$settings_pg[$name]=$s['value'];
}

$smarty->assign("title",$settings_pg['title'],false);

$db=null;
$smarty->assign('page',$page,true);
$smarty->assign('p',$p,true);
$smarty->assign('description',false,true);
$smarty->assign('keywords',false,true);
$smarty->assign('game',false,true);
$smarty->assign('tag',false,true);
$smarty->assign('s',false,true);
$smarty->display('welcome.tpl');
?>