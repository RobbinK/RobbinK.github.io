<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: filelock.class.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:59
##########################################################
 */

class filelock {

    private $handle;

    public static function read ($handle ) {
        $classname = get_class();
        $lock = new $classname;
        $lock->handle = $handle;
        return flock($handle,LOCK_SH) ? $lock : false;
    }

    public static function write ($handle ) {
        $classname = get_class();
        $lock = new $classname;
        $lock->handle = $handle;
        return flock($handle,LOCK_EX) ? $lock : false;
    }
    
    public function __destruct ( ) {
        flock($this->handle,LOCK_UN);
    }

}