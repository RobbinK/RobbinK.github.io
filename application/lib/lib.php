<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: lib.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:57
##########################################################
 */


class lib {
###############################################################################  

    public static function get_domain($url, $get_subdomain = true)
    {
        return get_domain($url, $get_subdomain);
    }

    public static function wwwurl($url) {
        if (!function_exists('unparse_url')) {

            function unparse_url($parsed_url) {
                $scheme = isset($parsed_url['scheme']) ? $parsed_url['scheme'] . '://' : '';
                $host = isset($parsed_url['host']) ? $parsed_url['host'] : '';
                $port = isset($parsed_url['port']) ? ':' . $parsed_url['port'] : '';
                $user = isset($parsed_url['user']) ? $parsed_url['user'] : '';
                $pass = isset($parsed_url['pass']) ? ':' . $parsed_url['pass'] : '';
                $pass = ($user || $pass) ? "$pass@" : '';
                $path = isset($parsed_url['path']) ? $parsed_url['path'] : '';
                $query = isset($parsed_url['query']) ? '?' . $parsed_url['query'] : '';
                $fragment = isset($parsed_url['fragment']) ? '#' . $parsed_url['fragment'] : '';
                return "$scheme$user$pass$host$port$path$query$fragment";
            }

        }
        $parsedUrl = parse_url($url);
        if (isset($parsedUrl['host'])) {
            $parsedUrl['host'] = preg_replace("/^www\./i", "", $parsedUrl['host']);
            if (count(explode('.', $parsedUrl['host'])) < 3 && preg_match('/[^\d\.\:]/', $parsedUrl['host']) && $parsedUrl['host'] != 'localhost')
                $parsedUrl['host'] = 'www.' . $parsedUrl['host'];
        }
        return (unparse_url($parsedUrl));
    }

    ######################################################

    public static function rand($length = 32, $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890') {
        $chars_length = (strlen($chars) - 1);
        $string = $chars[rand(0, $chars_length)];
        for ($i = 1; $i < $length; $i = strlen($string)) {
            $r = $chars[rand(0, $chars_length)];
            if ($r != $string[$i - 1])
                $string .= $r;
        }
        return $string;
    }

}

