<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: str.class.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:57
##########################################################
 */


class str {

    public static function textHighlight($text, $search, $styles = 'color:white;background-color:black', $casesensitive = false) {
        $modifier = ($casesensitive) ? null : 'i';
        if (is_array($search)) {
            array_walk($search, create_function('&$v,$k', '$v=preg_quote($v, \'/\');'));
            $quotedSearch = join('|', $search);    //(' ','|',$search);
        }
        else
            $quotedSearch = preg_quote($search, '/');
        $checkPattern = '/(' . $quotedSearch . ')/' . $modifier;
        $strReplacement = "<span style=\"{$styles}\">$1</span>";
        return preg_replace($checkPattern, $strReplacement, $text);
    }

    public static function summarize($str, $limit, $reverse = false,$sense=' ',$more='...') {
        if (empty($str))
            return $str;
        $i = 0;
        $res = null;
        $str = htmlspecialchars_decode($str);
        $str = strip_tags($str);
        $str = trim($str);
        if ($limit > strlen($str))
            $limit = strlen($str);
        $substr = null;
        $length = strlen($str);
        if ($reverse == false) {
            $pos = $limit;
            if ($limit<$length && @strrpos($str, $sense, abs($length - $limit) * -1))
                $pos = strrpos($str, $sense, abs($length - $limit) * -1);
            $substr = substr($str, 0, $pos);
            if ($str != $substr)
                $substr .= $more;
        } else { //reverse
            $pos = $length - $limit;
            $substr = substr($str, $pos);
            if ($limit<$length && @strpos($str, $sense, $length - $limit))
                $pos = strpos($str,$sense, $length - $limit);
            $substr = substr($str, $pos);
            if ($str != $substr)
                $substr = $more . $substr;
        }
        return $substr;
    }

    public static function str_between($str, $start, $end) {
        if (preg_match_all('/' . preg_quote($start) . '(.*)' . preg_quote($end) . '/', $str, $matches)) {
            return $matches[1];
        }
        // no matches
        return false;
    }

}