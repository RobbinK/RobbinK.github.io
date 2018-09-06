<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: pengu_seo.class.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:57
##########################################################
 */


include_once 'pagerank.class.php';

class pengu_seo {

    public static function check_backlink($remote_url, $your_link, $no_follow_sense = true) {
        $found = false;
        //===add http://wwww
        if (!preg_match('#^(http:\/\/|https:\/\/)#i', $your_link))
            $your_link = 'http://' . $your_link;
        $your_link = lib::wwwurl($your_link);
        //=== 
        if (!preg_match('#^(http:\/\/|https:\/\/)#i', $remote_url))
            $remote_url = 'http://' . $remote_url;
        $remote_url = lib::wwwurl($remote_url);
        //===
        $match_pattern = preg_quote(strtolower($your_link), "#");
        $match_pattern = str_replace('www\.', '(www\.)?', $match_pattern);
        $pattern = "#<a\s([^\>]*)href=[\"\']" . $match_pattern . "\/?[\"\']([^\>]*)\>([^\<\>]*)<\/a>#i";
        $nofollow_pattern = "#rel\s*=\s*\"\s*[n|d]ofollow\s*\"#i";
        $data = self::get_page_data($remote_url);
        if (preg_match($pattern, $data, $match)) {
            $found = true;
            if ($no_follow_sense) {
                foreach ($match as $mtc)
                    if (preg_match($nofollow_pattern, $mtc)) {
                        $found = false;
                        break;
                    }
            }
        }
        return $found;
    }

    public static function check_google_pr($remote_url) {
        $pr = pengu_pagerank::getRank($remote_url);
        return !empty($pr) ? $pr : '-';
    }

    public static function check_alexa_rank($remote_url) {
        $domain = lib::get_domain($remote_url);
        $request = "http://data.alexa.com/data?cli=10&amp;dat=s&amp;url=" . $domain;
        $data = self::get_page_data($request);
        preg_match('/<POPULARITY URL="(.*?)" TEXT="([\d]+)"/si', $data, $p);
        $value = ($p[2]) ? intval($p[2]) : "n/a";
        return $value;
    }

    public static function check_domain_age($remote_url) {
        $domain = lib::get_domain($remote_url);
        $request = "http://reports.internic.net/cgi/whois?whois_nic=" . $domain . "&type=domain";
        $data = self::get_page_data($request);
        preg_match('/Creation\s*Date:\s*([a-z0-9-]*)/si', $data, $p);

        if (!$p[1]) {
            $value = "Unknown";
        }
        else
            $value = strtotime($p[1]);
        return $value;
    }

    private static function get_page_data($url) {
        if (function_exists('curl_init')) {
            $ch = curl_init($url); // initialize curl with given url
            curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // add useragent
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // write the response to a variable
            if ((ini_get('open_basedir') == '') && (ini_get('safe_mode') == 'Off')) {
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // follow redirects if any
            }
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5); // max. seconds to execute
            curl_setopt($ch, CURLOPT_FAILONERROR, 1); // stop when it encounters an error
            return @curl_exec($ch);
        } else if ($handle = @fopen($url, "r")) {
            $data = '';
            while (!feof($handle)) {
                $data.= fread($handle, 1024);
            }
            fclose($handle);
            return $data;
        } else {
            return @file_get_contents($url);
        }
    }

}
