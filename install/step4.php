<?php
require_once('../mysql.php');
$db = new PDO('mysql:host='.HOST.';dbname='.DATABASE.';charset=utf8mb4',USER,PASSWORD);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
require_once('includes/insall_games.php');

if($_POST){
	
	$siteurl=$_POST['siteurl'];
	$siteurl = rtrim($siteurl,"/");
	$sitename=$_POST['sitename'];
	$sitename=preg_replace("/[^A-Za-z0-9 ]/", '', $sitename);
	$imgcdn=$siteurl.'/thumbs';
	
	$analytics=$_POST['analytics'];
	$adcode=$_POST['adcode'];
	$pagelevel=$_POST['pagelevel'];
	$gamesid=$_POST['gamesid'];
	$channel=$_POST['channel'];
	
	//
	//validate url
	//
	if (!filter_var($siteurl, FILTER_VALIDATE_URL) === false) {
	}else{
		$error="Please enter a valid url: http://yoursite.com";
	}

//
// install any new games once 
//
if(!$error){
	
	try {
	$stmt = $db->prepare("UPDATE settings SET result=:siteurl WHERE name='siteurl'");
	$stmt->bindValue(':siteurl', $siteurl, PDO::PARAM_STR);
	$stmt->execute();

	$stmt = $db->prepare("UPDATE settings SET result=:sitename WHERE name='sitename'");
	$stmt->bindValue(':sitename', $sitename, PDO::PARAM_STR);
	$stmt->execute();
	
	$stmt = $db->prepare("UPDATE settings SET result=:imgcdn WHERE name='imgcdn'");
	$stmt->bindValue(':imgcdn', $imgcdn, PDO::PARAM_STR);
	$stmt->execute();
	
	$stmt = $db->prepare("UPDATE settings SET result=:analytics WHERE name='analytics'");
	$stmt->bindValue(':analytics', $analytics, PDO::PARAM_STR);
	$stmt->execute();
	
	$stmt = $db->prepare("UPDATE settings SET result=:adcode WHERE name='adcode'");
	$stmt->bindValue(':adcode', $adcode, PDO::PARAM_STR);
	$stmt->execute();

	$stmt = $db->prepare("UPDATE settings SET result=:pagelevel WHERE name='adsense-pagelevel'");
	$stmt->bindValue(':pagelevel', $pagelevel, PDO::PARAM_STR);
	$stmt->execute();
	
	$stmt = $db->prepare("UPDATE settings SET result=:gamesid WHERE name='adsense-games'");
	$stmt->bindValue(':gamesid', $gamesid, PDO::PARAM_STR);
	$stmt->execute();
	
	$stmt = $db->prepare("UPDATE settings SET result=:channel WHERE name='ad-games-channel'");
	$stmt->bindValue(':channel', $channel, PDO::PARAM_STR);
	$stmt->execute();
	} catch(PDOException $ex) {
	$error="SQL error when updating.";
	error_log($ex->getMessage());
	}
}

//if step == get new games
if(!$error){
	$install_new=new installNew();
	$install_new->db =& $db;
	$check=$install_new->attempt($siteurl);
	if($check){
	//installs finished, go to next step
 
 	header("Location: step5.php");
	die();

	}else{
	error_log("Cron Job Error: Emails could not be verified");
	}	
}


}

?>
<!DOCTYPE html><html lang="en"><head>
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
<a href="" type="button" class="btn btn-success btn-circle">2</a>
<p>Step 2</p>
</div>
<div class="stepwizard-step">
<a href="" type="button" class="btn btn-success btn-circle">3</a>
<p>Step 3</p>
</div>
<div class="stepwizard-step">
<a href="" type="button" class="btn btn-primary btn-circle">4</a>
<p>Step 4</p>
</div>
</div>
</div><div class="container">
<div class="row">
<div class="page-header">
<h4>Settings</h4>
</div>
	<?php if($error){ echo '<div class="alert alert-danger" role="alert"> Error: '.$error.'</div>'; } ?>
<form method="post" action="?submt" id="loginit" style="margin-bottom:50px">	

	<div class="form-group">
	<label for="exampleInputEmail1">Website Url</label>
	<input type="text" required class="form-control" id="exampleInputEmail1" name="siteurl" placeholder="http://yoursite.com" />
	</div>

	<div class="form-group">
	<label for="exampleInputEmail1">Website Name</label>
	<input type="text" required class="form-control" id="exampleInputEmail1" name="sitename" placeholder="Demo Website" />
	</div>
	
	<div class="page-header">
	<h4>Ad Code (You can change this later)</h4>
	</div>
	
	<div class="form-group" style="padding-top:15px">
	<label for="exampleInputEmail5">Banner Ads (Use responsive ads<small> or 300x250</small>)</label><br>
	<textarea name="adcode" cols="50" rows="5" style="width:100%" id="exampleInputEmail5"></textarea>
	</div>
	
	<div class="form-group">
	<label for="exampleInputEmail1">Adsense ID (For <a href="https://support.google.com/adxseller/answer/6068103?hl=en" target="_blank">page level ads</a>, <small>must be enabled on your account</small>)</label>
	<input type="text" class="form-control" id="exampleInputEmail1" name="pagelevel" placeholder="ca-pub-000000" />
	</div>
	
	<div class="form-group">
	<label for="exampleInputEmail1">Adsense for Games ID</label>
	<input type="text" class="form-control" id="exampleInputEmail1" name="gamesid" placeholder="ca-games-pub-000000" />
	</div>

	<div class="form-group">
	<label for="exampleInputEmail1">Adsense for Games Channel ID (optional)</label>
	<input type="text" class="form-control" id="exampleInputEmail1" name="channel" placeholder="4333186796" />
	</div>
	
	<div class="form-group">
	<label for="exampleInputEmail5">Analytics Code(Include Script Tags)</label><br>
	<textarea name="analytics" cols="50" rows="5" style="width:100%" id="exampleInputEmail5"></textarea>
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