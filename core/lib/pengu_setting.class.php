<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: pengu_setting.class.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:59
##########################################################
 */


class pengu_setting extends pengu_tmp {

    private $name;
    function __construct($path = null, $name = null, $bezipped = false) {
        parent::__construct($path);
        if (!empty($name))
            $this->setSettingName($name);
        $this->setPrefix('setting_');
        if (is_bool($bezipped))
            $this->bezipped($bezipped);
    }

    function setSettingPrefix($prefix = 'setting_') {
        $this->setPrefix($prefix);
    }

    function setSettingPath($path) {
        $this->setPath($path);
    }

    function setSettingName($name) {
        if (!empty($name)) {
            $this->setKey($name);
        }
    }

    private function put($data) {
        if (is_array($data))
            $data = serialize($data);
        return $this->write($data);
    }

    private function import() {
        $data = $this->read();
        if (!empty($data))
            if ($data == serialize(false) || @unserialize($data) !== false)
                return unserialize($data);
            else
                return $data;
    }

    function get($varname = null) {
        $data = $this->import();
        if (is_array($data) && isset($data[$varname]))
            return $data[$varname];
        else if ($varname === null)
            return $data;
    }

    function save($vars, $values = null) {
        $data = array();
        if (is_array($vars) && is_array($values))
            $data = array_combine($vars, $values);
        else
        if (is_array($vars) && $values === null)
            $data = $vars;
        else
        if ($vars !== null && $values !== null)
            $data = array($vars => $values);
        else
        if ((is_string($vars) || is_numeric($vars)) && $values === null)
            return $this->put($vars);

        $oldData = $this->import();
        if (is_array($oldData))
            $data = array_merge($oldData, $data);
        return $this->put($data);
    }

    function delete($varname = null) {
        if (!empty($varname)) {
            $data = $this->import();
            if (isset($data[$varname])) {
                unset($data[$varname]);
                return $this->put($data);
            }
        } else {
            $this->remove();
        }
        return false;
    }

    function exists() {
        return $this->checkExists();
    }

}