<?php
$_key = $_COOKIE['login']; 
$userinfo = $Login->getUserInfo($_key);
$userinfo = json_decode($userinfo);
if(@$isLogin and $userinfo->role == "user"):
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="robots" content="noindex">
<meta name="googlebot" content="noindex">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!--[if lt IE 9]>
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<script type="text/javascript">
$(document).ready(function(){ $('#myModal').hide(); });
$('#myModal').modal({
	  keyboard: false
	});
$('#myModal').modal('show');
</script>
<script src="js/ievfallback.js"></script>
<link rel="stylesheet" type="text/css" href="css/bootstrap.css" />
<link rel="stylesheet" type="text/css" href="css/bootstrap-responsive.css" />
<link rel="stylesheet" type="text/css" href="css/myCSS.css" />

<title>Tickets</title>
</head>
<?php endif; ?>