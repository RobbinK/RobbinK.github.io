<?php
include('functions.php');
$smarty->caching = 0;
$id=htmlentities($_POST['query']);
if(strlen($id)<2){
$id="";
}
header('Location: '.$settings['siteurl'].'/sitesearch/'.$id);
die();
?>