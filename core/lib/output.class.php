<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: output.class.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:59
##########################################################
 */

class output
{
  public function urlencode($str)
  {
    $url_seo = @ereg_replace("[^A-Za-z0-9]", "_", $str);
    return $url_seo;
  }

}
 