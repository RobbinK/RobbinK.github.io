<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: js.base.class.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:57
##########################################################
 */

event::register_onLoadView(array('jsbase', 'PlaceJsFilesInView'));

define('JS_FORCELOAD', 'forceload');
define('JS_MINIFY', 'minify');
define('JS_EXEC', 'exec');

class jsbase
{

    private static $loadedFiles = array();
    public static $cdn = null;
    public static $cdn_zone = null;

    protected static function creat($JsFiles, $ClientCache, $config)
    {
        $comment = null;
        //==Comment
        if (DEVELOP) {
            $x = array();
            if (is_array($JsFiles))
                foreach ($JsFiles as $file)
                    $x[] = path::get_basename($file);
            else if (!empty($JsFiles))
                $x[] = path::get_basename($JsFiles);
            $comment .= join(' , ', $x);
        }
        //==Comment
        //=== saveTargetSetting
        $targetkey = md5(serialize($JsFiles));
        $setting = new pengu_setting(ROOT_PATH . '/tmp/etc');
        $setting->setSettingPrefix('cssjs_');
        $setting->setSettingName($targetkey);
        if (!$setting->exists())
            $setting->save('targets', $JsFiles);
        //=====================
        self::export_siteinfo();

        $forceshow = isset($config[JS_FORCELOAD]) ? $config[JS_FORCELOAD] : false;

        static $u;
        if (!isset($u)) {
            $u = lib_url() . '/pengu_cssjs/js.pengudriver.php';
            self::CDN($u);
        }
        self::createTag(
            $u .
            '?tkey=' . $targetkey .
            /* '&ccache=1'. */
            '&skey=' . register::Get('siteinfokey') .
            '&config=' . base64::encode($config)
            , $comment, $forceshow);
    }

    private static function CDN(&$src)
    {
        $domain = get_domain($src, false);
        $subdomain = null;
        if (strpos(get_domain($src, true), '.' . $domain) !== false)
            $subdomain = str_replace('.' . $domain, '', get_domain($src, true));

        if (!empty(self::$cdn_zone) && self::$cdn_zone != $domain) {
            $src = str_replace($domain, self::$cdn_zone, $src);
        }

        if (!empty(self::$cdn)) {
            $src = preg_replace("/www\./i", "", $src);
            if ($subdomain)
                $src = str_replace($subdomain, self::$cdn, $src);
            else {
                if (preg_match('/^https?\:\/\//i', $src))
                    $src = str_replace('://', "://" . self::$cdn . '.', $src);
                else
                    $src = self::$cdn . '.' . $src;
            }
        }
    }

    protected static function createTag($src, $comment, $forceShow = false)
    {
        ob_start();
        if (!empty($comment))
            echo "\n<!-- " . $comment . " -->\n";
        echo '<script type="text/javascript" src="' . $src . '"></script>';
        $content = ob_get_clean();
        if ($forceShow)
            echo $content;
        else
            register::Merge('PenguJsPlace', $content);
    }

    protected static function startPoint()
    {
        if (!register::Exist('PenguJsPlace')) {
            register::Set('PenguJsPlace', array());
            echo '<!--::PENGU_JSPLACE::-->';
        }
    }

    public static function load($JsFiles = null, $config = array(JS_FORCELOAD => false), $ClientCache = 604800)
    {
        if (isset($config[JS_FORCELOAD]) && !$config[JS_FORCELOAD])
            self::startPoint();

        if (!isset($config['minify']))
            $config['minify'] = false;

        if (!$JsFiles)
            return;

        if (is_array($JsFiles))
            foreach ($JsFiles as $file) {
                if (!file_exists($file))
                    exit("The file {$file} is not loaded");
                self::Setloadedfiles($file);
            }
        else {
            if (!file_exists($JsFiles))
                exit("The file {$JsFiles} is not loaded");
            self::Setloadedfiles($JsFiles);
        }
        self::creat($JsFiles, $ClientCache, $config);
    }

    public static function loadFromPath($Path = NULL, array $config = array(JS_FORCELOAD => false), $ClientCache = 604800)
    {
        if (isset($config[JS_FORCELOAD]) && !$config[JS_FORCELOAD])
            self::startPoint();

        if (!isset($config['minify']))
            $config['minify'] = false;

        if (!$Path)
            return;

        $Path .= '/';
        $JsFiles = glob($Path . '*.js');
        if (empty($JsFiles))
            return false;
        foreach ($JsFiles as $file)
            self::Setloadedfiles($file);
        self::creat($JsFiles, $ClientCache, $config);
    }

    private static function Setloadedfiles($JsFileName)
    {
        if (!in_array($JsFileName, self::$loadedFiles))
            self::$loadedFiles = array_merge(self::$loadedFiles, array($JsFileName));
    }

    public static function loaded($JsFileSrc)
    {
        if (in_array($JsFileSrc, self::$loadedFiles))
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

    function removeCachedJs()
    {
        if (validate::_is_ajax_request())
            return false;
        if (DEVELOP)
            path::RecursiveDelete(cache_path() . '/js', '*.dat');
    }

    function PlaceJsFilesInView(&$ViewContent)
    {
        if (validate::_is_ajax_request())
            return;
        $place = '<!--::PENGU_JSPLACE::-->';
        $aj = register::Get('PenguJsPlace');
        if (count($aj))
            $jses = @trim(join('', $aj));
        if (empty($jses))
            return;
        $jses = "\n" . $jses . "\n";
        if (preg_match("/<\/head>/i", $ViewContent)) {
            $ViewContent = str_replace($place, '', $ViewContent);
            $ViewContent = preg_replace("/(<script[^\>]*>[\s\S]*)?<\/head>/i", "{$jses}$1</head>", $ViewContent);
            register::Destroy('PenguJsPlace');
        } else if (preg_match("/<head>/i", $ViewContent)) {
            $ViewContent = str_replace($place, '', $ViewContent);
            $ViewContent = preg_replace("/<head>/i", "<head>{$jses}", $ViewContent);
            register::Destroy('PenguJsPlace');
        } else if (preg_match("/<body>/i", $ViewContent)) {
            $ViewContent = str_replace($place, '', $ViewContent);
            $ViewContent = preg_replace("/<body>/i", "<body>{$jses}", $ViewContent);
            register::Destroy('PenguJsPlace');
        } else {
            if (preg_match("/" . preg_quote($place) . "/i", $ViewContent)) {
                $ViewContent = preg_replace("/{$place}/i", $jses, $ViewContent);
                register::Destroy('PenguJsPlace');
            }
        }
    }

}

