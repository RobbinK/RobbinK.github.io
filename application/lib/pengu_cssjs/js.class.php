<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: js.class.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:57
##########################################################
 */


class js extends jsbase {

    public static $loadedJquery = false;
    public static $loadedJquery_migrate = false;
    public static $loadedJqueryUi = false;
    public static $loadedBootStrap = false;

    ////// load jquery UI
    public static function loadJqueryui($forceLoad = false, $ClientCache = 604800, $jqVersion = '1.10.3') {
        if (self::loadedJqueryUI())
            return false;
        if (!$forceLoad)
            self::startPoint();

        if (!isLocalServer()) {
            self::createTag('https://ajax.googleapis.com/ajax/libs/jqueryui/' . $jqVersion . '/jquery-ui.min.js', 'Load jquery UI ' . $jqVersion, $forceLoad);
            self::$loadedJqueryUi = true;
            return;
        }
        $FileSrc = static_path() . "/js/jquery-ui/$jqVersion/jquery-ui-{$jqVersion}.min.js";
        $FilePath = static_path() . "/js/jquery-ui/$jqVersion/jquery-ui-{$jqVersion}.min.js";

        if (!file_exists($FilePath))
            return false;
        self::creat($FileSrc, $ClientCache, array('exec' => false, JS_FORCELOAD => $forceLoad));
        self::$loadedJqueryUi = true;
    }

    public static function loadedJqueryUI() {
        return self::$loadedJqueryUi;
    }

    ////// load jquery
    public static function loadJquery($forceLoad = false, $ClientCache = 604800, $jqVersion = '1.10.2') {
        if (self::loadedJquery())

        return false;
        if (!$forceLoad)
            self::startPoint();
        if (!isLocalServer()) {
            self::createTag('https://ajax.googleapis.com/ajax/libs/jquery/' . $jqVersion . '/jquery.min.js', 'Load jquery ' . $jqVersion, $forceLoad);
            self::$loadedJquery = true;
            return;
        }
        $FileSrc = static_path() . '/js/jquery/jquery-' . $jqVersion . '.min.js';
        $FilePath = static_path() . '/js/jquery/jquery-' . $jqVersion . '.min.js';


        if (!file_exists($FilePath))
            return false;
        self::creat($FileSrc, $ClientCache, array('exec' => false, JS_FORCELOAD => $forceLoad));
        self::$loadedJquery = true;
    }

    public static function loadedJquery() {
        return self::$loadedJquery;
    }

    //////// load jquery migrate 
    public static function loadjquery_migrate($forceLoad = false, $ClientCache = 604800, $mgVersion = '1.2.1') {
        if (self::loadedJquery_migrate())
            return false;
        if (!$forceLoad)
            self::startPoint();

        if (!isLocalServer()) {
            self::createTag("http://code.jquery.com/jquery-migrate-$mgVersion.min.js", 'Load jquery migrate ' . $mgVersion, $forceLoad);
            self::$loadedJquery_migrate = true;
            return;
        }
        $FileSrc = static_path() . '/js/jquery/jquery-migrate-' . $mgVersion . '.min.js';
        $FilePath = static_path() . '/js/jquery/jquery-migrate-' . $mgVersion . '.min.js';


        if (!file_exists($FilePath))
            return false;
        self::creat($FileSrc, $ClientCache, array('exec' => false, JS_FORCELOAD => $forceLoad));
        self::$loadedJquery_migrate = true;
    }

    public static function loadedJquery_migrate() {
        return self::$loadedJquery_migrate;
    }

    //////// load bootstrap
    public static function loadBootStrap($forceLoad = false, $ClientCache = 604800, $bootVersion = '2.3.2') {
        if (self::loadedBootStrap())
            return false;
        if (!$forceLoad)
            self::startPoint();

        $FileSrc = static_path() . '/bootstrap/' . $bootVersion . '/js/bootstrap.min.js';
        $FilePath = static_path() . '/bootstrap/' . $bootVersion . '/js/bootstrap.min.js';


        if (!file_exists($FilePath))
            return false;
        self::$loadedBootStrap = true;
        self::creat($FileSrc, $ClientCache, array('exec' => false, JS_FORCELOAD => $forceLoad));
    }

    public static function loadedBootStrap() {
        return self::$loadedBootStrap;
    }

    ///////
}

