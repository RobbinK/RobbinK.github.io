<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: Setting.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:57
##########################################################
 */


class Setting extends Model {

    protected $_table = "abs_settings";
    private static $instance;

    private static function createInstance() {
        if (!self::$instance) {
            $className = get_class();
            self::$instance = new $className;
        }
    }

    function exec(array $params = null) {
        $data = parent::exec($params);
        if ($data)
            return $data;
        elseif (parent::errorno()) {
            _show_mysql_error(parent::lastsql(), parent::lasterror());
        }
    }

    public static function save_value($cat, $vars, $values = null) {
        self::createInstance();
        $error = 0;
        $data = array();
        if (is_array($vars) && is_array($values))
            $data = array_combine($vars, $values);
        else
        if (is_array($vars) && $values === null)
            $data = $vars;
        else
        if ($vars !== null && $values !== null)
            $data = array($vars => $values);

        $keys = array_keys($data);
        array_walk($keys, create_function('&$value', '$value="\'{$value}\'";'));
        $strkeys = join(',', $keys);
        $exist = self::$instance->select("`key`")->where(array("`key` in ({$strkeys})"))->exec()->allrows();
        if (!empty($data))
            foreach ($data as $k => $v) {
                $find = false;
                if (!is_string($v) && !is_numeric($v))
                    $v = serialize($v);
                if (!empty($exist))
                    foreach ($exist as $key => $value)
                        if (preg_match("/" . preg_quote($k) . "/i", $value['key']))
                            $find = true;
                if ($find == true)
                    $return = self::$instance->update(array('val' => $v))->where(array('key' => strtolower($k)))->exec();
                else
                    $return = self::$instance->insert(array('cat' => strtolower($cat), 'key' => strtolower($k), 'val' => $v))->exec();
                if ($return === false)
                    $error = 1;
            }
        self::delete_all_cache();
        if (!$error)
            return true;
    }

    public static function get_data($key = null, $index = null) {
        static $setting_data;
        if (!$setting_data) {
            $s = new pengu_setting(cache_path() . '/etc/');
            $s->setSettingPrefix('configuration_');
            $s->setSettingName('mysitesetting');
            if ($s->exists()) {
                $setting_data = $s->get();
            } else {
                self::createInstance();
                $setting_data = self::$instance->select()->exec()->allrows();
                $s->save($setting_data);
            }
        }
        if ($key === null)
            return $setting_data;
        $found = null;
        if (!empty($setting_data))
            foreach ($setting_data as $data) {
                if (preg_match("/^" . preg_quote($key) . "$/i", $data['key'])) {
                    $found = $data;
                    foreach ($found as &$v) {
                        if (validate::_is_Serialized($v)) {
                            $v = unserialize($v);
                        }
                    }
                    if ($index && isset($found[$index]))
                        return $found[$index];
                    return $found;
                }
            }

        return $found;
    }

    /*
     *  used for interface 
     */

    public static function get_default_value($key) {
        static $default_setting;
        if (!isset($default_setting)) {
            $setting_file = root_path() . '/' . DEFAUT_THEMES_DIR . '/' . template_name() . '/theme_setting.php';
            if (file_exists($setting_file)) {
                include($setting_file);
            }
        }
        if (isset($default_setting[$key]))
            return $default_setting[$key];
        return setting::get_data($key, 'val');
    }

    public static function delete_all_cache() {
        $s = new pengu_setting(cache_path() . '/etc/');
        $s->setSettingPrefix('configuration_');
        $s->setSettingName('mysitesetting');
        return $s->delete();
    }

}

function _get_theme_setting($key) {
    //--1
    $data = setting::get_data($key, 'val');
    if ($data !== null)
        return $data;
    //--2
    $themename = !empty($_GET['abtheme']) ? $_GET['abtheme'] : DefaultTemplate;
    $path = root_path() . leftchar('/', DEFAUT_THEMES_DIR) . leftchar('/', $themename) . '/theme_setting.php';
    if (file_exists($path)) {
        global $themeConfiguration;
        if (!isset($themeConfiguration))
            $themeConfiguration = include  $path;
        if (isset($themeConfiguration) && isset($themeConfiguration[$key]['default']))
            return $themeConfiguration[$key]['default'];
    }
    //--2 last themes!
    if (file_exists($path)) {
        include_once $path;
        if (isset($default_setting) && isset($default_setting[$key]))
            return $default_setting[$key];
    }
}
