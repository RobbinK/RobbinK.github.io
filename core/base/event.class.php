<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: event.class.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:59
##########################################################
 */


define('EVENT_OnCallController', 'OnCallController');
define('EVENT_OnPreLoadView', 'OnPreLoadView');
define('EVENT_OnLoadView', 'OnLoadView');
define('EVENT_OnShowedView', 'OnShowedView');

class event {

    private static $onCallControllerEvents;
    private static $onLoadViewEvents;
    private static $onPreLoadViewEvents;
    private static $onShowedViewEvents;

    public static function register_onCallController($eventmethod, $priority = null) {
        if (!$priority)
            $priority = count(self::$onCallControllerEvents) + 10;
        if (!is_array($eventmethod))
            $eventmethod = array($eventmethod);
        self::$onCallControllerEvents[] = array($eventmethod, 'p' => $priority);
    }

    public static function register_onPreLoadView($eventmethod, $priority = null) {
        if (!$priority)
            $priority = count(self::$onCallControllerEvents) + 10;
        if (!is_array($eventmethod))
            $eventmethod = array($eventmethod);
        self::$onPreLoadViewEvents[] = array($eventmethod, 'p' => $priority);
    }

    public static function register_onLoadView($eventmethod, $priority = null) {
        if (!$priority)
            $priority = count(self::$onCallControllerEvents) + 10;
        if (!is_array($eventmethod))
            $eventmethod = array($eventmethod);
        self::$onLoadViewEvents[] = array($eventmethod, 'p' => $priority);
    }

    public static function register_onShowedView($eventmethod, $priority = null) {
        if (!$priority)
            $priority = count(self::$onCallControllerEvents) + 10;
        if (!is_array($eventmethod))
            $eventmethod = array($eventmethod);
        self::$onShowedViewEvents[] = array($eventmethod, 'p' => $priority);
    }

    public static function getEvents($eventName) {
        switch ($eventName) {
            case EVENT_OnCallController:
                self::array_sort(self::$onCallControllerEvents, 'p');
                $data = self::$onCallControllerEvents;
                break;
            case EVENT_OnPreLoadView:
                self::array_sort(self::$onPreLoadViewEvents, 'p');
                $data = self::$onPreLoadViewEvents;
                break;
            case EVENT_OnLoadView:
                $data = self::$onLoadViewEvents;
                self::array_sort($data, 'p');

                break;
            case EVENT_OnShowedView:
                self::array_sort(self::$onShowedViewEvents, 'p');
                $data = self::$onShowedViewEvents;
                break;
        }
        if (!$data)
            return array();
        $ret = array();
        foreach ($data as $v)
            if (count($v[0]) == 1)
                $ret[] = $v[0][0];
            else
                $ret[] = $v[0];
        return $ret;
    }

    static function array_sort(&$array) {
        if (!function_exists('array_sort_func')) {

            function array_sort_func($a, $b = NULL) {
                static $keys;
                if ($b === NULL)
                    return $keys = $a;
                foreach ($keys as $k) {
                    if (@$k[0] == '!') {
                        $k = substr($k, 1);
                        if (@$a[$k] !== @$b[$k]) {
                            return strnatcmp(@$b[$k], @$a[$k]);
                        }
                    } else if (@$a[$k] !== @$b[$k]) {
                        return strnatcmp(@$a[$k], @$b[$k]);
                    }
                }
                return 0;
            }

        }

        if (!$array)
            return $array;
        $keys = func_get_args();
        array_shift($keys);
        array_sort_func($keys);
        usort($array, "array_sort_func");
    }

}
