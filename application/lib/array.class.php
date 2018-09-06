<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: array.class.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:57
##########################################################
 */


class arrayUtil {
    ######################################################

    public static function array_filter_recursive(array $input, $fn = null) {
//        $defaultfn = function($val) {
//                    return (!empty($val));
//                };
//        if (!$fn)
//            $fn = $defaultfn;

        $result = array();
        foreach ($input as $key => $val) {
            if (is_array($val)) {
                $recur = self::array_filter_recursive($val, $fn);
                if ($recur !== null)
                    $result[$key] = $recur;
            } else {
                if ($fn) {
                    if ($fn($val)) {
                        $result[$key] = $val;
                    }
                } else {
                    if (!empty($val))
                        $result[$key] = $val;
                }
            }
        }
        if (!empty($result))
            return $result;
    }

    ######################################################

    public static function array_search($array, $key, $value) {
        $results = array();
        if (is_array($array)) {
            if (isset($array [$key]) && $array [$key] == $value)
                $results[] = $array;
            foreach ($array as $subarray)
                if (is_array($subarray))
                    $results = array_merge($results, self :: array_search($subarray, $key, $value));
        }
        return $results;
    }

    ######################################################

    public static function arrayToObject($array) {
        if (!is_array($array)) {
            return $array;
        }

        $object = new stdClass();
        if (is_array($array) && count($array) > 0) {
            foreach ($array as $name => $value) {
                $name = strtolower(trim($name));
                if (!empty($name)) {
                    $object->$name = self::arrayToObject($value);
                }
            }
            return $object;
        } else {
            return FALSE;
        }
    }

    public static function objectToArray($data) {
        if (is_array($data) || is_object($data)) {
            $result = array();
            foreach ($data as $key => $value) {
                $result[$key] = self::objectToArray($value);
            }
            return $result;
        }
        return $data;
    }

    ###################################################

    static function array_sort_order(&$array, $sort_field, $order) {
        global $_order, $_sort_field;
        $_order = $order;
        $_sort_field = $sort_field;

        if (!function_exists('array_sort_order_func')) {

            function array_sort_order_func($a, $b) {
                global $_order, $_sort_field;
                $pos_a = array_search(@$a[$_sort_field], $_order);
                $pos_b = array_search(@$b[$_sort_field], $_order);
                return $pos_a - $pos_b;
            }

        }

        if (!$array)
            return;
        usort($array, 'array_sort_order_func');
    }

    ###################################################

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

if (!function_exists('array_replace')) {

    function array_replace() {
        if (func_num_args() < 2) {
            throw new Exception('There should be at least 2 arguments passed to array_replace()');
        }
        $args = func_get_args();
        $ret = array();
        foreach ($args[0] as $k => $v) {
            $ret[$k] = $v;
        }

        for ($i = 1; $i < func_num_args(); $i++) {
            foreach ($args[$i] as $k => $v) {
                $ret[$k] = $v;
            }
        }
        return $ret;
    }

}