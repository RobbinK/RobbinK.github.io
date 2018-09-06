<?PHP
class admin {
var $db;
var $user = array();

function sync(){
if(isset($_SESSION['user_login_status']) AND $_SESSION['user_login_status'] == true){
$this->load_u();
}else {
$this->guest();
}

return $this->user;
}

function load_u(){
$email=$_SESSION['user_email'];
if(isset($_SESSION['isadmin']) && $_SESSION['isadmin']==1){
$stmt = $this->db->prepare("SELECT users_admin.id FROM users_admin,users WHERE users.user_email= :email AND users.id=users_admin.uid LIMIT 1");
$stmt->bindValue(':email', $email, PDO::PARAM_STR);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
$admin=$results[0];
if(intval($admin['id'])>0){
$this->user= $admin;
}else{
$this->guest();
}
}else{
$this->guest();
}
}

function guest(){
$name = 'badman_'.rand(10,1000);
$this->user = array('id' => 0, 'name' => $name);
}

}
?>