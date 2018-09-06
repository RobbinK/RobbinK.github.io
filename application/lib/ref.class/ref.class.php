<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: ref.class.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:57
##########################################################
 */


define('REF_OW_NAME', 'name');
define('REF_OW_TOOLBAR', 'toolbar');
define('REF_OW_LOCATION', 'location');
define('REF_OW_DIRECTORIES', 'directories');
define('REF_OW_MENUBAR', 'menubar');
define('REF_OW_SCROLLBARS', 'scrollbars');
define('REF_OW_RESIZABLE', 'resizable');
define('REF_OW_COPYHISTORY', 'copyhistory');
define('REF_OW_WIDTH', 'width');
define('REF_OW_HEIGHT', 'height');

class referre {

    private $url;

    function __construct($url, $fix = true) {
        if ($fix)
            $this->url = $this->fixurl($url);
        else
            $this->url = $url;
    }

    private function fixurl($url) {
        if (!preg_match('/https?:\/\//i', $url))
            if (!preg_match('/^\//', $url))
                $url.='https://' . $url;
        return $url;
    }

    /**
     * redirect as server
     */
    public function redirect() {
        if (headers_sent()) {
            die('<meta http-equiv="Location" content="' . $this->url . '">');
        } else {
            exit(header("Location: {$this->url}"));
        }
    }

    public function locate() {
        $return = new scriptTag;
        $return->out = "window.location.href='{$this->url}';return false;";
        return $return;
    }

    public function openWindow(array $option = null) {
        if (!js::loaded(lib_path() . '/ref.class/openwin.js'))
            js::load(lib_path() . '/ref.class/openwin.js', array(JS_MINIFY => true));

        //--- Set Name
        if (@array_key_exists(REF_OW_NAME, $option))
            $name = $option[REF_OW_NAME];
        else
            $name = md5(url::link($this->url)->url_nonqry());
        //--- set other option
        if (@array_key_exists(REF_OW_NAME, $option))
            unset($option[REF_OW_NAME]);
        $arrayopt = array(
            'toolbar' => 'no',
            'location' => 'no',
            'directories' => 'no',
            'menubar' => 'no',
            'scrollbars' => 'no',
            'resizable' => 'no',
            'copyhistory' => 'no',
            'width' => '500',
            'height' => '500',
        );
        if (is_array($option))
            $arrayopt = array_replace($arrayopt, $option);
        foreach ($arrayopt as $k => $v) {
            $v = validate::_is_boolean_Type($v) ? (convert::to_bool($v) ? 'yes' : 'no') : $v;
            $options[] = $k . '=' . $v;
        }
        $options = join(',', $options);
        //-------------------------------
        $return = new scriptTag();
        $return->out = "openwin('{$this->url}','{$name}','{$options}');return false;";
        return $return;
    }

}

class scriptTag {

    public function showScriptTag() {
        $rand = rand();
        $this->out = '<script type="text/javascript">function reftodo' . $rand . '(){' . $this->out . '}reftodo' . $rand . '();</script>';
        return $this;
    }

    public function getResult() {
        return $this->out;
    }

    function __toString() {
        return (string) $this->getResult();
    }

}

function ref($url) {
    return new referre($url);
}
