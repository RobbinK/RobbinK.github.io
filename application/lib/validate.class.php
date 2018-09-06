<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: validate.class.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:57
##########################################################
 */


class validate {
    ######################################################

    public static function _is_Serialized($str) {
        return ($str == serialize(false) || @unserialize($str) !== false);
    }

    ######################################################

    public static function _is_Base64($data) {
        if (preg_match("/^[a-zA-Z0-9\!\-_]+$/", $data))
            return true;
        return false;
    }

    ######################################################

    public static function _is_Md5($md5) {
        return !empty($md5) && preg_match('/^[a-f0-9]{32}$/', $md5);
    }

    ######################################################

    public static function _is_browser($browsers) {
        $UserBrowser = $_SERVER["HTTP_USER_AGENT"];
        if (is_array($browsers)) {
            foreach ($browsers as $br)
                if (strstr($UserBrowser, $br))
                    return true;
        } else {
            if (strstr($UserBrowser, $browsers))
                return true;
        }
        return false;
    }

    ######################################################

    public static function _is_URL($url) {
        $url = trim($url);
        //$pattern='#^(?:https?://)?(?:localhost|[a-z0-9\-]+(?:\.[a-z0-9\-]+)+)(?:0-9]+)?(?:\/.*)?$#i';
        //$pattern = '@(?:https?|ftp)://(?:-\.)?(?:[^\s/?\.#]+\.?)+(?:/[^\s]*)?$@i';
        $pattern='@^(http:\/\/www\.|https:\/\/www\.|http:\/\/|https:\/\/|www\.)([a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}|localhost|\d{0,3}\.\d{0,3}\.\d{0,3}\.\d{0,3})(:[0-9]{1,5})?(\/.*)?$@';
        return preg_match($pattern, $url);
    }

    ######################################################

    public static function _is_ajax_request() {
        return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
    }

    ########################################################

    public static function _is_boolean_Type($value) {
        if (is_bool($value))
            return true;
        if (!is_scalar($value))
            return false;
        $trues = ";1;t;y;yes;on;enabled;enable;active;true;بله;آری;صحیح;فعال;درست;نعم;";
        $falses = ";0;f;n;no;off;disabled;disable;inactive;false;خیر;نه;غلط;غیر فعال;لا;";
        if (strpos($trues . $falses, ";" . strtolower($value) . ";") !== false)
            return true;

        return false;
    }

    ########################################################

    public static function _is_price($price) {
        if (preg_match("/^[\$]?[-+]?[\d\,]+\s*(usd|ریال|تومان)?$/i", $price))
            if (preg_match("/[\$]|usd|ریال|تومان/i", $price))
                return true;
        return false;
    }

    ########################################################

    public static function _is_date($mydate, $date_seperator = '-') {
        $date_regex = '/^[12][0-9]{3}' . $date_seperator . '0[1-9]|1[012]' . $date_seperator . '0[1-9]|[12][0-9]|3[01]$/';
        if (preg_match($date_regex, $mydate))
            return true;
        return false;
    }

}