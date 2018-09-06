<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: lang.init.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:58
##########################################################
 */


class PenguI18n extends i18n {

    public static $installed = false;

    public function getUserLangs() {

        $userLangs = array();
        $userLangs[] = function_exists('lang') ? lang() : 'en';
        return $userLangs;
    }

    public static function isLangExist($lang_code) {
        return file_exists(template_path() . "/lang/lang_{$lang_code}.ini");
    }

    public static function install($cache_path = null) {
        if (file_exists(template_path() . '/lang')) {
            if (empty($cache_path))
                $cache_path = cache_path() . '/lang';
            if (!file_exists($cache_path))
                rmkdir($cache_path);
            $i18n = new PenguI18n();
            $i18n->setCachePath($cache_path);
            $i18n->setFilePath(template_path() . "/lang/lang_{LANGUAGE}.ini"); // language file path (the ini files)
            $i18n->setFallbackLang('en');
            $i18n->setPrefix('L');
            $i18n->setSectionSeperator('_');
            try {
                $i18n->init();
            } catch (Exception $e) {
                //echo ($e->getMessage());
                //sexit;
                return false;
            }
            self::$installed = true;
        }
    }

}

function pengu_lang_load() {
    if (!PenguI18n::$installed)
        PenguI18n::install();
}

event::register_onPreLoadView('pengu_lang_load');
