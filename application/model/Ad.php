<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: Ad.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:57
##########################################################
 */


class Ad extends Model {

    protected $_table = 'abs_ads';
    private static $instance;

    private static function createInstance() {
        if (!self::$instance) {
            $className = get_class();
            self::$instance = new $className;
        }
    }

    public static function getAds($zone_id) {
        self::createInstance();
        return self::$instance->select('id,code,countries,`order`')->where(array('status' => 1, 'zone_id' => $zone_id))->exec()->allrows();
    }

}