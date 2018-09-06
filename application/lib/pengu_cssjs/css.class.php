<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: css.class.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:57
##########################################################
 */


class css extends cssbase {

    public static $loadedJqueryUICss = false;
    public static $loadedBootStrapCss = false;
    public static $loadedBootStrapResponsiveCss = false;
    public static $loadedAlertCss = false;
    public static $loadedFlagsCss = false;

    // load jquery UI
    public static function loadJqueryuicss($forceLoad = false, $ClientCache = 604800, $jqVersion = '1.10.3', $UITheme = 'smoothness') {
        if (self::loadedJqueryuicss())
            return false;
        if (!$forceLoad)
            self::startPoint();


        if (!isLocalServer()) {
            self::createTag('https://ajax.googleapis.com/ajax/libs/jqueryui/' . $jqVersion . '/themes/start/jquery-ui.css', 'Load jquery UI ' . $UITheme . ' ' . $jqVersion, $forceLoad);
            self::$loadedJqueryUICss = true;
            return;
        }

        $FileSrc = static_url() . "/js/jquery-ui/{$jqVersion}/css/{$UITheme}/jquery-ui-{$jqVersion}.custom.min.css";
        $FilePath = static_path() . "/js/jquery-ui/{$jqVersion}/css/{$UITheme}/jquery-ui-{$jqVersion}.custom.min.css";


        if (!file_exists($FilePath))
            return false;
        self::creat($FileSrc, $ClientCache, array(CSS_FORCELOAD => $forceLoad));
        self::$loadedJqueryUICss = true;
    }

    public static function loadedJqueryuicss() {
        return self::$loadedJqueryUICss;
    }

    ////// Load BootStrap
    public static function loadBootStrap($forceLoad = false, $ClientCache = 604800, $bootVersion = '2.3.2', $rtl = false) {
        if (self::loadedBootStrap())
            return false;
        if (!$forceLoad)
            self::startPoint();

        $FileSrc = static_url() . '/bootstrap/' . $bootVersion . '/css/bootstrap' . ($rtl ? '-rtl' : null) . '.min.css';
        $FilePath = static_path() . '/bootstrap/' . $bootVersion . '/css/bootstrap' . ($rtl ? '-rtl' : null) . '.min.css';


        if (!file_exists($FilePath))
            return false;
        self::$loadedBootStrapCss = true;
        self::creat($FileSrc, $ClientCache, array(CSS_FORCELOAD => $forceLoad));
    }

    public static function loadedBootStrap() {
        return self::$loadedBootStrapCss;
    }

    public static function loadBootStrapResponsive($forceLoad = false, $ClientCache = 604800, $bootVersion = '2.3.2') {
        if (self::loadedBootStrapResponsive())
            return false;
        if (!$forceLoad)
            self::startPoint();

        $FileSrc = static_url() . '/bootstrap/' . $bootVersion . '/css/bootstrap-responsive.min.css';
        $FilePath = static_path() . '/bootstrap/' . $bootVersion . '/css/bootstrap-responsive.min.css';


        if (!file_exists($FilePath))
            return false;
        self::$loadedBootStrapResponsiveCss = true;
        self::creat($FileSrc, $ClientCache, array(CSS_FORCELOAD => $forceLoad));
    }

    public static function loadedBootStrapResponsive() {
        return self::$loadedBootStrapResponsiveCss;
    }

    public static function loadAlert($forceLoad = false, $ClientCache = 604800, $cssType = 'texter') {
        if (self::loadedAlert())
            return false;
        if (!$forceLoad)
            self::startPoint();

        $d1 = "/pengu_message/css/{$cssType}.css";
        $d2 = "/css/message/{$cssType}.css";


        if (file_exists(template_path() . $d2))
            $FileSrc = template_url() . $d2;
        elseif (file_exists(plugin_path() . $d1))
            $FileSrc = plugin_url() . $d1;
        else
            return false;

        self::$loadedAlertCss = true;
        self::creat($FileSrc, $ClientCache, array(CSS_FORCELOAD => $forceLoad));
    }

    public static function loadedAlert() {
        return self::$loadedAlertCss;
    }

    public static function loadFlags($forceLoad = false, $ClientCache = 604800, $cssType = 'famfamfam') {
        if (self::loadedFlags())
            return false;
        if (!$forceLoad)
            self::startPoint();

        $d1 = "/css/flags/{$cssType}/flags.css";


        if (file_exists(static_path() . $d1))
            $FileSrc = static_url() . $d1;
        else
            return false;

        self::$loadedFlagsCss = true;
        self::creat($FileSrc, $ClientCache, array(CSS_FORCELOAD => $forceLoad));
    }

    public static function loadedFlags() {
        return self::$loadedFlagsCss;
    }

}

