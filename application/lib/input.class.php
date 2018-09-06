<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: input.class.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:57
##########################################################
 */


class input {

    private static $injectionkeywords;

    public static function set_injections($injectionsArray) {
        if (is_array($injectionsArray))
            self::$injectionkeywords = $injectionsArray;
    }

    public static function posts() {
        $args = func_get_args();
        foreach ($args as $v) {
            $_POST[$v] = self :: post($v);
        }
    }

    public static function post($name) {
        return (isset($_POST[$name])) ? self :: paramsafe($_POST[$name], true, true) : null;
    }

    public static function get($name) {
        return (isset($_REQUEST[$name]) ? self :: paramsafe($_REQUEST[$name], true, true) : null);
    }

    public static function safe($text, $sql = true, $html = true) {
        return self :: paramsafe($text, $sql, $html);
    }

    public static function sqlsafe($text) {
        return self :: paramsafe($text, true, false);
    }

    public static function sqlescape($text) {
        return self :: sql_escape($text);
    }

    public static function htmlsafe($text) {
        return self :: paramsafe($text, false, true);
    }

//>>>>>>>>> Unsafe

    public static function unsafe($text, $sql = true, $html = true) {
        return self :: paramunsafe($text, $sql, $html);
    }

    public static function sqlunsafe($text) {
        return self :: paramunsafe($text, true, false);
    }

    public static function htmlunsafe($text) {
        return self :: paramunsafe($text, false, true);
    }

//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>

    private static function sql_safe($text) {
        if (count(self::$injectionkeywords) > 0) {
            $safewords = array();
            $patern_badwords = array();
            foreach (self::$injectionkeywords as $val) {
                $safewords[] = base64_encode($val);
                $patern_badwords[] = "/" . $val . "/i";
            }
            $text = preg_replace($patern_badwords, $safewords, $text);
        }
        $text = self::sql_escape($text);
        return $text;
    }

    private static function sql_unsafe($text) {
        if (count(self::$injectionkeywords) > 0) {
            $patern_safewords = array();
            foreach (self::$injectionkeywords as $val)
                $patern_safewords[] = "/" . base64_encode($val) . "/i";
            $text = preg_replace($patern_safewords, self::$injectionkeywords, $text);
        }
        $text = stripcslashes($text);
        return $text;
    }

    private static function sql_escape($text) {
        global $pengu_dbhandle;

        if ($pengu_dbhandle) {
            if (get_magic_quotes_gpc())
                $text = stripslashes($text);
            if (DB_CONNECTION_TYPE == 'mysql') {
                $text = mysql_real_escape_string($text, $pengu_dbhandle);
            } else if (DB_CONNECTION_TYPE == 'mysqli') {
                $text = mysqli_real_escape_string($pengu_dbhandle, $text);
            } else if (DB_CONNECTION_TYPE == 'pdo') {
                $text = trim($pengu_dbhandle->quote($text), '"\'');
            } else
                $text = addslashes($text);
        } else {
            if (get_magic_quotes_gpc())
                $text = stripslashes($text);
            $text = addslashes($text);
        }
        return $text;
    }

    private static function html_safe($text) {
        return htmlspecialchars(trim($text), ENT_QUOTES);
    }

    private static function html_unsafe($text) {
        return htmlspecialchars_decode($text);
    }

    private static function paramsafe($params, $sql, $html) {
        $out = array();
        if (is_array($params)) {
            foreach ($params as $key => $val)
                $out[self :: safe($key, $sql, $html)] = self :: safe($val, $sql, $html);
        } else {
            $out = $params;
            if ($sql)
                $out = self :: sql_safe($out);
            if ($html)
                $out = self :: html_safe($out);
        }
        return $out;
    }

    public static function paramunsafe($params, $sql, $html) {
        $out = array();
        if (is_array($params)) {
            foreach ($params as $key => $val)
                $out[self :: unsafe($key, $sql, $html)] = self :: unsafe($val, $sql, $html);
        } else {
            $out = $params;
            if ($sql)
                $out = self :: sql_unsafe($out);
            if ($html)
                $out = self :: html_unsafe($out);
        }
        return $out;
    }

}
