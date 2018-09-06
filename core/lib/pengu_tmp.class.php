<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: pengu_tmp.class.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:59
##########################################################
 */


class pengu_tmp {

    private $path;
    private $key;
    private $wastePath;
    private $prefix = 'file_';
    private $extention = '.dat';
    private $file_data;
    private $file_attrs;
    private $zip;
    private $expireTime;
    private $sourceMtime = 0;

    function __construct($path = null, $prefix = 'file_', $ext = '.dat', $bezipped = false) {
        if (!empty($path))
            $this->setPath($path);
        else
            $this->setPath(ROOT_PATH . '/tmp/etc');
        $this->setPrefix($prefix);
        $this->setExtention($ext);
        if ($bezipped && class_exists('ZipArchive'))
            $this->zip = true;
    }

    function bezipped($bool = true) {
        if ($bool && class_exists('ZipArchive'))
            $this->zip = true;
        else
            $this->zip = false;
        return $this;
    }

    function expireTime($seconds) {
        if (intval($seconds) < 5)
            $seconds = 5;
        $this->expireTime = intval($seconds);
    }

    protected function sensetiveFileTrigger($filePath) {
        if (file_exists($filePath) && is_file($filePath) && $mtime = filemtime($filePath))
            $this->sourceMtime = $mtime;
    }

    private function checkIfValid() {
        if (empty($this->file_attrs))
            return false;
        /* check file exp time */
        if (intval($this->expireTime) > 0)
            if ($this->expireTime != $this->file_attrs['epxtime'] || filemtime($this->CachFilePath()) < time()) {
                $this->remove();
                return false;
            }

        /* check file exp time */
        if (intval($this->sourceMtime) > 0)
            if ($this->sourceMtime != $this->file_attrs['sourcetime']) {
                $this->remove();
                return false;
            }

        return true;
    }

    protected function CachFilePath() {
        if ($this->zip)
            return "{$this->path}/{$this->prefix}{$this->key}.zip";
        else
            return"{$this->path}/{$this->prefix}{$this->key}{$this->extention}";
    }

    protected function checkExists() {
        $this->grab();
        if ($this->checkIfValid())
            return true;
        return false;
    }

    protected function setKey($keyName) {
        $this->key = !empty($keyName) ? ( _is_Md5($keyName) ? $keyName : md5($keyName) ) : null;
    }

    protected function getKey() {
        return $this->key;
    }

    protected function setPath($path = null) {
        if (!empty($path)) {
            $this->path = rtrim($path, '/');
            $this->wastePath = $this->path . '/waste';
            if (!file_exists($this->path))
                rmkdir($path);
            if ($this->zip && !file_exists($this->wastePath))
                rmkdir($this->wastePath);
        }
    }

    function touchFile($expireTime) {
        $path = $this->CachFilePath();
        if (!empty($path) && file_exists($path))
            return touch($path, time() + intval($expireTime));
        return false;
    }

    protected function setPrefix($prefix) {
        $prefix = preg_replace('/[^a-zA-Z]/', '', $prefix);
        if (!empty($prefix))
            $this->prefix = $prefix . '_';
    }

    protected function setExtention($ext = '.dat') {
        if (!empty($ext))
            $this->extention = leftchar('.', $ext);
    }

    protected function write($filedata) {
        $ret = false;
        $path = null;
        $filedata = '{{%' . intval($this->expireTime) . ',' . intval($this->sourceMtime) . '%}}' . $filedata;
        if ($this->zip) {
            $tmp = tempnam($this->wastePath, 'tmp');
            $zip = new ZipArchive();
            $zip->open($tmp, ZipArchive::OVERWRITE);
            $zip->addFromString("data{$this->extention}", $filedata);
            $zip->close();
            $path = "{$this->path}/{$this->prefix}{$this->key}" . ".zip";
            if (file_exists($path))
                @unlink($path);
            if (!file_exists($path)) {
                $ret = rename($tmp, $path);
                permup($path);
            }
            else
                @unlink($tmp);
        } else {
            $path = "{$this->path}/{$this->prefix}{$this->key}" . $this->extention;
            if ($fp = @fopen($path, 'w+')) {
                if (flock($fp, LOCK_EX)) {
                    if (fwrite($fp, $filedata))
                        $ret = true;
                    flock($fp, LOCK_UN);
                }
                @fclose($fp);
                permup($path);
            }
        }
        if ($this->expireTime > 0 && $path)
            @touch($path, time() + intval($this->expireTime));
        return $ret;
    }

    private function grab() {
        $this->file_data = null;
        $this->file_attrs = null;
        if ($this->zip) {
            $zipFilePath = $this->CachFilePath();
            if (!file_exists($zipFilePath))
                return false;
            $zip = new ZipArchive;
            $westefolder = $this->wastePath . '/extract_' . mt_rand(100000, 999999);
            @rmkdir($westefolder);
            if ($zip->open($zipFilePath) === true) {
                $zip->extractTo($westefolder);
                $zip->close();
                $this->file_data = @file_get_contents($westefolder . "/data{$this->extention}");
                @unlink($westefolder . "/data{$this->extention}");
                @rmdir($westefolder);
                /* File Attributes */
                if ($position = strpos($this->file_data, '%}}')) {
                    $attrs = trim(substr($this->file_data, 0, $position + 3));
                    if (!empty($attrs)) {
                        @list($this->file_attrs['epxtime'], $this->file_attrs['sourcetime']) = @explode(',', substr($attrs, 3, strlen($attrs) - 6));
                    }
                    $this->file_data = substr($this->file_data, $position + 3);
                }
                //E
                return $this->file_data;
            }
        } else {
            $filepath = $this->CachFilePath();
            if (!file_exists($filepath))
                return false;
            $this->file_data = @file_get_contents($filepath);
            /* File Attributes */
            if ($position = strpos($this->file_data, '%}}')) {
                $attrs = trim(substr($this->file_data, 0, $position + 3));
                if (!empty($attrs)) {
                    @list($this->file_attrs['epxtime'], $this->file_attrs['sourcetime']) = @explode(',', substr($attrs, 3, strlen($attrs) - 6));
                }
                $this->file_data = substr($this->file_data, $position + 3);
            }
            //E
            return $this->file_data;
        }
    }

    protected function read() {
        if (!empty($this->file_data))
            return $this->file_data;
        $this->grab();
        if ($this->checkIfValid())
            return $this->file_data;
    }

    private function clearWestExtractFolders() {
        $directories = glob($this->wastePath . "/extract_*");
        if (!empty($directories))
            foreach ($directories as $dir) {
                if (@filemtime($dir) < time() - 10) {
                    @unlink("{$dir}/data{$this->extention}");
                    @rmdir($dir);
                }
            }
    }

    protected function remove() {
        $file = $this->CachFilePath();
        $this->file_attrs = null;
        $this->file_attrs = null;
        if (!empty($file) && file_exists($file)) {
            return @unlink($file);
        }
    }

    function __destruct() {
        if ($this->zip)
            $this->clearWestExtractFolders();
    }

}

class pengu_cache extends pengu_tmp {

    function __construct($path = null, $prefix = 'cache_', $ext = '.dat', $bezipped = false) {
        if (empty($path))
            $path = cache_path() . '/etc';
        parent::__construct($path, $prefix, $ext, $bezipped);
    }

    function setExpiration($seconds) {
        parent::expireTime($seconds);
    }

    function sensetiveFileTrigger($triggerFile) {
        parent::sensetiveFileTrigger($triggerFile);
    }

    function setCacheKey($key) {
        parent::setKey($key);
    }

    function getCacheKey() {
        return parent::getKey();
    }

    function isCached() {
        return parent::checkExists();
    }

    function write($data) {
        if (is_array($data))
            $data = serialize($data);
        return parent::write($data);
    }

    function read() {
        $data = parent::read();
        if (!empty($data))
            if ($data == serialize(false) || @unserialize($data) !== false)
                return unserialize($data);
            else
                return $data;
    }

    function delete() {
        return parent::remove();
    }

    function getCachePath() {
        return parent::CachFilePath();
    }

}