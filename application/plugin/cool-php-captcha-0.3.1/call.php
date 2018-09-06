<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: call.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:58
##########################################################
 */


require_once '../../../path.php';
require_once(ROOT_PATH . '/core/_jp.php');
include_once 'captcha.php';
##############################################  
$captcha = new SimpleCaptcha();
if (isset($_GET['name']))
    $captcha->session_var = $_GET['name'];


// OPTIONAL Change configuration...
//$captcha->wordsFile = 'words/es.php';
//$captcha->session_var = 'secretword';
//$captcha->imageFormat = 'png';
//$captcha->lineWidth = 3;
//$captcha->scale = 3; $captcha->blur = true;
//$captcha->resourcesPath = "/var/cool-php-captcha/resources";
// OPTIONAL Simple autodetect language example
/*
  if (!empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
  $langs = array('en', 'es');
  $lang  = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
  if (in_array($lang, $langs)) {
  $captcha->wordsFile = "words/$lang.php";
  }
  }
 */



// Image generation
$captcha->CreateImage();