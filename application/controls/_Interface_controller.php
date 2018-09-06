<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: _Interface_controller.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:57
##########################################################
 */


class InterfaceController extends BaseController
{

    function __construct()
    {
        parent::__construct();
        agent::init();

        /* changing template */
        if (!empty($_GET['abtheme']) && file_exists(themes_path() . '/' . $_GET['abtheme']))
            $this->MapViewTemeplateName($_GET['abtheme']);
        elseif (!file_exists(themes_path() . '/' . template_name())) {
            pengu_enderror('Template error', 'Current template you chose does not exist!');
        }

        $this->MapViewThemesFolder('/themes');
        $this->MapViewFolder(null);
        /* detect device */

        $this->language_functions_init();

        function is_mobile()
        {
            $detect_cookie_device = 'ab_device';
            $ismobile = false;
            if (!isset($_COOKIE[$detect_cookie_device])) {
                $ismobile = agent::mobile_detect()->isMobile();
                setcookie($detect_cookie_device, $ismobile, time() + 255 * 24 * 3600, '/');
            } else
                $ismobile = $_COOKIE[$detect_cookie_device];
            if (isset($_GET['abandroid']) || isset($_GET['abios']))
                $ismobile = true;
            return $ismobile;
        }

        function mobile_os()
        {
            $detect_cookie_os = 'ab_device_os';
            if (!isset($_COOKIE[$detect_cookie_os])) {
                $device = 'undefined';
                if (agent::mobile_detect()->isAndroidOS())
                    $device = 'android';
                if (agent::mobile_detect()->isiOS())
                    $device = 'ios';
                setcookie($detect_cookie_os, $device, time() + (255 * 24 * 3600), '/');
            } else
                $device = $_COOKIE[$detect_cookie_os];
            if (isset($_GET['abandroid']))
                $device = 'android';
            if (isset($_GET['abios']))
                $device = 'ios';
            return $device;
        }

        function is_android()
        {
            return mobile_os() == 'android';
        }

        function is_ios()
        {
            return mobile_os() == 'ios';
        }

        function is_andriod_ios()
        {
            if (in_array(mobile_os(), array('android', 'ios')))
                return true;
            return false;
        }

        function mobileApp()
        {
            global $mobileapp;
            return $mobileapp;
        }

        /* end device detector */
        $Emobile = _get_theme_setting('mobile_theme');
        if (is_andriod_ios() && in_array(route_name(), array('homepage', 'populargames', 'allgames', 'allgames_cat')) && file_exists(template_path() . '/mobile/') && ($Emobile === null || $Emobile == 1)) {
            global $mobileapp;
            $mobileapp = true;
            $this->MapViewFolder('mobile');
        }

        function ab_template_id()
        {
            static $id;
            if (isset($id))
                return $id;
            $p = template_path() . '/theme_setting.php';
            global $themeConfiguration;
            if (!isset($themeConfiguration)) {
                $themeConfiguration = include $p;
            }
            if (defined('_template_id')) {
                $id = _template_id;
                return $id;
            }
            $id = template_name();
            return $id;
        }

        pengu_user_load_lib("ab_ui_funcs");
        pengu_user_load_lib("ab_game_funcs");
        pengu_user_load_lib("ab_page_funcs");
        pengu_user_load_lib("ab_category_funcs");
        pengu_user_load_lib("ab_member_funcs");
        pengu_user_load_lib("ab_ui_visit");
        pengu_user_load_lib("ab_ui_customscript");

        if (!validate::_is_ajax_request()) {
            if (generatingStats())
                _visitor_init();
            //css and js cdn just don't work on ajax
            if ($cdn = setting::get_data('css_cdn', 'val'))
                css::$cdn = $cdn;
            if ($cdn_zone = setting::get_data('css_cdn_zone', 'val'))
                css::$cdn_zone = $cdn_zone;

            if ($cdn = setting::get_data('js_cdn', 'val'))
                js::$cdn = $cdn;
            if ($cdn_zone = setting::get_data('js_cdn_zone', 'val'))
                js::$cdn_zone = $cdn_zone;
        }
        if ($cdn = setting::get_data('images_cdn', 'val'))
            pengu_image::$cdn = $cdn;
        if ($cdn_zone = setting::get_data('images_cdn_zone', 'val'))
            pengu_image::$cdn_zone = $cdn_zone;
    }

    function language_functions_init()
    {

        function lang()
        {
            $l = setting::get_data('site_language', 'val');
            return !empty($l) && PenguI18n::isLangExist($l) ? $l : 'en';
        }

        function lang_isrtl()
        {
            return in_array(lang(), array('fa', 'ar', 'ur')) ? true : false;
        }

        PenguI18n::install(cache_path() . '/lang/interface/' . template_name());
        /* ================ */
    }

    function islogin()
    {
        $model = new Member;
        $model->setLogoutPage(ab_router('userlogin'));
        $model->CheckLogin();
    }

    function page404()
    {
        direction::$currentAction = 'page404';
        if (file_exists(siteinfo('template_path') . '/404.php')) {
            $this->MapViewFileName('404.php');
        } else
            @include(static_path() . '/pages/default404page.php');
    }

    function page_maintenance()
    {
        direction::$currentAction = 'page_maintenance';
        if (file_exists(viewfolder_path() . '/maintenance.php'))
            $this->MapViewFileName('maintenance.php');
        else
            die("<center><strong>Closed for maintenance</strong></center>");
    }

}
