<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: index.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:59
##########################################################
 */
  
if (!preg_match('/install$/', dirname(__FILE__)))
    exit('/install folder not found!');

##############################################
require_once ('../path.php');
require_once (ROOT_PATH . '/core/_jp.php');



include 'header.php';
?> 

<span style="color:#C00000;font-family:Tahoma;font-size:8pt;">
    version <?=sys_ver?>
</span>
<br /> 
<br /> 
<img alt="" src="images/InstallIcon.png" />

<p>
    ArcadeBooster Game Script is a free web based software that helps
    arcade sites owners make professional gaming sites as easy as a few clicks!
        
    <div class='hint'>
        Before INSTALLATION please GIVE permission <font style="color:#00cc33">777</font> for <b style="color:#FF8F00">/tmp</b>  and <b style="color:#FF8F00">/content</b> FOLDERS in root directory.
    </div>
</p>

<a class="button-silver" href="step2.php"><span> Next Step</span></a>
<!---------   END Content----------------> 
<?php include 'footer.php'; ?>