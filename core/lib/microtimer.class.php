<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: microtimer.class.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:59
##########################################################
 */


class microtimer {

    private static $resultTime;
    private static $startTime;
    private static $endTime;
    private static $time;

    private static function microtime_float() {
        list($usec, $sec) = explode(" ", microtime());
        return ((float) $usec + (float) $sec);
    }

    public static function start($name = null) {
        if ($name)
            self::$startTime[$name] = self::microtime_float();
        else
            self::$time['start'] = self::microtime_float();
    }

    public static function stop($name = null) {
        if ($name && isset(self::$startTime[$name])) {
            self::$endTime [$name] = self::microtime_float();
            self::$resultTime[$name] = self::$endTime[$name] - self::$startTime[$name];
        } elseif (isset(self::$time['start'])) {
            self::$time['end'] = self::microtime_float();
            self::$time['result'] = self::$time['end'] - self::$time['start'];
        }
        return self::getLastTime($name);
    }

    public static function getLastTime($name = null) {
        if ($name)
            return isset(self::$resultTime[$name]) ? self::$resultTime[$name] : 0;
        else
            return isset(self::$time['result']) ? self::$time['result'] : 0;
    }

    public static function getResults() {
        return self::$resultTime;
    }

    public static function remove($name = null) {
        if (isset(self::$resultTime[$name]))
            unset(self::$resultTime[$name]);
        else
            self::$resultTime = null;
    }

}
