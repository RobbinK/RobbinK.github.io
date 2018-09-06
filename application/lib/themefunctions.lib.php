<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: themefunctions.lib.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:57
##########################################################
 */


function get_content($filename) {
    $ext = path::get_extension($filename) ? path::get_extension($filename) : 'php';
    $dir = path::get_dirname($filename);
    $filename = rightchar('/', $dir) . path::get_filename($filename);
    eval(globals_st($GLOBALS));
    $path = path::get_dirname(viewfile_path());
    $newpath = null;
    while ($newpath != template_path()) {
        $newpath = path::each_dir($path);
        if (file_exists($newpath . "/{$filename}.$ext")) {
            include ($newpath . "/{$filename}.$ext");
            return;
        }
    }
}

function get_sidebar($i = null) {
    eval(globals_st($GLOBALS));
    $path = path::get_dirname(viewfile_path());
    $newpath = null;
    while ($newpath != template_path()) {
        $newpath = path::each_dir($path);
        if (file_exists($newpath . "/sidebar{$i}.php")) {
            include ($newpath . "/sidebar{$i}.php");
            return;
        }
    }
}

function get_header($i = null) {
    eval(globals_st($GLOBALS));
    $path = path::get_dirname(viewfile_path());
    $newpath = null;
    while ($newpath != template_path()) {
        $newpath = path::each_dir($path);
        if (file_exists($newpath . "/header{$i}.php")) {
            include ($newpath . "/header{$i}.php");
            return;
        }
    }
}

function get_footer($i = null) {
    eval(globals_st($GLOBALS));
    $path = path::get_dirname(viewfile_path());
    $newpath = null;
    while ($newpath != template_path()) {
        $newpath = path::each_dir($path);
        if (file_exists($newpath . "/footer{$i}.php")) {
            include ($newpath . "/footer{$i}.php");
            return;
        }
    }
}

?>