<?php
include('functions.php');
$smarty->caching = 0;
$error=false;
$email=false;	
$subject=false;		
$message=false;
$msg=false;

//
//Edit SEO in the admin cp
//
$stmt = $db->query("SELECT id,name,value FROM settings_pages WHERE page='contact'");
$settings_page = $stmt->fetchAll(PDO::FETCH_ASSOC);
$settings_pg=array();
foreach($settings_page as $s){
$name=$s['name'];	
$settings_pg[$name]=$s['value'];
}

$smarty->assign("title",$settings_pg['title'],false);

if($_POST){
$go_with_the_flow = 1;
$email=strip_tags($_POST['email']);	
$subject=strip_tags($_POST['subject']);		
$message=strip_tags($_POST['message']);	

if(!$subject){
$error='Please enter a subject';
$go_with_the_flow = 0;
}

if(!$message){
$error='Please enter a message';
$go_with_the_flow = 0;	
}

if ( !preg_match('/^[_A-z0-9-]+((\.|\+)[_A-z0-9-]+)*@[A-z0-9-]+(\.[A-z0-9-]+)*(\.[A-z]{2,4})$/', $email) ){
$error='Please enter a valid email';
$go_with_the_flow = 0;
} elseif (!$email){
$error='Please enter a valid email';
$go_with_the_flow = 0; 
}

if($go_with_the_flow == 1){
$message = (strlen($message) > 500) ? substr($message, 0, 500) . '...' : $message;
$subject = (strlen($subject) > 120) ? substr($subject, 0, 120) . '...' : $subject;
$ip=$_SERVER['REMOTE_ADDR'];
$ip2=$_SERVER['HTTP_X_FORWARDED_FOR'];
$uid=intval($user['id']);
try {
$stmt = $db->prepare("INSERT INTO contact(email,title,message,ip,ip2,uid) VALUES(:email,:subject,:message,:ip,:ip2,:uid)");
$stmt->execute(array(':email' => $email, ':subject' => $subject, ':message' => $message, ':ip' => $ip, ':ip2' => $ip2, ':uid' => $uid));
$msg=true;
} catch(PDOException $ex) {
if($smarty->debugging){
echo $ex->getMessage();
}
error_log($ex->getMessage());
$error='An error occured';
}
}
}

$smarty->assign('msg',$msg,true);
$smarty->assign('email',$email,true);	
$smarty->assign('subject',$subject,true);	
$smarty->assign('message',$message,true);
$smarty->assign('errors',$error,true);
$smarty->assign('description',false,true);
$smarty->assign('keywords',false,true);
$smarty->assign('game',false,true);
$smarty->assign('tag',false,true);
$smarty->assign('s',false,true);
$smarty->display('contact.tpl');
?>