<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: register.class.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:59
##########################################################
 */


class register {

    private static $Data;

    public static function Get($name) {
        if (isset(self::$Data[$name]))
            return self::$Data[$name];
    }

    public static function Set($name, $value) {
        self::$Data[$name] = $value;
    }

    public static function SetAll($data) {
        self::$Data = $data;
    }

    public static function GetAll() {
        return self::$Data;
    }

    public static function Merge($name, $value) {
        self::$Data[$name][] = $value;
    }

    public static function Exist($name) {
        return isset(self::$Data[$name]);
    }

    public static function Destroy($name) {
        if (isset(self::$Data[$name]))
        {
            unset(self::$Data[$name]);
            return;
        }
        unset(self::$Data);
    } 

}