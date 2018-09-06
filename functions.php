<?PHP
require_once('mysql.php');
$db = new PDO('mysql:host='.HOST.';dbname='.DATABASE.';charset=utf8mb4',USER,PASSWORD);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
require_once("libs/password_compatibility_library.php");
require_once("libs/Smarty.class.php");
$smarty = new Smarty();
$smarty->compile_check = true;
$smarty->debugging = false;
$smarty->caching = 0;

//
//Edit settings in the admin panel
//
$stmt = $db->query("SELECT name,result FROM settings");
$settings_pre = $stmt->fetchAll(PDO::FETCH_ASSOC);
$settings=array();
foreach($settings_pre as $s){
$name=$s['name'];	
$settings[$name]=$s['result'];
}
$smarty->assign("settings",$settings,false);
$smarty->assign("SiteUrl",$settings['siteurl'],false);
$smarty->assign("imgCDN",$settings['imgcdn'],false);

//
// Get categories for menu
//
$tagsdb='tags_'.$settings['lang'];
$stmt = $db->query("SELECT t.id,t.name FROM {$tagsdb} t WHERE t.isparent = 1 ORDER BY t.name ASC LIMIT 25");
$categoriesloop = $stmt->fetchAll(PDO::FETCH_ASSOC);
$smarty->assign("categoriesloop",$categoriesloop,false);

//
// Build user
//
require_once("classes/login.class.php");
$login = new Login();
require_once('classes/u.class.php');
$u =new u();
$u->db =& $db;
$user = $u->sync();

//
// Check if user was referred
//
if($user['id']==0){
if(isset($_GET['ref'])){
$ref=intval($_GET['ref']);	
$_SESSION['ref']=$ref;
}

//
// Count guest achievements
//
$ach_count=0;
if(isset($_SESSION['awards'])){
$ach_count=count(explode(",",$_SESSION['awards']));
}
$smarty->assign("ach_count", $ach_count, true);
}

$smarty->assign("user", $user, true);

?>