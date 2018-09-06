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

include 'init.php';
include_once 'header.php';
?>
<div class="heading">
    <h3>Welcome to ArcadeBooster Toolkits</h3> 
    
   
</div> 
<fieldset>
Welcome to ArcadeBooster Importing system. This system will help you to move from other arcade scripts to ArcadeBooster. 
<br/>
Please read the important information below before your start using importing system and make sure you have done all the requirements as requested.
<br/>   
<b><font style="color:red">Important Notice</font></b><br/>
 - To use AB importing system, you need to have AB script v1.5.0+ installed on your server. If you are currently using previous versions of AB script, please update your copy using the link you see in your admin panel.
    <br/><br/>
- Make sure you put <b>/ab_toolkits/</b> folder in the same path you've installed the AB script.
<br/><br/>
- Importing system will use the same configuration file you've edited for your AB script. So you need to set AB database connection information correctly. 
<br/><br/>
- The importing script will transfer all files and data from old script to ArcadeBooster. All your data in old database will stay the same without any change so you can swap to old script at any time easily.
<br/><br/>
- To transfer images and game files, AB importing tool will make a copy of those files to the new path. So you need to have enough disk space if you need to have a copy of files in your old script. To move files (instead of copy) just select manual copy option during the importing wizard and move your files manually to the AB upload folders which are <b>/content/upload/games/files/</b> for game files, <b>/content/upload/games/images/</b> for images files and <b>/content/upload/</b> for user avatars.
<br/><br/>
- Importing process may take up to 1-2 hours depends on your contents. So, do not close your browser during the moving process.
<br/><br/>
</fieldset>
<?php
include_once 'footer.php';
?>