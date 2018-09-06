<?php
include('../functions.php');
if(!$user || $user['id']<1){
header('Location: '.$settings['siteurl'].'/login');
die();
}
$errors=false;

if($_POST){
if (empty($_POST['user_password'])) {
$errors='Please enter a valid password';
} 
if (!$errors) {
$email=$user['user_email'];
$stmt = $db->prepare("SELECT admin_password FROM users_admin,users WHERE users.user_email=:email AND users.id=users_admin.uid LIMIT 1");
$stmt->bindValue(':email', $email, PDO::PARAM_STR);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
$admin=$result[0];
if($admin){
if (password_verify($_POST['user_password'],$admin['admin_password'])) {
$_SESSION['isadmin']=1;
header('Location: '.$settings['siteurl'].'/'.$settings['adminfolder'].'/');
die();
}
}else{
$errors='Incorrect Password';
}
}
}
$smarty->assign('page','home',true);
$smarty->assign('errors',$errors,true);
$smarty->assign('messages',0,true);
$smarty->display('login.tpl');
?>
