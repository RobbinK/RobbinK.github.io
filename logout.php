<?php
include('functions.php');

$_SESSION = array();
session_destroy();
$u->remove_cookie();

header('Location: '.$settings['siteurl']);
die();
?>