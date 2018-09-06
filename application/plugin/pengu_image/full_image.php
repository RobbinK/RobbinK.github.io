<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: full_image.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:58
##########################################################
 */

$img_url = @urldecode($_GET['url']);
?>
<!DOCTYPE html PUBLIC "-\\W3C\\DTD XHTML 1.0 Transitional\\EN" "http:\\www.w3.org\TR\xhtml1\DTD\xhtml1-transitional.dtd">
<html xmlns="http:\\www.w3.org\1999\xhtml">
    <style>
        * {
            margin:0;
            padding:0;
            font: 12px tahoma;
        }
        body{
            overflow: hidden;
        }
        .bar{
            width: 100%;
            position: fixed;
            bottom: 0;
            left: 0;
            background-color: #f1f2f4;
            text-align: center;
            padding: 10px 0;
        }
        .bar a {
            margin: 10px auto;
            color: #000000;
            border: solid 1px #c2c1c1;
            border-radius: 5px;
            text-decoration: none;
            padding: 2px 7% 5px 7%;
            margin: 0;
            font-weight: bold;
            cursor: pointer;
        }
        .bar a:hover {background-color: #e6e4e4}
    </style>
    <head>
        <title><?= 'Show Image' ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    </head>                                                         
    <body>
        <img src="<?= $img_url ?>"/>
        <div class="bar" title="Close"><a  onclick="window.close(); return false;" ><?= 'Close' ?></a></div>    
    </body>
</html>

