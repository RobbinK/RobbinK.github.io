<?PHP
class u {
var $db;
var $user = array();

public function sync(){
if(isset($_SESSION['user_login_status']) AND $_SESSION['user_login_status'] == true){
$this->load_u(session_id());
}else{
if(isset($_COOKIE['uhash'])){	
$pieces=explode(":",$_COOKIE['uhash']);
if(strlen($pieces[0])>10&&$pieces[1]){
$this->remember($pieces[0],$pieces[1]);
}else{
$this->guest();
}
}else{
$this->guest();
}
}
return $this->user;
}

public function regen_cookie(){
$this->sync();
$uid=intval($this->user['id']);
if($uid==0){return false;}
$unqid=substr(uniqid(),0,12);
$hex=openssl_random_pseudo_bytes(32);
$hex=bin2hex($hex);	
$savehex=hash('sha256', $hex);
$expire=date("Y-m-d H:i:s", strtotime("+30 days"));

$stmt = $this->db->prepare("INSERT IGNORE INTO users_auth_tokens (uid,selector,token,expires) VALUES(:field1,:field2,:field3,:field4)");
$stmt->execute(array(':field1' => $uid, ':field2' => $unqid, ':field3' => $savehex, ':field4' => $expire));

//delete old cookie
if(isset($_COOKIE['uhash'])){
$pieces=explode(":",$_COOKIE['uhash']);
$selled=preg_replace("/[^A-Za-z0-9 ]/", '', $pieces[0]);	

$stmt = $this->db->prepare("DELETE FROM users_auth_tokens WHERE selector=:selector AND uid=:uid");
$stmt->bindValue(':selector', $selled, PDO::PARAM_STR);
$stmt->bindValue(':uid', $uid, PDO::PARAM_INT);
$stmt->execute();

unset($_COOKIE['uhash']);
setcookie('uhash', null, -1, '/');
}
//add new cookie
$cookie_name="uhash";
$cookie_value=$unqid.":".$hex;
setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/");
}

public function remove_cookie(){
$this->sync();
$uid=intval($this->user['id']);
if($uid==0){return false;}
if(isset($_COOKIE['uhash'])){
$pieces=explode(":",$_COOKIE['uhash']);
$selled=preg_replace("/[^A-Za-z0-9 ]/", '', $pieces[0]);	

$stmt = $this->db->prepare("DELETE FROM users_auth_tokens WHERE selector=:selector AND uid=:uid");
$stmt->bindValue(':selector', $selled, PDO::PARAM_STR);
$stmt->bindValue(':uid', $uid, PDO::PARAM_INT);
$stmt->execute();

unset($_COOKIE['uhash']);
setcookie('uhash', null, -1, '/');
}
}

private function load_u(){
$email=$_SESSION['user_email'];
if(preg_match('/^[_A-z0-9-]+((\.|\+)[_A-z0-9-]+)*@[A-z0-9-]+(\.[A-z0-9-]+)*(\.[A-z]{2,4})$/', $email)){

$stmt = $this->db->prepare("SELECT users.*,ul.id as level FROM users, users_levels ul WHERE users.user_email = :email AND ul.points <= users.xp ORDER BY ul.points DESC LIMIT 1");
$stmt->bindValue(':email', $email, PDO::PARAM_STR);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
$user=$results[0];

if(intval($user['id'])>0){
$this->user=$user;
}else{
$this->guest();
}
}else{
$this->guest();
}
}

private function remember($selector,$hash){
//check uhash cookie to auto log in via hash code of cookie
$selector=preg_replace("/[^A-Za-z0-9 ]/", '', $selector);
$hexinfo;

$stmt = $this->db->prepare("SELECT uid,selector,token,expires FROM users_auth_tokens WHERE selector=:selector AND expires > DATE_SUB(NOW(),INTERVAL 1 MINUTE) LIMIT 1");
$stmt->bindValue(':selector', $selector, PDO::PARAM_STR);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
$hexinfo=$results[0];
$uid=intval($hexinfo['uid']);

$now = time();
$your_date = strtotime($hexinfo['expires']);
$datediff = $your_date-$now;
$daysoff=floor($datediff/(60*60*24));

if(!$uid||$uid==0){
$this->guest();
}

if($this->timingSafeCompare(hash('sha256', $hash),$hexinfo['token'])){
$stmt = $this->db->prepare("SELECT users.*,ul.id as level FROM users, users_levels ul WHERE users.id = :uid AND ul.points <= users.xp ORDER BY ul.points DESC LIMIT 1");
$stmt->bindValue(':uid', $uid, PDO::PARAM_INT);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
$user=$results[0];
if (intval($user['id'])>0){
$this->user=$user;
session_regenerate_id(true);
$_SESSION['user_email'] = $this->user['user_email'];
$_SESSION['user_login_status'] = 1;
if($daysoff<3){
$this->regen_cookie();
}
}else{
$this->guest();
}
}
}

private function guest(){
$name = 'Guest_'.rand(100,10000);
$this->user = array('id' => 0, 'name' => $name);
}

private function timingSafeCompare($safe, $user) {
$safe .= chr(0);
$user .= chr(0);
if (function_exists('mb_strlen')) {
$safeLen = mb_strlen($safe, '8bit');
$userLen = mb_strlen($user, '8bit');
} else {
$safeLen = strlen($safe);
$userLen = strlen($user);
}
$result = $safeLen - $userLen;
for ($i = 0; $i < $userLen; $i++) {
$result |= (ord($safe[$i % $safeLen]) ^ ord($user[$i]));
}
return $result === 0;
}
}
?>