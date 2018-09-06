<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: mysqli_trace.class.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:59
##########################################################
 */


class pengu_mysqli_trace {

    private static $sql;
    private static $exectime;
    private static $error;
    private static $affrows;
    private static $numrows;
    private static $cached;
    private static $executemode;
    public static $logsavepath;

    public static function init($data) {

        self::$sql = isset($data['sql']) ? $data['sql'] : null;
        self::$exectime = isset($data['exectime']) ? $data['exectime'] : null;
        self::$error = isset($data['error']) ? $data['error'] : null;
        self::$affrows = isset($data['affrows']) ? $data['affrows'] : null;
        self::$numrows = isset($data['numrows']) ? $data['numrows'] : null;
        self::$executemode = isset($data['executemode']) ? $data['executemode'] : null;
        self::$cached = isset($data['cached']) ? $data['cached'] : null;

        if (!empty(self::$error)) {
            #----- agar khata dasht
            $out = array('sql' => self::$sql, 'minifysql' => self::minifySql(self::$sql), 'error' => self::$error);
        } else {
            #----- agar update|delete|ins|create bod
            if (self::$executemode == 'wor')
                $out = array('sql' => self::$sql, 'minifysql' => self::minifySql(self::$sql), 'affrows' => self::$affrows, 'exectime' => self::$exectime);
            else {
                #----- agar select bod va result dasht
                $out = array('sql' => self::$sql, 'minifysql' => self::minifySql(self::$sql), 'numrows' => self::$numrows, 'exectime' => self::$exectime);
                if (self::$cached)
                    $out = array_merge($out, array('cached' => true));
                if (self::_is_ajax_request())
                    $out = array_merge($out, array('isajax' => true));
            }
        }
        if (isset($out))
            self::saveToFile($out);
    }

    public static function minifySql($sql, $MaxChar = 150) {
        $sql = strtolower($sql);
        $out = preg_replace("#select\s(.*?\s*)\sfrom#i", "select ... from", $sql);
        return self::summarize($out, $MaxChar);
    }

    private static function summarize($str, $limit, $reverse = false, $sense = ' ') {
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
            if ($limit < $length && @strrpos($str, $sense, abs($length - $limit) * -1))
                $pos = strrpos($str, $sense, abs($length - $limit) * -1);
            $substr = substr($str, 0, $pos);
            if ($str != $substr)
                $substr .= '...';
        } else { //reverse
            $pos = $length - $limit;
            $substr = substr($str, $pos);
            if ($limit < $length && @strpos($str, $sense, $length - $limit))
                $pos = strpos($str, $sense, $length - $limit);
            $substr = substr($str, $pos);
            if ($str != $substr)
                $substr = '...' . $substr;
        }
        return $substr;
    }

    private static function _is_ajax_request() {
        return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
    }

    private static function saveToFile($row) {
        $logbulk = 100;
        if (defined('MYSQL_LOG_BULK') && MYSQL_LOG_BULK > 0)
            $logbulk = MYSQL_LOG_BULK;

        $data = array();
        $filename = self::$logsavepath . '/mysqllog_last.txt';

        if ($lastdata = @file_get_contents($filename))
            $data = unserialize($lastdata);
        if (sizeof($data) >= $logbulk) {
            $data = array_slice($data, -1 * ($logbulk - 1), $logbulk - 1);
        }
        $data[] = $row;
        if ($file = @fopen($filename, "w")) {
            fwrite($file, serialize($data));
            fclose($file);
            permup($filename);
        }
    }

}
