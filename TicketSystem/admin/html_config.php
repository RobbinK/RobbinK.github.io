<?php
/* HTML BACE */
$head = '
			<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
			<html xmlns="http://www.w3.org/1999/xhtml">
			<head>
			<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
			<meta name="robots" content="noindex">
			<meta name="googlebot" content="noindex">
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
		    <!--[if lt IE 9]>
		      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		    <![endif]-->
			<title>tickets</title>
			<link rel="stylesheet" type="text/css" href="../css/bootstrap.css" />
			<link rel="stylesheet" type="text/css" href="../css/bootstrap-responsive.css" />
			<link rel="stylesheet" type="text/css" href="../css/myCSS.css" />
			</head>
		';

$startBody = '
				<body>
				<script src="http://code.jquery.com/jquery-latest.js"></script>
				<script type="text/javascript" src="../js/bootstrap.js"></script>
		     ';

$endBody = '
				</body>
				</html>
			';

$loginForm = '
<article class="container">
    	<section class="form-actions pager">
            
            <form action="login.php" method="post">
                <legend>Login Form</legend>
                    
                    <div class="input-prepend"><span class="add-on">Username <i class="icon-user"></i></span><input name="email" id="" value="" class="span2 input-block-level" type="email" required="required"></div>
    
                    <div class="input-prepend"><span class="add-on">Password <i class="icon-lock"></i></span><input name="password" id="" class="span2 input-block-level" type="password" required="required"></div>
                
                <input class="btn btn-info" name="login" value="Login" class="btn btn-primary" type="submit">
  
            </form>
        
        </section>    	
    </article>
';

$loginError = '
				<div class="alert alert-error"><center><p> Please enter a valid username or password! </p></center></div>
				';

$logoutError = '
					<div class="alert alert-error"><center><p> you are <b>not</b> logged out </p></center></div>
				';

?>