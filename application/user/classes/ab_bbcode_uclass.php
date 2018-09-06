<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: ab_bbcode_uclass.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:58
##########################################################
 */


class ab_bbcodeUclass {

    private $pluginpath, $pluginurl, $bbcode_parser;

    function __construct() {
        $this->pluginpath = plugin_path() . '/pengu_comment';
        $this->pluginurl = plugin_url() . '/pengu_comment';
        
        require_once $this->pluginpath . '/jbbcode-1.2.0/Parser.php';
        require_once $this->pluginpath . '/BbeditorCodeDefinitionSet.php';

        $this->bbcode_parser = new jbbcode_parser();
        $this->bbcode_parser->addCodeDefinitionSet(new DefaultCodeDefinitionSet());
        $this->bbcode_parser->addCodeDefinitionSet(new BbeditorCodeDefinitionSet());
    }

    public function bbcode_decode($str) {
        $str = $this->decode($str);
        $str = $this->tosmiley($str);
        return $str;
    }

    private function decode($inputText) {
        $this->bbcode_parser->parse($inputText);
        return $this->bbcode_parser->getAsHTML();
    }

    private function tosmiley($inputText) {
        $url = $this->pluginurl . '/bbeditor/emoticons/';
        $tr = array(
            ':alien:' => "<img src=\"{$url}alien.png\">",
            ':angel:' => "<img src=\"{$url}angel.png\">",
            ':angry:' => "<img src=\"{$url}angry.png\">",
            ':blink:' => "<img src=\"{$url}blink.png\">",
            ':blush:' => "<img src=\"{$url}blush.png\">",
            ':cheerful:' => "<img src=\"{$url}cheerful.png\">",
            '8-)' => "<img src=\"{$url}cool.png\">",
            ':\'(' => "<img src=\"{$url}cwy.png\">",
            ':devil:' => "<img src=\"{$url}devil.png\">",
            ':dizzy:' => "<img src=\"{$url}dizzy.png\">",
            ':ermm:' => "<img src=\"{$url}ermm.png\">",
            ':getlost:' => "<img src=\"{$url}getlost.png\">",
            ':D' => "<img src=\"{$url}grin.png\">",
            ':happy:' => "<img src=\"{$url}happy.png\">",
            '<3' => "<img src=\"{$url}heart.png\">",
            ':kissing:' => "<img src=\"{$url}kissing.png\">",
            ':ninja:' => "<img src=\"{$url}ninja.png\">",
            ':pinch:' => "<img src=\"{$url}pinch.png\">",
            ':pouty:' => "<img src=\"{$url}pouty.png\">",
            ':(' => "<img src=\"{$url}sad.png\">",
            ':O' => "<img src=\"{$url}shocked.png\">",
            ':sick:' => "<img src=\"{$url}sick.png\">",
            ':sideways:' => "<img src=\"{$url}sideways.png\">",
            ':silly:' => "<img src=\"{$url}silly.png\">",
            ':sleeping:' => "<img src=\"{$url}sleeping.png\">",
            ':)' => "<img src=\"{$url}smile.png\">",
            ':P' => "<img src=\"{$url}tongue.png\">",
            ':unsure:' => "<img src=\"{$url}unsure.png\">",
            ':woot:' => "<img src=\"{$url}w00t.png\">",
            ':wassat:' => "<img src=\"{$url}wassat.png\">",
            ';)' => "<img src=\"{$url}wink.png\">",
        );
        return strtr($inputText, $tr);
    }

}