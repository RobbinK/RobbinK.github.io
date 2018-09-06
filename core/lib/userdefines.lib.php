<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: userdefines.lib.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:59
##########################################################
 */


define('SITE_ROOT_URL', 'root_url');
define('SITE_ROOT_PATH', 'root_path');
define('SITE_APP_PATH', 'app_path');
define('SITE_CONFIG_PATH', 'config_path');
define('SITE_EMAILS_PATH', 'emails_path');
define('SITE_EMAILS_URL', 'emails_url');
define('SITE_LIB_PATH', 'lib_path');
define('SITE_LIB_URL', 'lib_url');
define('SITE_PLUGIN_PATH', 'plugin_path');
define('SITE_PLUGIN_URL', 'plugin_url');
define('SITE_TMP_PATH', 'tmp_path');
define('SITE_TMP_URL', 'tmp_url');
define('SITE_CACHE_PATH', 'cache_path');
define('SITE_CACHE_URL', 'cache_url');
define('SITE_CONTENT_PATH', 'content_path');
define('SITE_CONTENT_URL', 'content_url');
define('SITE_STATIC_PATH', 'static_path');
define('SITE_STATIC_URL', 'static_url');
define('SITE_CONTROLS_PATH', 'controls_path');
define('SITE_CONTROLLER', 'controller');
define('SITE_ACTION', 'action');
define('SITE_THEMES_PATH', 'themes_path');
define('SITE_THEMES_URL', 'themes_url');
define('SITE_VIEWFOLDER_PATH', 'viewfolder_path');
define('SITE_VIEWFOLDER_URL', 'viewfolder_url');
define('SITE_VIEWFILE_PATH', 'viewfile_path');
define('SITE_VIEWFILE_URL', 'viewfile_url');
define('SITE_VIEWFILE_NAME', 'viewfile_name');
define('SITE_TEMPLATE_NAME', 'template_name');
define('SITE_TEMPLATE_PATH', 'template_path');
define('SITE_TEMPLATE_URL', 'template_url');

function siteinfo($index) {
    switch (strtolower($index)) {
        case SITE_ROOT_URL: return direction::$rootUrl;
            break;
        case SITE_ROOT_PATH: return direction::$rootPath;
            break;
        case SITE_APP_PATH: return direction::$appPath;
            break;
        case SITE_CONFIG_PATH: return direction::$rootPath . direction::leftslashes(CONFIG_DIR);
            break;
        case SITE_EMAILS_PATH: return direction::$rootPath . '/application/emails';
            break;
        case SITE_EMAILS_URL: return direction::$rootPath . '/application/emails';
            break;
        case SITE_LIB_PATH: return direction::$rootPath . '/application/lib';
            break;
        case SITE_LIB_URL: return direction::$rootUrl . '/application/lib';
            break;
        case SITE_PLUGIN_PATH: return direction::$rootPath . '/application/plugin';
            break;
        case SITE_PLUGIN_URL: return direction::$rootUrl . '/application/plugin';
            break;
        case SITE_STATIC_PATH: return direction::$rootPath . '/application/static';
            break;
        case SITE_STATIC_URL: return direction::$rootUrl . '/application/static';
            break;
        case SITE_TMP_PATH: return direction::$tmpPath;
            break;
        case SITE_TMP_URL: return direction::$tmpUrl;
            break;
        case SITE_CACHE_PATH: return direction::$tmpPath . '/cache';
            break;
        case SITE_CACHE_URL: return direction::$tmpUrl . '/cache';
            break;
        case SITE_CONTENT_PATH: return direction::$contentPath;
            break;
        case SITE_CONTENT_URL: return direction::$contentUrl;
            break;
        case SITE_ACTION: return direction::$currentAction;
            break;
        case SITE_CONTROLLER: return direction::$currentController;
            break;
        case SITE_CONTROLS_PATH: return direction::$appPath . '/controls';
            break;
        case SITE_THEMES_PATH: return direction::$themesPath;
            break;
        case SITE_THEMES_URL: return direction::$themesUrl;
            break;
        case SITE_VIEWFOLDER_PATH: return direction::$currentViewFolderPath;
            break;
        case SITE_VIEWFOLDER_URL: return direction::$currentViewFolderUrl;
            break;
        case SITE_VIEWFILE_PATH: return direction::$currentViewFilePath;
            break;
        case SITE_VIEWFILE_URL: return direction::$currentViewFileUrl;
            break;
        case SITE_VIEWFILE_NAME: return direction::$currentViewFile;
            break;
        case SITE_TEMPLATE_NAME: return direction::$themeName;
            break;
        case SITE_TEMPLATE_PATH: return direction::$temeplatePath;
            break;
        case SITE_TEMPLATE_URL: return direction::$temeplateUrl;
            break;
    }
}

function root_path() {
    return siteinfo(SITE_ROOT_PATH);
}

function root_url() {
    return siteinfo(SITE_ROOT_URL);
}

function app_path() {
    return siteinfo(SITE_APP_PATH);
}

function config_path() {
    return siteinfo(SITE_CONFIG_PATH);
}

function lib_path() {
    return siteinfo(SITE_LIB_PATH);
}

function lib_url() {
    return siteinfo(SITE_LIB_URL);
}

function plugin_path() {
    return siteinfo(SITE_PLUGIN_PATH);
}

function plugin_url() {
    return siteinfo(SITE_PLUGIN_URL);
}

function static_path() {
    return siteinfo(SITE_STATIC_PATH);
}

function static_url() {
    return siteinfo(SITE_STATIC_URL);
}

function tmp_path() {
    return siteinfo(SITE_TMP_PATH);
}

function tmp_url() {
    return siteinfo(SITE_TMP_URL);
}

function cache_path() {
    return siteinfo(SITE_CACHE_PATH);
}

function cache_url() {
    return siteinfo(SITE_CACHE_URL);
}

function content_path() {
    return siteinfo(SITE_CONTENT_PATH);
}

function content_url() {
    return siteinfo(SITE_CONTENT_URL);
}

function action() {
    return siteinfo(SITE_ACTION);
}

function controller() {
    return siteinfo(SITE_CONTROLLER);
}

function route_name() {
    global $route;
    return $route->getName();
}

function controller_path() {
    return siteinfo(SITE_CONTROLS_PATH);
}

function themes_path() {
    return siteinfo(SITE_THEMES_PATH);
}

function themes_url() {
    return siteinfo(SITE_THEMES_URL);
}

function viewfolder_path() {
    return siteinfo(SITE_VIEWFOLDER_PATH);
}

function viewfolder_url() {
    return siteinfo(SITE_VIEWFOLDER_URL);
}

function viewfile_path() {
    return siteinfo(SITE_VIEWFILE_PATH);
}

function viewfile_url() {
    return siteinfo(SITE_VIEWFILE_URL);
}

function viewfile_name() {
    return siteinfo(SITE_VIEWFILE_NAME);
}

function template_name() {
    return siteinfo(SITE_TEMPLATE_NAME);
}

function template_path() {
    return siteinfo(SITE_TEMPLATE_PATH);
}

function template_url() {
    return siteinfo(SITE_TEMPLATE_URL);
}