<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: Trader_history.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:57
##########################################################
 */


class Trader_history extends Model {
    protected $_table='abs_trade_history';
        private static $instance;

    private static function createInstance() {
        if (!self::$instance) {
            $className = get_class();
            self::$instance = new $className;
        }
    }
}