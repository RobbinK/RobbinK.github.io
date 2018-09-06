<?php
require_once('../mysql.php');
$db = new PDO('mysql:host='.HOST.';dbname='.DATABASE.';charset=utf8mb4',USER,PASSWORD);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
$error=false;
require_once("../classes/login.class.php");
$login = new Login();
require_once('../classes/u.class.php');
$u =new u();
$u->db =& $db;
$user = $u->sync();
require_once("../libs/password_compatibility_library.php");

//Register user + admin
if($_POST){
	
	$email=$_POST['email'];
	$password=$_POST['password'];
	$password2=$_POST['password2'];
	$name="admin";
	
	//
	//check email
	//
	if ( !preg_match('/^[_A-z0-9-]+((\.|\+)[_A-z0-9-]+)*@[A-z0-9-]+(\.[A-z0-9-]+)*(\.[A-z]{2,4})$/', $email) ){
	$error='Please enter a valid email';
	$email="";
	} elseif (!$email){
	$error='Please enter a valid email';
	}
	if ( !$password OR strlen($password) < 6  OR strlen($password) > 30 ){
	$error='Password must be 6-30 characters.';
	}
	if ( !$password2 OR strlen($password2) < 6  OR strlen($password2) > 30 ){
	$error='Password must be 6-30 characters.';
	}
	
	//
	//insert
	//
	if(!$error){
		$_SESSION['user_email'] = $email;
		$_SESSION['user_login_status'] = 1;
		
		$ip=$_SERVER['REMOTE_ADDR'];
		$user_password_hash = password_hash($password, PASSWORD_DEFAULT);
		$admin_password_hash = password_hash($password2, PASSWORD_DEFAULT);
		
		$stmt = $db->prepare("INSERT IGNORE INTO users (user_email,user_password_hash,username,ipaddress) VALUES(:field1,:field2,:field3,:field4)");
		$stmt->execute(array(':field1' => $email, ':field2' => $user_password_hash, ':field3' => $name, ':field4' => $ip));
		$realuid=$db->lastInsertId();
		
		$stmt = $db->prepare("INSERT IGNORE INTO users_admin (uid,admin_password) VALUES(:field1,:field2)");
		$stmt->execute(array(':field1' => $realuid, ':field2' => $admin_password_hash));
		
		usleep(200);
		$u->regen_cookie();
	}
	
	if(!$error){
	header("Location: step3.php");
	die();
	}	
}


?><!DOCTYPE html><html lang="en"><head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"/>
<title>Install</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<style>
.stepwizard-step p {
margin-top: 10px;
}
.stepwizard-row {
display: table-row;
}
.stepwizard {
display: table;
width: 50%;
position: relative;
}
.stepwizard-step button[disabled] {
opacity: 1 !important;
filter: alpha(opacity=100) !important;
}
.stepwizard-row:before {
top: 14px;
bottom: 0;
position: absolute;
content: " ";
width: 100%;
height: 1px;
background-color: #ccc;
z-order: 0;
}
.stepwizard-step {
display: table-cell;
text-align: center;
position: relative;
}
.btn-circle {
width: 30px;
height: 30px;
text-align: center;
padding: 6px 0;
font-size: 12px;
line-height: 1.428571429;
border-radius: 15px;
}
</style>
</head><body>
<nav class="navbar navbar-default">
<div class="container-fluid">
<h1 style="text-align:center;padding-bottom:5px">Install</h1>
</div>
</nav>

<div class="stepwizard col-md-offset-3">
<div class="stepwizard-row setup-panel">
<div class="stepwizard-step">
<a href="" type="button" class="btn btn-success btn-circle">1</a>
<p>Step 1</p>
</div>
<div class="stepwizard-step">
<a href="" type="button" class="btn btn-primary btn-circle">2</a>
<p>Step 2</p>
</div>
<div class="stepwizard-step">
<a href="" type="button" class="btn btn-default btn-circle">3</a>
<p>Step 3</p>
</div>
<div class="stepwizard-step">
<a href="" type="button" class="btn btn-default btn-circle">4</a>
<p>Step 4</p>
</div>
</div>
</div>

<div class="container">
<div class="row">
<div class="page-header">
<h4>Register Your Account</h4>
<p>This will be your login account for your website.</p>
</div>

<?php if($error){ echo '<div class="alert alert-danger" role="alert"> Error: '.$error.'</div>'; } ?>

<form method="post" action="?submt" id="loginit">	
<div class="form-group">
<label for="exampleInputPassword1">Email</label>
<input autofocus type="email" class="form-control" id="exampleInputPassword1" name="email" placeholder="Email" required>
</div>

<div class="form-group">
<label for="exampleInputPassword1">Password</label>
<input type="password" class="form-control" id="exampleInputPassword1" name="password" placeholder="Password" autocomplete="off" required>
</div>

<div class="form-group">
<label for="exampleInputPassword1">Admin Password</label>
<input type="password" class="form-control" id="exampleInputPassword1" name="password2" placeholder="Admin Password" autocomplete="off" required>
</div>

<button type="submit" class="btn btn-full btn-bigtxt btn-primary" id="load" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> Loading...">Submit</button>
</form>
</div></div>
</body></html>
<script src="https://code.jquery.com/jquery-3.1.0.min.js" integrity="sha256-cCueBR6CsyA4/9szpPfrX3s49M9vUU5BgtiJj06wt/s="   crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<script>
$('.btn').on('click', function() {
var $this = $(this);
$this.button('loading');
});
</script>