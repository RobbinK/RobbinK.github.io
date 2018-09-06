<?php
include('functions.php');
$smarty->caching = 0;
$page=1;
if(isset($_GET['page'])){
$page=$_GET['page'];
}
$key=null;
if(isset($_GET['key'])){
$key=$_GET['key'];
}
$title="Help!";
$error=false;
$errorbad=false;
$success=false;

if($page=='password'){
$title="Reset Password";
$continue=false;
if($key!='recover'){
$key='password';
}

if($key=='password'){
if($_POST){
$email=$_POST['email'];
$continue=true;	
//validate email
if ( !preg_match('/^[_A-z0-9-]+((\.|\+)[_A-z0-9-]+)*@[A-z0-9-]+(\.[A-z0-9-]+)*(\.[A-z]{2,4})$/', $email) ){
$error='Please enter a valid email';
$continue=false;
}

if($continue){
//check if email exists
try {
$stmt = $db->prepare("SELECT id,user_email,username FROM users WHERE user_email = :email LIMIT 1");
$stmt->bindValue(':email', $email, PDO::PARAM_STR);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
$content=$results[0];
} catch(PDOException $ex) {
if($smarty->debugging){
echo $ex->getMessage();
}
error_log($ex->getMessage());
}

if ( !$content['user_email'] ){
$error="Email does not exist";
$continue=false;
}else{
$id=$content['id'];
$name=$content['username'];
$continue=true;	
}
}

if(!$settings['email_host'] || !$settings['email_address']){
	$error="Email not set up. Please contact us!";
	$continue=false;
}

if($continue){
define(PW_SALT,'(+gdb4s#df');
$expFormat = mktime(date("H"), date("i"), date("s"), date("m")  , date("d")+1, date("Y"));
$expDate = date("Y-m-d H:i:s",$expFormat);
$key = md5($id . '_' . $email . rand(10,19000) .$expDate . PW_SALT);
$_SESSION['resetkey']=substr($key, -7);
$stmt = $db->prepare("INSERT IGNORE INTO users_pwrecovery (uid,keyme,expire) VALUES(:field1,:field2,:field3)");
$stmt->execute(array(':field1' => $id, ':field2' => $key, ':field3' => $expDate));

//send email
require 'classes/phpmail/PHPMailerAutoload.php';

$passwordLink = "<a href=\"".$settings['siteurl']."/help/password/?key=recover&email=" . $key . "\">".$settings['siteurl']."/help/password/?key=recover&email=" . $key."</a>";
$message = "Dear $name,<br/>";
$message .= "Please visit the following link to reset your password:<br/><br/>";
$message .= "$passwordLink<br/><br/>";
$message .= "Please be sure to copy the entire link into your browser. The link will expire after 1 day for security reasons.\r\n\r\n";
$message .= "If you did not request this forgotten password email, no action is needed, your password will not be reset as long as the link above is not visited.<br/><br/>";
$message .= "Thank you";

$smarty->assign("key",$key);
$smarty->assign("url",$settings['siteurl']);
$smarty->assign("name",$name);
$html_body=$smarty->fetch('classes/email/reset_password.tpl');
$non_html="Copy and paste this link to recover your password ".$settings['siteurl']."/help/password/?key=recover&email=" . $key;

$mail = new PHPMailer;
$mail->isSMTP();
$mail->Host = $settings['email_host'];
$mail->SMTPAuth = true;
$mail->Username = $settings['email_address'];
$mail->Password = $settings['email_password'];
$mail->SMTPSecure = 'ssl';
$mail->Port = 465;   
$mail->From = $settings['email_address'];
$mail->FromName = 'Password Recovery';
$mail->addAddress($email); 
$mail->addReplyTo($settings['email_address'], 'Password Recovery');
$mail->isHTML(true);

$mail->Subject = $settings['sitename'].' Password Recovery';
$mail->Body  = $html_body;
$mail->AltBody = $non_html;

if(!$mail->send()) {
$error="Email could not be sent";	
} else {
$success="Email sent, check your email! You may need to check your spam folder.";
}

}
}

}else{
//reset password
$key2=preg_replace("/[^A-Za-z0-9 ]/", '', $_GET['email']);

//check key and session
try {
$stmt = $db->prepare("SELECT uid,keyme,expire FROM users_pwrecovery WHERE keyme = :key2 LIMIT 1");
$stmt->bindValue(':key2', $key2, PDO::PARAM_INT);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
$nameq=$results[0];
} catch(PDOException $ex) {
if($smarty->debugging){
echo $ex->getMessage();
}
error_log($ex->getMessage());
}

if ( !$nameq['uid']||$nameq['uid']<1){
$error="Key is incorrect";
}else{
if($_SESSION['resetkey'] != substr($key2, -7)){
$error="Session mismatch. Try again";	
$errorbad=true;
}else{
$expFormat = mktime(date("H"), date("i"), date("s"), date("m")  , date("d"), date("Y"));
$expDate = date("Y-m-d H:i:s",$expFormat);
if($expDate>$nameq['expire']){
$error="Reset link has expired";
}else{
$uid=$nameq['uid'];
$continue=true;
}
}
}
if($_POST && $continue=true){
$continue=true;
$pass1=$_POST['password_1'];
$pass2=$_POST['password_2'];

if (!$pass1 OR strlen($pass1) < 8  OR strlen($pass1) > 24 ){
$error='Password must be 8-24 characters.';
$continue=false;
}

//check if passwords are same
if($pass1!=$pass2){
$error="Passwords do not match. Try again";
$continue=false;
}

if($continue){
$user_password_hash = password_hash($pass1, PASSWORD_DEFAULT);
try {
$stmt = $db->prepare("UPDATE users SET user_password_hash=:user_password_hash WHERE id = :uid");
$stmt->bindValue(':user_password_hash', $user_password_hash, PDO::PARAM_STR);
$stmt->bindValue(':uid', $uid, PDO::PARAM_INT);
$stmt->execute();
} catch(PDOException $ex) {
if($smarty->debugging){
echo $ex->getMessage();
}
error_log($ex->getMessage());
}
$stmt = $db->prepare("DELETE FROM users_pwrecovery WHERE keyme = :id");
$stmt->bindValue(':id', $key2, PDO::PARAM_STR);
$stmt->execute();
$continue=true;
$success="Your password has been reset. You can now log in";
}
}
$smarty->assign('continue',$continue,true);
$smarty->assign('key2',$key2,true);	
}
}

$db=null;
$smarty->assign('success',$success,true);
$smarty->assign('errorbad',$errorbad,true);
$smarty->assign('error',$error,true);
$smarty->assign('title',$title,true);
$smarty->assign('page',$page,true);
$smarty->assign('key',$key,true);
$smarty->assign('description',false,true);
$smarty->assign('keywords',false,true);
$smarty->assign('game',false,true);
$smarty->assign('tag',false,true);
$smarty->assign('s',false,true);
$smarty->display('help.tpl');
?>