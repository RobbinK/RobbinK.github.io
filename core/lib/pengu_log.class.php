<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: pengu_log.class.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:59
##########################################################
 */


class pengu_log {

    private $path;
    private $prefix = 'file_';
    private $file_name;
    private $extention = '.dat';
    public $saved;
    static $save_GEO_data = false;
    static $enable = true;

    static function get_instance() {
        static $instance;
        if (!$instance)
            $instance = new pengu_log;
        return $instance;
    }

    function __construct($path = null, $prefix = 'rep_', $ext = '.log') {
        if (!empty($path))
            $this->setPath($path);
        else
            $this->setPath(ROOT_PATH . '/tmp/logs');
        $this->setPrefix($prefix);
        $this->setExtention($ext);
        $this->setFileName('executed-' . date('Y-m'));
    }

    static function enable_condition($condition) {
        if ($condition)
            self::$enable = true;
        else
            self::$enable = false;
    }

    function setPath($path) {
        if (!empty($path)) {
            $this->path = rtrim($path, '/');
            if (!file_exists($this->path))
                rmkdir($path);
        }
        return $this;
    }

    function setFileName($filename) {
        $filename = preg_replace('/[^0-9a-zA-Z_-]/', '', $filename);
        if (!empty($filename))
            $this->file_name = $filename;
        return $this;
    }

    function setPrefix($prefix) {
        $prefix = preg_replace('/[^a-zA-Z]/', '', $prefix);
        if (!empty($prefix))
            $this->prefix = $prefix . '_';
        return $this;
    }

    function setExtention($ext = '.log') {
        if (!empty($ext))
            $this->extention = leftchar('.', $ext);
        return $this;
    }

    function timer_start($name) {
        if (!self::$enable)
            return false;
        microtimer::start($name);
        $this->saved = false;
        return $this;
    }

    function timer_end($name) {
        if (!self::$enable)
            return false;
        return microtimer::stop($name);
        $this->saved = false;
        return $this;
    }

    function add_text($text) {
        if (!self::$enable || !is_string($text))
            return false;
        $this->log_data[] = $text;
        $this->saved = false;
        return $this;
    }

    function add_data($key, $val) {
        if (!self::$enable)
            return false;
        $this->log_data[] = array('key' => $key, 'val' => $val);
        $this->saved = false;
        return $this;
    }

    private static function db_get_instance() {
        static $db_instance;
        if (!$db_instance) {
            global $ConnectOptions;
            $db_instance = new pengu_db($ConnectOptions);
        }
        if ($db_instance->ping())
            return $db_instance;
        else
            return false;
    }

    function db_query($sql) {
        if (!self::$enable)
            return false;
        if (!$instance = self::db_get_instance())
            return false;
        $data = $instance->query($sql)->exec();
        if ($data)
            $this->add_data($sql, $data->allrows());
        return $this;
    }

    function db_getcount($table_name, $condition = null) {
        if (!self::$enable)
            return false;
        if (!$instance = self::db_get_instance())
            return false;
        $instance->settable($table_name)->select();
        if ($condition)
            $instance->where($condition);
        $this->add_data("get count from `{$table_name}`" . ($condition ? ' with a condition' : null), $instance->getcount());
        return $this;
    }

    function save() {
        if (!self::$enable)
            return false;
        $this->saved = true;
        $logfile = $this->path . '/' . $this->prefix . $this->file_name . $this->extention;
        if (!$fhandel = fopen($logfile, 'a'))
            return false;

        fwrite($fhandel, '**********************************************************' . PHP_EOL);
        fwrite($fhandel, '******************** ' . date('Y-m-d H:i:s') . ' ********************' . PHP_EOL . PHP_EOL);
        if (self::$save_GEO_data) {
            fwrite($fhandel, "GEO (ip=" . agent::get_client_ip() . ", tier=" . @agent::remote_info_tier() . ", country=" . @agent::remote_info_country() . ", code=" . @agent::remote_info_country_code() . ", referrer=" . @agent::remote_info_referrer() . ")" . PHP_EOL);
            fwrite($fhandel, PHP_EOL);
        }

        //-- show metric data
        $timer_result = microtimer::getResults();
        microtimer::remove();
        if (!empty($timer_result)) {
            fwrite($fhandel, '╔═════════════════════TIMER═════════════════════╗' . PHP_EOL);
            foreach ($timer_result as $k => $v)
                fwrite($fhandel, '║ → ' . $k . ' : ' . round($v, 5) . PHP_EOL);
            fwrite($fhandel, '╚═════════════════════════════════════════════════' . PHP_EOL);
            fwrite($fhandel, PHP_EOL);
        }
        //-- show log data
        if (isset($this->log_data)) {
            fwrite($fhandel, '╔═════════════════════ DATA ═════════════════════╗' . PHP_EOL);
            foreach ($this->log_data as $k => $v) {
                if (is_array($v)) {
                    $key = $v['key'];
                    $val = $v['val'];
                    if (is_numeric($val) || is_string($val)) {
                        fwrite($fhandel, '║ → ' . $key . ' : ' . $val . PHP_EOL);
                    } elseif (is_array($val) || is_object($val)) {
                        fwrite($fhandel, '║ ' . self::var_log($val, '→ ' . $key, null, ':') . PHP_EOL);
                    } elseif (is_bool($val)) {
                        fwrite($fhandel, '║ -> ' . ($val ? "True({$val})" : "False({$val})") . PHP_EOL);
                    }
                } else
                    fwrite($fhandel, '║ → ' . $v . PHP_EOL);
            }
            fwrite($fhandel, '╚═════════════════════════════════════════════════' . PHP_EOL);
            $this->log_data = null;
        }
        fwrite($fhandel, PHP_EOL . PHP_EOL);
        fclose($fhandel);
        return $this;
    }

    static function var_log(&$varInput, $var_name = '', $reference = '', $method = '=', $sub = false) {
        static $output;
        static $depth;
        if ($sub == false) {
            $output = null;
            $depth = 0;
            $reference = $var_name;
            $var = serialize($varInput);
            $var = unserialize($var);
        } else {
            ++$depth;
            $var = & $varInput;
        }
        // constants
        $nl = PHP_EOL;
        $block = 'a_big_recursion_protection_block';

        $c = $depth;
        $indent = '';
        while ($c -- > 0) {
            $indent .= '       ';
        }
        // if this has been parsed before
        if (is_array($var) && isset($var[$block])) {

            $real = & $var[$block];
            $name = & $var['name'];
            $type = gettype($real);
            $output .= $indent . $var_name . ' ' . $method . '& ' . ($type == 'array' ? 'Array' : get_class($real)) . ' ' . $name . $nl;

            // havent parsed this before
        } else {
            // insert recursion blocker
            $var = Array($block => $var, 'name' => $reference);
            $theVar = & $var[$block];

            // print it out
            $type = gettype($theVar);
            switch ($type) {
                case 'array' :
                    $output .= $indent . $var_name . ' ' . $method . ' Array (' . $nl;
                    $keys = array_keys($theVar);
                    foreach ($keys as $name) {
                        $value = &$theVar[$name];
                        self::var_log($value, $name, $reference . '["' . $name . '"]', '=', true);
                    }
                    $output .= $indent . ')' . $nl;
                    break;
                case 'object' :
                    $output .= $indent . $var_name . ' = ' . get_class($theVar) . ' {' . $nl;
                    foreach ($theVar as $name => $value) {
                        self::var_log($value, $name, $reference . '->' . $name, '->', true);
                    }
                    $output .= $indent . '}' . $nl;
                    break;
                case 'string' :
                    $output .= $indent . $var_name . ' ' . $method . ' "' . $theVar . '"' . $nl;
                    break;

                default :
                    $output .= $indent . $var_name . ' ' . $method . ' (' . $type . ') ' . $theVar . $nl;
                    break;
            }
            // $var=$var[$block];
        }
        --$depth;
        if ($sub == false)
            return $output;
    }

    function __destruct() {
        if ($this->saved === false && self::$enable)
            $this->save();
    }

}

function pengu_log() {
    return pengu_log::get_instance();
}

function pengu_log_condition($condition) {
    pengu_log::enable_condition($condition);
}