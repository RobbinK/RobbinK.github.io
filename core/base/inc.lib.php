<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: inc.lib.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:59
##########################################################
 */


require_once(ROOT_PATH . '/core/lib/seclib/lp/lc.php');

function pengu_user_load_class($class_name, &$instance, $params = null) {
    $class_name = preg_replace('/_uclass|\.php/i', '', $class_name);
    $filepath = siteinfo(SITE_APP_PATH) . "/user/classes/{$class_name}_uclass.php";
    if (!file_exists($filepath))
        return false;
    include_once($filepath);
    $class = "{$class_name}Uclass";
    if (!class_exists($class))
        return false;
    if (!empty($params)) {
        if (!is_array($params))
            $params = array($params);
        $keys = range(1, count($params));
        array_walk($keys, create_function('&$v,$k', '$v=\'var\'.$v;'));
        $newarr = array_combine($keys, array_values($params));
        extract($newarr);
        $keys = array_keys($newarr);
        array_walk($keys, create_function('&$v,$k', '$v=\'$\'.$v;'));
        eval("\$instance=new {$class}(" . join(',', $keys) . ");");
    } else
        $instance = new $class;
}

function pengu_user_load_lib($lib_name) {
    $lib_name = preg_replace('/_ulib|\.php/i', '', $lib_name);
    $filepath = siteinfo(SITE_APP_PATH) . "/user/libs/{$lib_name}_ulib.php";
    if (!file_exists($filepath))
        return false;
    include_once($filepath);
}
