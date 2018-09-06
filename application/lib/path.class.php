<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: path.class.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:57
##########################################################
 */


class path {

    public static function get_extension($filename) {
        return self::fileinfo($filename, 'extension');
    }

    public static function get_dirname($filename) {
        return self::fileinfo($filename, 'dirname');
    }

    public static function get_filename($filename) {
        return self::fileinfo($filename, 'filename');
    }

    public static function get_basename($filename) {
        return self::fileinfo($filename, 'basename');
    }

    private static function fileinfo($path, $index = null) {
        if (($qpos = strpos($path, '?')) !== false)
            $path = substr($path, 0, $qpos);
        $arr = pathinfo($path);
        if ($index === null)
            return $arr;
        if (isset($arr[$index]))
            return $arr[$index];
    }

#############################################################

    static function get_newname($path, $filenamebody, $ext = null) {
        $p = $filenamebody . ($ext ? '.' . $ext : null);
        $i = 1;
        while (file_exists($path . '/' . $p)) {
            $p = $filenamebody . '_' . $i . ($ext ? '.' . $ext : null);
            $i++;
        }
        return $p;
    }

#############################################################

    public static function get_mime_type($file) {
        $mime_types = array(
            "pdf" => "application/pdf"
            , "exe" => "application/octet-stream"
            , "zip" => "application/zip"
            , "docx" => "application/msword"
            , "doc" => "application/msword"
            , "xls" => "application/vnd.ms-excel"
            , "ppt" => "application/vnd.ms-powerpoint"
            , "gif" => "image/gif"
            , "png" => "image/png"
            , "jpeg" => "image/jpg"
            , "jpg" => "image/jpg"
            , "mp3" => "audio/mpeg"
            , "wav" => "audio/x-wav"
            , "mpeg" => "video/mpeg"
            , "mpg" => "video/mpeg"
            , "mpe" => "video/mpeg"
            , "mov" => "video/quicktime"
            , "avi" => "video/x-msvideo"
            , "3gp" => "video/3gpp"
            , "css" => "text/css"
            , "jsc" => "application/javascript"
            , "js" => "application/javascript"
            , "php" => "text/html"
            , "htm" => "text/html"
            , "html" => "text/html"
        );
        return $mime_types[self::get_extension($file)];
    }

#############################################################

    public static function globr($sDir, $sPattern, $nFlags = NULL) {
        $aFiles = glob("$sDir/$sPattern", $nFlags);
        if (!function_exists('getOnLevelFolders')) {

            function getOnLevelFolders($sDir) {
                $i = 0;
                if (is_dir($sDir)) {
                    if ($rContents = opendir($sDir)) {
                        while ($sNode = readdir($rContents)) {
                            if (is_dir($sDir . '/' . $sNode)) {
                                if ($sNode != "." && $sNode != "..") {
                                    $aDirs[$i] = $sDir . '/' . $sNode;
                                    $i++;
                                }
                            }
                        }
                    }
                }
                if (isset($aDirs))
                    return $aDirs;
            }

        }
        $files = getOnLevelFolders($sDir);
        if (is_array($files)) {
            foreach ($files as $file) {
                $aSubFiles = self::globr($file, $sPattern, $nFlags);
                $aFiles = array_merge($aFiles, $aSubFiles);
            }
        }
        return $aFiles;
    }

    public static function RecursiveFindFiles($directory, $extensions) {
        if (!function_exists('glob_folder_recursive')) {

            function glob_folder_recursive($directory, &$directories = array()) {
                $ds = glob($directory, GLOB_ONLYDIR | GLOB_NOSORT);
                if (!empty($ds))
                    foreach ($ds as $folder) {
                        $directories[] = $folder;
                        glob_folder_recursive("{$folder}/*", $directories);
                    }
            }

        }

        glob_folder_recursive(rtrim($directory, '/'), $directories);
        $files = array();
        foreach ($directories as $directory) {
            if (is_string($extensions)) {
                $ds = glob(rtrim($directory, '/') . "/*.{$extensions}");
                if (!empty($ds))
                    foreach ($ds as $file) {
                        $files[] = $file;
                    }
            } elseif (is_array($extensions)) {
                foreach ($extensions as $ext) {
                    $ds = glob(rtrim($directory, '/') . "/*.{$extensions}");
                    if (!empty($ds))
                        foreach ($ds as $file) {
                            $files[$ext][] = $file;
                        }
                }
            }
        }
        return $files;
    }

    public static function RecursiveCopy($source, $dest, $permission = null) {
        if (!file_exists($source))
            return false;
        $sourceHandle = opendir($source);
        rmkdir($dest);

        while ($res = readdir($sourceHandle)) {
            if ($res == '.' || $res == '..')
                continue;

            if (is_dir($source . '/' . $res)) {
                self::RecursiveCopy($source . '/' . $res, $dest . '/' . $res, $permission);
            } else {
                copy($source . '/' . $res, $dest . '/' . $res);
                if (!empty($permission)) {
                    permup($dest . '/' . $res);
                }
            }
        }
    }

#############################################################

    public static function RecursiveDelete($path, $exct = '.*') {
        if (!file_exists($path))
            return false;
        $sourceHandle = opendir($path);
        while ($res = readdir($sourceHandle)) {

            if ($res == '.' || $res == '..')
                continue;

            if (!in_array($exct, array('.*', '*.*')))
                if (!is_dir($path . '/' . $res) && strpos($exct, self::fileinfo($path . '/' . $res)->extension) === false)
                    continue;

            if (is_dir($path . '/' . $res)) {
                if (!@rmdir($path . '/' . $res))
                    self::RecursiveDelete($path . '/' . $res);
            } else {
                unlink($path . '/' . $res);
            }
        }
        //@rmdir($path . '/');
    }

    #############################################################

    function file_get_contents_fopen($url) {
        $data = NULL;
        $dataHandle = fopen($url, "r");
        if ($dataHandle) {
            while (!feof($dataHandle)) {
                $data.= fread($dataHandle, 4096);
            }
            fclose($dataHandle);
        }
        return $data;
    }

    #############################################################

    public static function getFolderList($dir) {

        $retval = array();
        if (substr($dir, -1) != "/")
            $dir .= "/";

        $d = @dir($dir) or die("getFileList: Failed opening directory $dir for reading");
        while (false !== ($entry = $d->read())) {
            // skip hidden files
            if (in_array($entry[0], array('.', '..')))
                continue;
            if (is_dir($dir . $entry)) {
                $retval[] = arrayUtil::arrayToObject(array(
                            "base" => basename($dir . $entry),
                            "path" => $dir . $entry . "/",
                            "type" => filetype($dir . $entry),
                            "size" => 0,
                            "lastmod" => filemtime($dir . $entry)
                ));
            }
        }
        $d->close();

        return $retval;
    }

    ################################################################

    private static function remotefsize($url) {
        $sch = parse_url($url, PHP_URL_SCHEME);
        if (($sch != "http") && ($sch != "https") && ($sch != "ftp") && ($sch != "ftps")) {
            return false;
        }
        if (($sch == "http") || ($sch == "https")) {
            $headers = get_headers($url, 1);
            if ((!array_key_exists("Content-Length", $headers))) {
                return false;
            }
            return $headers["Content-Length"];
        }
        if (($sch == "ftp") || ($sch == "ftps")) {
            $server = parse_url($url, PHP_URL_HOST);
            $port = parse_url($url, PHP_URL_PORT);
            $path = parse_url($url, PHP_URL_PATH);
            $user = parse_url($url, PHP_URL_USER);
            $pass = parse_url($url, PHP_URL_PASS);
            if ((!$server) || (!$path)) {
                return false;
            }
            if (!$port) {
                $port = 21;
            }
            if (!$user) {
                $user = "anonymous";
            }
            if (!$pass) {
                $pass = "phpos@";
            }
            switch ($sch) {
                case "ftp":
                    $ftpid = ftp_connect($server, $port);
                    break;
                case "ftps":
                    $ftpid = ftp_ssl_connect($server, $port);
                    break;
            }
            if (!$ftpid) {
                return false;
            }
            $login = ftp_login($ftpid, $user, $pass);
            if (!$login) {
                return false;
            }
            $ftpsize = ftp_size($ftpid, $path);
            ftp_close($ftpid);
            if ($ftpsize == -1) {
                return false;
            }
            return $ftpsize;
        }
    }

    public static function get_file_size($fileSrc, $byte = false) {
        $size = 0;
        if (is_file($fileSrc))
            $size = @filesize($fileSrc);
        elseif (is_dir($fileSrc)) {
            $size = 0;
            $files = @self::globr($fileSrc, '*.*');
            if (is_array($files) && !empty($files))
                while (current($files)) {
                    $size+= @filesize(current($files));
                    next($files);
                }
        } elseif (validate::_is_URL($fileSrc))
            $size = @self::remotefsize($fileSrc);
        if (is_numeric($size))
            return $byte ? $size : convert::formatSizeUnits($size);
    }

    ################################################################

    public static function each_dir(&$path) {
        $firstchar = null;
        $lastchar = null;
        if (substr($path, 0, 1) == '/')
            $firstchar = '/';
        if (substr($path, -1, 1) == '/')
            $lastchar = '/';
        $path = trim($path, '/\\ ');
        $arr = explode('/', $path);
        if (count($arr) == 0)
            return false;
        $arr = array_reverse($arr);
        $ret = array_shift($arr);
        $arr = array_reverse($arr);
        $path = $firstchar . join('/', $arr) . $lastchar;

        return self::rightSlashes($path) . $ret;
    }

    ################################################################

    public static function leftSlashes($dir) {
        $trimed = ltrim($dir, '/');
        if (!empty($trimed))
            return '/' . $trimed;
    }

    public static function rightSlashes($dir) {
        $trimed = rtrim($dir, '/');
        if (!empty($trimed))
            return $trimed . '/';
    }

    ######################################################

    public static function filePermission($filepath) {
        return substr(sprintf('%o', @fileperms($filepath)), -4);
    }

    ######################################################

    public static function chmod_R($path, $filemode) {
        if (!is_dir($path))
            return chmod($path, $filemode);

        $dh = opendir($path);
        while (($file = readdir($dh)) !== false) {
            if ($file != '.' && $file != '..') {
                $fullpath = $path . '/' . $file;
                if (is_link($fullpath))
                    return false;
                elseif (!is_dir($fullpath))
                    if (!chmod($fullpath, $filemode))
                        return false;
                    elseif (!self::chmod_R($fullpath, $filemode))
                        return false;
            }
        }

        closedir($dh);

        if (chmod($path, $filemode))
            return true;
        else
            return false;
    }

}

