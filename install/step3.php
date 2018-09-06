<?php

//
// try to create cron, if can't show user message
//
$path=realpath(dirname(__FILE__));
$path=str_replace('/install','/cron/',$path);
$path=str_replace('//','/',$path);

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
<a href="" type="button" class="btn btn-primary btn-circle">3</a>
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
<h4>Cron Job</h4>
</div>

<?php if($error){ echo '<div class="alert alert-danger" role="alert"> Error: '.$error.'</div>'; } ?>

<?php echo '<div class="alert alert-info" role="alert"><b>Create a Cron Job</b>: Your site will not work without a cron job!<br> Job should run every <b>15 minutes</b> and call <b>/cron/index.php</b>.<br><br>You can create cron jobs in CPanel.<br><br>Example of a cron command:<br><textarea style="width:100%">/usr/bin/wget http://YOURSITE.com/cron/index.php</textarea></div>'; ?>


<a href="step4.php" class="btn btn-large btn-primary">Continue</a>
</div></div>
</body></html>