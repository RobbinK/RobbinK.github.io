<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: direction.class.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:59
##########################################################
 */


class direction {

    public static $currentController;
    public static $currentAction;
    public static $currentViewFolder;
    public static $currentViewFile;
    public static $currentViewFolderPath;
    public static $currentViewFolderUrl;
    public static $currentViewFile_groupFolder;
    public static $currentViewFilePath;
    public static $currentViewFileUrl;
    public static $ThemesFolder;
    public static $themeName;
    public static $themesPath;
    public static $themesUrl;
    public static $temeplatePath;
    public static $temeplateUrl;
    public static $appPath;
    public static $rootUrl;
    public static $rootPath;
    public static $tmpPath;
    public static $tmpUrl;
    public static $contentPath;
    public static $contentUrl;
    public static $pathExportSessionName = 'pathexplortdata';

    public static function setDefaults() {
        self::$rootUrl = ROOT_URL;
        self::$rootPath = ROOT_PATH;
        self::$appPath = ROOT_PATH . '/application';
        self::$tmpPath = ROOT_PATH . '/tmp';
        self::$tmpUrl = ROOT_URL . '/tmp';
        self::$contentPath = ROOT_PATH . '/content';
        self::$contentUrl = ROOT_URL . '/content';
        self::$ThemesFolder = self::leftslashes(DEFAUT_THEMES_DIR);
        self::$themeName = self::getDefaultThemplateName();
        self::refresh();
    }

    public static function init() {
        global $DispTarget, $DispAction;
        self::$currentController = $DispTarget;
        self::$currentAction = $DispAction;
        self::$currentViewFolder = self::$currentController . 'Controller';
        self::$currentViewFile = $DispAction . '.php';
        self::refresh();
    }

    public static function refresh() {
        self::$themesPath = self::$rootPath . self::leftslashes(self::$ThemesFolder);
        self::$themesUrl = self::$rootUrl . self::leftslashes(self::$ThemesFolder);
        self::$temeplatePath = self::$themesPath . self::leftslashes(self::$themeName);
        self::$temeplateUrl = self::$themesUrl . self::leftslashes(self::$themeName);
        self::$currentViewFolderPath = self::$temeplatePath . self::leftslashes(self::$currentViewFolder);
        self::$currentViewFolderUrl = self::$temeplateUrl . self::leftslashes(self::$currentViewFolder);
        self::$currentViewFilePath = self::$currentViewFolderPath . self::leftslashes(self::$currentViewFile_groupFolder) . self::leftslashes(self::$currentViewFile);
        self::$currentViewFileUrl = self::$currentViewFolderUrl . self::leftslashes(self::$currentViewFile_groupFolder) . self::leftslashes(self::$currentViewFile);
    }

    public static function leftslashes($dir) {
        $trimed = ltrim($dir, '/');
        if (!empty($trimed))
            return '/' . $trimed;
    }

    public static function rightslashes($dir) {
        $trimed = rtrim($dir, '/');
        if (!empty($trimed))
            return $trimed . '/';
    }

    public static function getDefaultThemplateName() { 
        if (defined('DefaultTemplate'))
            $theme = DefaultTemplate; // use setting
        return (empty($theme) ? 'default' : $theme);
    }

    public static function setThemesFolder($Dir) {
        self::$ThemesFolder = $Dir;
        self::refresh();
    }

    public static function setThemeName($themeName) {
        self::$themeName = $themeName;
        self::refresh();
    }

    public static function setViewFolder($FolderName) {
        self::$currentViewFolder = $FolderName;
        self::refresh();
    }

    public static function setViewFile($FileName) {
        self::$currentViewFile = $FileName;
        self::refresh();
    }

    public static function setViewFile_groupFolder($FolderName) {
        self::$currentViewFile_groupFolder = $FolderName;
        self::refresh();
    }

    public static function setContentDir($dir) {
        self::$contentPath = ROOT_PATH . $dir;
        self::$contentUrl = ROOT_URL . $dir;
    }

    public static function export() {
        $data = array(
            'currentController' => self::$currentController,
            'currentAction' => self::$currentAction,
            'currentViewFolder' => self::$currentViewFolder,
            'currentViewFile' => self::$currentViewFile,
            'currentViewFile_groupFolder' => self::$currentViewFile_groupFolder,
            'rootUrl' => self::$rootUrl,
            'rootPath' => self::$rootPath,
            'appPath' => self::$appPath,
            'tmpPath' => self::$tmpPath,
            'tmpUrl' => self::$tmpUrl,
            'contentPath' => self::$contentPath,
            'contentUrl' => self::$contentUrl,
            'ThemesFolder' => self::$ThemesFolder,
            'themeName' => self::$themeName,
        );
        $key = md5(serialize($data));
        $setting = new pengu_setting(ROOT_PATH . '/tmp/etc');
        $setting->setSettingPrefix('direction_');
        $setting->setSettingName($key);
        if (!$setting->exists())
            $setting->save('siteinfo', $data);
        return $key;
    }

    public static function import($data) {
        if (count($data) == 0)
            return;
        foreach ($data as $k => $v)
            self::$$k = $v;
        self::refresh();
    }

}

/* cache Path */
if (!file_exists(ROOT_PATH . '/tmp/cache')) {
    rmkdir(ROOT_PATH . '/tmp/cache');
    if (!file_exists(ROOT_PATH . '/tmp/cache/images'))
        rmkdir(ROOT_PATH . '/tmp/cache/images');
    if (!file_exists(ROOT_PATH . '/tmp/cache/lang'))
        rmkdir(ROOT_PATH . '/tmp/cache/lang');
    if (!file_exists(ROOT_PATH . '/tmp/cache/mysql'))
        rmkdir(ROOT_PATH . '/tmp/cache/mysql');
    if (!file_exists(ROOT_PATH . '/tmp/cache/etc'))
        rmkdir(ROOT_PATH . '/tmp/cache/etc');
}

//if (!file_exists(ROOT_PATH . '/tmp/etc'))
//    rmkdir(ROOT_PATH . '/tmp/etc'); 