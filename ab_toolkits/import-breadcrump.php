<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: import-breadcrump.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:59
##########################################################
 */


$plans = array(
    'import-type.php' => 'Import',
    'import-configuration.php' => 'Configuration',
    'import-paths.php' => 'Content Paths',
    'import-settables.php' => 'Match Tables',
    'import-setfields.php' => 'Match Fields',
    'import-finish.php' => 'Importing',
);
$basename = basename(url::itself()->url_nonqry());
$class = "sep-ok";
echo "<ul>\n";
foreach ($plans as $key => $plan) {
    echo "<li><div><span class='{$class}'></span><span>{$plan}</span></div></li>\n";
    if ($key == $basename)
        $class = "sep-pending";
}
echo "</ul>\n";
?>