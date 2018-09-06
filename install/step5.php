<?php
require_once('../functions.php');
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
.list-group {
list-style: decimal inside;
}

.list-group-item {
display: list-item;
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
<a href="" type="button" class="btn btn-success btn-circle">4</a>
<p>Step 4</p>
</div>
</div>
</div><div class="container" style="padding-bottom:50px">
<div class="row">
<div class="page-header">
<h4>Install Complete</h4>
</div>

<div class="alert alert-success" role="alert">Congrats! Your website is now ready.<br><a href="<?php echo $settings['siteurl'] ?>/admin/" target="_blank">Go to admin</a> &middot; <a href="<?php echo $settings['siteurl'] ?>" target="_blank">View your website</a></div>

<div class="page-header">
<h4>Information</h4>
</div>
<div class="alert alert-warning" role="alert">Delete the /install/ folder in your file system.</div>
<div class="alert alert-warning" role="alert">If your website is in a subfolder (yoursite.com/m/) YOU MUST edit .htaccess. Find line:<br>RewriteBase /<br>and change to:<br>RewriteBase /YOURSUB/</div>

<div class="page-header">
<h4>SEO Check List</h4>
</div>

<ol class="list-group">
<li class="list-group-item">
<p>Use a CDN like <a href="http://tracking.maxcdn.com/c/314885/3982/378" target="_blank">MaxCDN</a> to increase page speeds and optimize images.</p>
</li>
<li class="list-group-item">
<p>Edit your Page Titles and Descriptions <a href="<?php echo $settings['siteurl'] ?>/admin/settings.php?view=seo" target="_blank">HERE</a></p>
</li>
<li class="list-group-item">
<p>Submit your sitemap <?php echo $settings['siteurl'] ?>/sitemap.txt to google.</p>
</li>
<li class="list-group-item">
<p>Write custom descriptions for all of the games</p>
</li>
</ol>


<div class="page-header">
<h4>Support</h4>
</div>

<a type="button" href="http://pub.lagged.com/contact" target="_blank" class="btn btn-primary btn-lg">Contact Us</a>


</div></div>
</body></html>