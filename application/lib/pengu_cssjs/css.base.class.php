<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: css.base.class.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:57
##########################################################
 */


event::register_onLoadView(array('cssbase', 'PlaceCssFilesInView'));
if (!defined('CSS_MinExtention'))
    define('CSS_MinExtention', true);
define('CSS_FORCELOAD', 'forceload');

class cssbase
{

    private static $loadedFiles = array();
    public static $cdn = null;
    public static $cdn_zone = null;

    protected static function creat($CssFiles, $ClientCache, $config = array())
    {
        $root = urlencode(base64_encode(ROOT_PATH));
        self::export_siteinfo();
        $forceshow = isset($config[CSS_FORCELOAD]) ? $config[CSS_FORCELOAD] : false;

        if (is_array($CssFiles)) {
            foreach ($CssFiles as $css) {
                if (!preg_match('/^.*(?:\.css\.min|\.min\.css)$/i', $css) && CSS_MinExtention)
                    $css .= '.min';
                self::CDN($css);
                self::createTag(
                    $css .
                    (CSS_MinExtention ? '?skey=' . register::Get('siteinfokey') : null), $forceshow);
            }
        } else {
            if (!preg_match('/^.*(?:\.css\.min|\.min\.css)$/i', $CssFiles) && CSS_MinExtention)
                $CssFiles .= '.min';
            self::CDN($CssFiles);
            self::createTag(
                $CssFiles .
                (CSS_MinExtention ? '?skey=' . register::Get('siteinfokey') : null), $forceshow);
        }
    }

    private static function CDN(&$src)
    {
        $domain = get_domain($src, false);
        $subdomain=null;
        if (strpos( get_domain($src, true),'.' . $domain)!==false)
            $subdomain = str_replace('.' . $domain, '', get_domain($src, true));

        if (!empty(self::$cdn_zone) && self::$cdn_zone != $domain) {
            $src = str_replace($domain, self::$cdn_zone, $src);
        }

        if (!empty(self::$cdn)) {
            $src = preg_replace("/www\./i", "", $src);
            if ($subdomain)
                $src = str_replace($subdomain , self::$cdn, $src);
            else {
                if (preg_match('/^https?\:\/\//i', $src))
                    $src = str_replace('://', "://" . self::$cdn . '.', $src);
                else
                    $src = self::$cdn . '.' . $src;
            }
        }
    }

    protected static function createTag($src, $forceShow = false)
    {
        ob_start();
        echo "\n";
        echo '<link rel="stylesheet" type="text/css" href="' . $src . '" />';
        $content = ob_get_clean();
        if ($forceShow)
            echo $content;
        else
            register::Merge('PenguCssPlace', $content);
    }

    protected static function startPoint()
    {
        if (!register::Exist('PenguCssPlace')) {
            register::Set('PenguCssPlace', array());
            echo '<!--::PENGU_CSSPLACE::-->';
        }
    }

    public static function load($CssFiles = null, array $config = array(CSS_FORCELOAD => false), $ClientCache = 604800)
    {
        if (isset($config[CSS_FORCELOAD]) && !$config[CSS_FORCELOAD])
            self::startPoint();

        if (!$CssFiles)
            return;

        if (is_array($CssFiles))
            foreach ($CssFiles as $file)
                self::Setloadedfiles($file);
        else
            self::Setloadedfiles($CssFiles);

        self::creat($CssFiles, $ClientCache, $config);
    }

    public static function loadFromPath($Path = NULL, array $config = array(CSS_FORCELOAD => false), $ClientCache = 604800)
    {
        if (isset($config[CSS_FORCELOAD]) && !$config[CSS_FORCELOAD])
            self::startPoint();

        if (!$Path)
            return;

        $Path .= '/';
        $CssFiles = glob($Path . '*.css');
        if (empty($CssFiles))
            return false;
        foreach ($CssFiles as $file)
            self::Setloadedfiles($file);
        self::creat($CssFiles, $ClientCache, $config);
    }

    private static function Setloadedfiles($CssFileName)
    {
        if (!in_array($CssFileName, self::$loadedFiles))
            self::$loadedFiles = array_merge(self::$loadedFiles, array($CssFileName));
    }

    public static function loaded($CssFileName)
    {
        if (in_array($CssFileName, self::$loadedFiles))
            return true;
        return false;
    }

    private static function export_siteinfo()
    {
        if (validate::_is_ajax_request())
            return false;
        if (!register::Exist('siteinfokey')) {
            register::Set('siteinfokey', direction::export());
        }
    }

    public function removeCachedCss()
    {
        if (validate::_is_ajax_request())
            return false;
        if (DEVELOP)
            path::RecursiveDelete(cache_path() . '/css', '*.dat');
    }

    function PlaceCssFilesInView(&$ViewContent)
    {
        if (validate::_is_ajax_request())
            return;
        $place = '<!--::PENGU_CSSPLACE::-->';
        $ac = register::Get('PenguCssPlace');
        if (count($ac))
            $Csses = trim(join("", register::Get('PenguCssPlace')));
        if (empty($Csses))
            return;
        $Csses = "\n" . $Csses . "\n";
        if (preg_match("/\<\/head\>/i", $ViewContent)) {
            $ViewContent = str_replace($place, '', $ViewContent);
            $ViewContent = preg_replace("/\<\/head\>/i", "{$Csses}</head>", $ViewContent);
            register::Destroy('PenguCssPlace');
        } else if (preg_match("/\<head\>/i", $ViewContent)) {
            $ViewContent = str_replace($place, '', $ViewContent);
            $ViewContent = preg_replace("/\<head\>/i", "<head>{$Csses}", $ViewContent);
            register::Destroy('PenguCssPlace');
        } else if (preg_match("/\<body\>/i", $ViewContent)) {
            $ViewContent = str_replace($place, '', $ViewContent);
            $ViewContent = preg_replace("/\<body\>/i", "<body>{$Csses}", $ViewContent);
            register::Destroy('PenguCssPlace');
        } else {
            if (preg_match("/" . preg_quote($place) . "/i", $ViewContent)) {
                $ViewContent = preg_replace("/{$place}/i", $Csses, $ViewContent);
                register::Destroy('PenguCssPlace');
            }
        }
    }

}
