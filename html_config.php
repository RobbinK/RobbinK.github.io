<?php
$head = '
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
<title>'.@SITE_TITLE.'</title>
<link rel="stylesheet" type="text/css" href="css/bootstrap.css" />
<link rel="stylesheet" type="text/css" href="css/myCSS.css" />
<link rel="stylesheet" type="text/css" href="css/bootstrap-responsive.css" />
<script type="text/javascript">
function new_captcha()
{
	document.getElementById(\'captcha\').src = \'securimage/securimage_show.php?\' + Math.random(); return false
}
</script>
<script type="text/javascript" src="js/ievfallback.js"></script>
</head>';

$startBody = '
<body>
<script src="http://code.jquery.com/jquery-latest.js"></script>
<script type="text/javascript" src="js/bootstrap.js"></script>
		     ';

$loginError = '
				<div class="alert alert-error"><center><p> Please enter a valid username or password! </p></center></div>
				';

$logoutError = '
					<div class="alert alert-error"><center><p> you are <b>not</b> logged out </p></center></div>
				';

$endBody = '
</body>
</html>
			';

