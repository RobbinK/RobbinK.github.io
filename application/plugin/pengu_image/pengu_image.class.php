<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: pengu_image.class.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:58
##########################################################
 */


define('IMAGE_FIT_FILL', 'fill');
define('IMAGE_FIT_INSIDE', 'inside');
define('IMAGE_FIT_OUTSIDE', 'outside');

class pengu_image {

    private static $outExtension = 'jpg';
    public static $cdn;
    public static $cdn_zone;
    private $imageInf_data;
    private $mostReCreate = false;
    private $ShowHtmlTag = false;
    private $InlineTag_Properties;

    private static function init() {
        if (self::testGDInstalled() && (strnatcmp(phpversion(), '5.2.0') >= 0)) {
            require_once ('lib/wideimage/WideImage.php');
        } else {
            require_once ('lib/simple.resize.php');
        }
    }

    private static function testGDInstalled() {
        if (extension_loaded('gd') && function_exists('gd_info'))
            return true;
        else
            return false;
    }

    public static function resize($src, $width = null, $height = null, $fit = 'fill') {
        self::init();
        $ext = path::get_extension($src);
        $base = path::get_basename($src);

        if ($ext == 'bmp')
            $ext = 'jpg';

        $tmp_filename = '';
        if (!empty($base))
            $tmp_filename = md5(serialize(array(
                        $src, $width, $height, $fit
                    ))) . '.' . $ext;

        $data = array(
            'src' => $src,
            'ext' => $ext,
            'tmpsrc' => cache_path() . '/images/' . $tmp_filename,
            'tmpurl' => cache_url() . '/images/' . $tmp_filename,
            'width' => $width,
            'height' => $height,
            'fit' => $fit
        );

        $instance = new pengu_image;
        $instance->imageInf_data = $data;
        return $instance;
    }

    public function ReCreate() {
        $this->mostReCreate = true;
        return $this;
    }

    public function ShowOnWindow() {
        $data = $this->imageInf_data;
        if (!file_exists($data['tmpsrc']) || $this->mostReCreate)
            $this->createImage();
        list($width, $height) = @getimagesize($data['tmpsrc']);
        $url = plugin_url() . '/pengu_image/full_image.php?url=' . urlencode($data['tmpurl']);
        return ref($url)->openWindow(array(REF_OW_WIDTH => $width . 'px', REF_OW_HEIGHT => ($height + 37) . 'px', REF_OW_SCROLLBARS => false));
    }

    public function ShowIMGTag($InlineTag_Properties = null) {
        $this->ShowHtmlTag = true;
        $this->InlineTag_Properties = $InlineTag_Properties;
        return $this;
    }

    private function createImage() {
        $data = $this->imageInf_data; 

        if (class_exists('WideImage') && (strnatcmp(phpversion(), '5.2.0') >= 0)) {
            try {
                @WideImage::load($data['src'])->resize($data['width'], $data['height'], $data['fit'])->saveToFile($data['tmpsrc']);
            } catch (Exception $e) {
                
            }
        } elseif (function_exists('img_resize')) {

            //img_resize lib is loaded
            switch ($data['fit']) {
                case 'fill':
                    $params = array(
                        'width' => $data['width'],
                        'height' => $data['height'],
                        'aspect_ratio' => false,
                        'crop' => false
                    );
                    break;
                case 'inside':
                    $params = array(
                        'constraint' => array('width' => $data['width'], 'height' => $data['height'])
                    );
                    break;
                case 'outside':
                    $params = array(
                        'width' => $data['width'],
                        'height' => $data['height'],
                        'aspect_ratio' => true,
                        'crop' => true
                    );
                    break;
                default :
                    $params = array(
                        'constraint' => array('width' => $data['width'], 'height' => $data['height'])
                    );
                    break;
            }

            img_resize($data['src'], $data['tmpsrc'], $params);
        }
    }

    function getImagePath() {
        if (!file_exists($this->imageInf_data['tmpsrc']) || $this->mostReCreate)
            $this->createImage();
        return !empty($this->imageInf_data['tmpsrc']) ? $this->imageInf_data['tmpsrc'] : null;
    }

    function getResult() {
        $data = $this->imageInf_data;

        if (!file_exists($data['tmpsrc']) || $this->mostReCreate)
            $this->createImage();

        $root = urlencode(base64_encode(ROOT_PATH));

        if ($data['ext'] == 'jpg') {
            static $u;
            if (!isset($u)) {
                $u = plugin_url() . "/pengu_image/pengu_image.driver.php";
                self::CDN($u);
            }
            $return = $u . "?root=" . $root . "&img=" . base64::encode($data['tmpsrc']);
        } else {
            $return = $data['tmpurl'];
            self::CDN($return);
        }


        if ($this->ShowHtmlTag) {
            $return = "\n<img src='{$return}' {$this->InlineTag_Properties} />";
            return $return;
        }
        else
            return $return;
    }

    private static function CDN(&$src) {
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

    function __toString() {
        return (string) $this->getResult();
    }

}