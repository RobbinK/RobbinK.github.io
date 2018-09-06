<?php
include('functions.php');
$cache_id='sitemap';
$smarty->setCaching(Smarty::CACHING_LIFETIME_SAVED);
$smarty->setCacheLifetime(300);
$smarty->compile_check = true;
header('Content-Type: text/html; charset=utf-8');
//check cache
$gamesdb='games_'.$settings['lang'];
$tagsdb='tags_'.$settings['lang'];
if(!$smarty->isCached('sitemap.tpl',$cache_id)) {
try {
$stmt = $db->query("SELECT gen.url_key FROM {$gamesdb} gen ORDER BY gen.id ASC");
$content = $stmt->fetchAll(PDO::FETCH_ASSOC);
$smarty->assign('res',$content);
} catch(PDOException $ex) {
if($smarty->debugging){
echo $ex->getMessage();
}
error_log($ex->getMessage());
}
try {
$stmt = $db->query("SELECT name FROM {$tagsdb} ORDER BY id ASC");
$content2 = $stmt->fetchAll(PDO::FETCH_ASSOC);
$smarty->assign('tags',$content2);
} catch(PDOException $ex) {
if($smarty->debugging){
echo $ex->getMessage();
}
error_log($ex->getMessage());
}
try {
$stmt = $db->query("SELECT id FROM users ORDER BY id ASC");
$content3 = $stmt->fetchAll(PDO::FETCH_ASSOC);
$smarty->assign('users',$content3);
} catch(PDOException $ex) {
if($smarty->debugging){
echo $ex->getMessage();
}
error_log($ex->getMessage());
}
}
$db=null;
$smarty->display('sitemap.tpl',$cache_id);
?>
