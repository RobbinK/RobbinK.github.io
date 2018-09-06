<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: ab_admin_cssjs_include_ulib.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:58
##########################################################
 */

define('l_basic', 1);
define('l_jquery', 10);
define('l_jquery_ui_css', 100);
define('l_jquery_ui_js', 101);
define('l_bootstrap', 102);
define('l_qtip2', 103);
define('l_colorbox', 104);
define('l_sticky', 105);
define('l_imageload', 106);
define('l_cookie', 109);
define('l_actual', 110);
define('l_debounced', 11);
define('l_sparkline', 112);
define('l_totop', 113);
define('l_selectNav', 114);
define('l_touch_punch', 115);
define('l_wookmark', 116);
define('l_mediaTable', 117);
define('l_flot', 118);
define('l_fullcalendar', 119);
define('l_list_js', 120);
define('l_jBreadcrumbs', 121);
define('l_list_paging', 122);
define('l_ie', 200);
define('l_pengu_common', 202);
define('l_splashy', 203);
define('l_flags', 204);
define('l_blue', 205);
define('l_style', 206);
define('l_datepicker', 207);
define('l_timepicker', 208);
define('l_validate', 209);
define('l_datatable', 210);
define('l_smoke', 211);
define('l_showloading', 212);
define('l_unserializeForm', 213);
define('l_autosize', 214);
define('l_complexify', 215);
define('l_spinner', 216);
define('l_uniform', 217);
define('l_hint', 218);
define('l_requier', 219);
define('l_yepnope', 220);
define('l_multiselect', 221);
define('l_bootstrap_modal', 222);
define('l_tagsinput', 223);

function abs_admin_inc_css($csscontent, $opt = array())
{
    global $abs_admin_inc_csscontents;
    $opt = array_merge(array(CSS_FORCELOAD => true), $opt);
    ob_start();
    if (is_array($csscontent) && !empty($csscontent))
        css::load(eval('return array(' . join(',', $csscontent) . ');'), $opt);
    else
        if (strpos($csscontent, 'css::load') !== false)
            eval($csscontent);
        else
            css::load($csscontent, $opt);

    $abs_admin_inc_csscontents .= ob_get_clean();
}

function abs_admin_inc_js($jscontent, $opt = array())
{
    global $abs_admin_inc_jscontents;
    $opt = array_merge(array(JS_FORCELOAD => true, JS_EXEC => false, JS_MINIFY => false), $opt);
    ob_start();
    if (is_array($jscontent) && !empty($jscontent)) {
        js::load(eval('return array(' . join(',', $jscontent) . ');'), $opt);
    } else
        if (strpos($jscontent, 'js::load') !== false)
            eval($jscontent);
        else
            js::load($jscontent, $opt);
    $abs_admin_inc_jscontents .= ob_get_clean();
}

function abs_admin_inc($name)
{
    global $abs_admin_cssjs;

    switch ($name) {
        case l_basic:
            abs_admin_inc(l_jquery);
            abs_admin_inc(l_jquery_ui_js);
            abs_admin_inc(l_pengu_common);
            abs_admin_inc(l_ie);
            abs_admin_inc(l_bootstrap);
            abs_admin_inc(l_flags);

            $csses[] = "template_url() . '/lib/jquery-ui/css/Aristo/Aristo.css'";
            //jBreadcrumbs
            $csses[] = "template_url() . '/lib/jBreadcrumbs/css/BreadCrumb" . (lang_isrtl() ? '_rtl' : null) . ".css'";
            $jses[] = "template_path() . '/lib/jBreadcrumbs/js/jquery.jBreadCrumb.1.1.min.js'";

            //qtip2 
            $csses[] = "template_url() . '/lib/qtip2/jquery.qtip.min.css'";
            $jses[] = "template_path() . '/lib/qtip2/jquery.qtip.min.js'";

            //slimscroll
            $jses[] = "template_path() . '/lib/slimscroll/jquery.slimscroll.min.js'";

            $csses[] = "template_url() . '/css/style" . (lang_isrtl() ? '_rtl' : null) . ".css'";
            $csses[] = "template_url() . '/css/" . (isset($_COOKIE['ab_admin_theme']) ? $_COOKIE['ab_admin_theme'] : 'dark') . ".css'";
            $csses[] = "template_url() . '/img/splashy/splashy.css'";
            $csses[] = "static_url() . '/css/social-icons/social.css'";
            $csses[] = "template_url() . '/lib/sticky/styles/sticky.css'";
            $csses[] = "static_url() . '/js/jquery.showloading/showLoading.css'";
            $jses[] = "template_path() . '/lib/sticky/sticky.min.js'";
            $jses[] = "template_path() . '/lib/sticky/stickyhide.min.js'";
            $jses[] = "static_path() . '/js/jquery.cookie.min.js'";
            $jses[] = "static_path() . '/js/base64.lib.js'";
            $jses[] = "static_path() . '/js/jquery.imagesloaded.min.js'";
//            $jses[] = "static_path() . '/js/jquery.mousewheel.min.js'";
            $jses[] = "static_path() . '/js/forms/jquery.autosize.min.js'";
            $jses[] = "static_path() . '/js/jquery.actual.min.js'";
            $jses[] = "static_path() . '/js/jquery-debounced-and-throttled/jquery.debouncedresize.min.js'";
            $jses[] = "static_path() . '/js/jquery.showloading/jquery.showLoading.min.js'";
            $jses[] = "static_path() . '/js/jquery-deparam/jquery.deparam.min.js'";
            $jses[] = "template_path() . '/lib/UItoTop/jquery.ui.totop.min.js'";
            $jses[] = "template_path() . '/js/selectNav.js'";
            abs_admin_inc_js($jses);
            abs_admin_inc_css($csses);
            break;
        case l_jquery:
            abs_admin_inc_js("js::loadJquery(true);");
            abs_admin_inc_js("js::loadjquery_migrate(true);");
            break;
        case l_jquery_ui_css:
            abs_admin_inc_css("css::loadJqueryuicss(true);");
            break;
        case l_jquery_ui_js:
            abs_admin_inc_js("js::load(template_path() . '/lib/jquery-ui/jquery-ui-1.10.0.custom.min.js', array(JS_FORCELOAD => true));");
            break;
        case l_bootstrap:
            $csses[] = "template_url() . '/bootstrap/css/bootstrap." . (lang_isrtl() ? 'rtl' : 'min') . ".css'";
            $csses[] = "template_url() . '/bootstrap/css/bootstrap-responsive." . (lang_isrtl() ? 'rtl' : 'min') . ".css'";
            $jses[] = "template_path() . '/bootstrap/js/bootstrap.min.js'";
            abs_admin_inc_css($csses);
            abs_admin_inc_js($jses);
            break;
        case l_jBreadcrumbs:
            abs_admin_inc_css("css::load(template_url() . '/lib/jBreadcrumbs/css/BreadCrumb" . (lang_isrtl() ? '_rtl' : null) . ".css', array(CSS_FORCELOAD => true));");
            abs_admin_inc_js("js::load(template_path() . '/lib/jBreadcrumbs/js/jquery.jBreadCrumb.1.1.min.js', array(JS_FORCELOAD => true));");
            break;
        case l_qtip2:
            abs_admin_inc_css("css::load(template_url() . '/lib/qtip2/jquery.qtip.min.css', array(CSS_FORCELOAD => true));");
            abs_admin_inc_js("js::load(template_path() . '/lib/qtip2/jquery.qtip.min.js', array(JS_FORCELOAD => true));");
            break;
        case l_colorbox:
            abs_admin_inc_css("css::load(template_url() . '/lib/colorbox/colorbox.css', array(CSS_FORCELOAD => true));");
            abs_admin_inc_js("js::load(template_path() . '/lib/colorbox/jquery.colorbox.min.js', array(JS_FORCELOAD => true));");
            break;
        case l_sticky:
            abs_admin_inc_css("css::load(template_url() . '/lib/sticky/styles/sticky.css', array(CSS_FORCELOAD => true));");
            $jses[] = "template_path() . '/lib/sticky/sticky.min.js'";
            $jses[] = "template_path() . '/lib/sticky/stickyhide.min.js'";
            abs_admin_inc_js($jses);
            break;
        case l_smoke:
            abs_admin_inc_css("css::load(template_url() . '/lib/smoke/themes/abs.css', array(CSS_FORCELOAD => true));");
            abs_admin_inc_js("js::load(template_path() . '/lib/smoke/smoke.abs.min.js', array(JS_FORCELOAD => true));");
            break;
        case l_imageload:
            abs_admin_inc_js("js::load(static_path() . '/js/jquery.imagesloaded.min.js', array(JS_FORCELOAD => true));");
            break;
        case l_cookie:
            abs_admin_inc_js("js::load(static_path() . '/js/jquery.cookie.min.js', array(JS_FORCELOAD => true));");
            break;
        case l_actual:
            abs_admin_inc_js("js::load(static_path() . '/js/jquery.actual.min.js', array(JS_FORCELOAD => true));");
            break;
        case l_debounced:
            abs_admin_inc_js("js::load(static_path() . '/js/jquery-debounced-and-throttled/jquery.debouncedresize.min.js', array(JS_FORCELOAD => true));");
            break;
        case l_sparkline:
            abs_admin_inc_js("js::load(static_path() . '/js/jquery.sparkline.min.js', array(JS_FORCELOAD => true));");
            break;
        case l_totop:
            abs_admin_inc_js("js::load(template_path() . '/lib/UItoTop/jquery.ui.totop.min.js', array(JS_FORCELOAD => true));");
            break;
        case l_selectNav:
            abs_admin_inc_js("js::load(template_path() . '/js/selectNav.js', array(JS_FORCELOAD => true));");
            break;
        case l_touch_punch:
            abs_admin_inc_js("js::load(static_path() . '/js/forms/jquery.ui.touch-punch.min.js', array(JS_FORCELOAD => true));");
            break;
        case l_autosize:
            abs_admin_inc_js("js::load(static_path() . '/js/forms/jquery.autosize.min.js', array(JS_FORCELOAD => true));");
            break;
        case l_wookmark:
            abs_admin_inc_js("js::load(template_path() . '/js/jquery.wookmark.js', array(JS_FORCELOAD => true));");
            break;
        case l_mediaTable:
            abs_admin_inc_js("js::load(template_path() . '/js/jquery.mediaTable.min.js', array(JS_FORCELOAD => true));");
            break;
        case l_flot:
            $jses[] = "template_path() . '/lib/flot/jquery.flot.min.js'";
            $jses[] = "template_path() . '/lib/flot/jquery.flot.resize.min.js'";
            $jses[] = "template_path() . '/lib/flot/jquery.flot.pie.min.js'";
            abs_admin_inc_js($jses);
            global $abs_admin_inc_csscontents;
            ob_start();
            ?>
            <!--[if lte IE 8]>
            <script src="<?= template_url() ?>/lib/flot/excanvas.min.js"></script>
            <![endif]-->
            <?php
            $abs_admin_inc_csscontents .= ob_get_clean();
            break;
        case l_fullcalendar:
            abs_admin_inc_js("js::load(template_path() . '/lib/fullcalendar/fullcalendar.min.js', array(JS_FORCELOAD => true));");
            abs_admin_inc_css("css::load(template_url() . '/lib/fullcalendar/fullcalendar_abs.css', array(CSS_FORCELOAD => true));");
            break;
        case l_list_js:
            abs_admin_inc_js("js::load(template_path() . '/lib/list_js/list.min.js', array(JS_FORCELOAD => true));");
            break;
        case l_list_paging:
            abs_admin_inc_js("js::load(template_path() . '/lib/list_js/plugins/paging/list.paging.js', array(JS_FORCELOAD => true));");
            break;
        /* ==IE== */
        case l_ie :
            global $abs_admin_inc_csscontents;
            $abs_admin_inc_csscontents = '
            <!--[if lte IE 8]> 
                <link rel="stylesheet" href="' . template_url() . '/css/ie.css" />
                <script src="' . template_url() . '/js/ie/html5.js"></script>
                <script src="' . template_url() . '/js/ie/respond.min.js"></script> 
            <![endif]-->';
            break;
        case l_splashy:
            abs_admin_inc_css("css::load(template_url() . '/img/splashy/splashy.css', array(CSS_FORCELOAD => true));");
            break;
        case l_flags:
            abs_admin_inc_css("css::load(template_url() . '/img/flags/flags.css', array(CSS_FORCELOAD => true));");
            break;
        case l_blue:
            abs_admin_inc_css("css::load(template_url() . '/css/blue.css', array(CSS_FORCELOAD => true));");
            break;
        case l_style:
            abs_admin_inc_css("css::load(template_url() . '/css/style.css', array(CSS_FORCELOAD => true));");
            break;
        case l_pengu_common:
            $jses[] = "static_path() . '/js/pengu/safe.js'";
            $jses[] = "static_path() . '/js/pengu/str.js'";
            $jses[] = "static_path() . '/js/pengu/common.js'";
            $jses[] = "static_path() . '/js/pengu/url.js'";
            abs_admin_inc_js($jses);
            break;
        case l_datepicker:
            if (!css::loaded(template_url() . '/lib/datepicker/datepicker.css'))
                abs_admin_inc_css("css::load(template_url() . '/lib/datepicker/datepicker.css', array(CSS_FORCELOAD => true));");
            abs_admin_inc_js("js::load(template_path() . '/lib/datepicker/bootstrap-datepicker.min.js', array(JS_FORCELOAD => true));");
            break;
        case l_timepicker:
            if (!css::loaded(template_url() . '/lib/datepicker/datepicker.css'))
                abs_admin_inc_css("css::load(template_url() . '/lib/datepicker/datepicker.css', array(CSS_FORCELOAD => true));");
            abs_admin_inc_js("js::load(template_path() . '/lib/datepicker/bootstrap-timepicker.min.js', array(JS_FORCELOAD => true));");
            break;
        case l_validate:
            abs_admin_inc_js("js::load(template_path() . '/lib/validation/jquery.validate.min.js', array(JS_FORCELOAD => true));");
            break;
        case l_unserializeForm:
            abs_admin_inc_js("js::load(static_path() . '/js/jQuery.unserializeForm/jQuery.unserializeForm.min.js', array(JS_FORCELOAD => true));");
            break;
        case l_showloading:
            abs_admin_inc_js("js::load(static_path() . '/js/jquery.showloading/jquery.showLoading.min.js', array(JS_FORCELOAD => true));");
            abs_admin_inc_css("css::load(static_url() . '/js/jquery.showloading/showLoading.css', array(css_FORCELOAD => true));");
            break;
        case l_datatable:
            $jses[] = "template_path() . '/lib/datatables/jquery.dataTables.min.js'";
            $jses[] = "template_path() . '/lib/datatables/jquery.dataTables.ext.js'";
            $jses[] = "template_path() . '/lib/datatables/extras/Scroller/media/js/dataTables.scroller.min.js'";
            $jses[] = "template_path() . '/lib/datatables/extras/TableTools/media/js/TableTools.min.js'";
            $jses[] = "template_path() . '/lib/datatables/jquery.dataTables.sorting.js'";
//            $jses[] = "template_path() . '/lib/datatables/extras/TableTools/media/js/ZeroClipboard.js'";
            $jses[] = "template_path() . '/lib/datatables/jquery.dataTables.bootstrap.min.js'";
            abs_admin_inc_css("css::load(template_url() . '/lib/datatables/extras/TableTools/media/css/TableTools.css', array(CSS_FORCELOAD => true));");
            abs_admin_inc_js($jses, array(JS_MINIFY => false));
            break;
        case l_complexify:
            abs_admin_inc_js("js::load(template_path() . '/lib/complexify/jquery.complexify.min.js', array(JS_FORCELOAD => true));");
            break;
        case l_spinner:
            abs_admin_inc_js("js::load(static_path() . '/js/forms/jquery.spinners.min.js', array(JS_FORCELOAD => true));");
            break;
        case l_uniform:
            abs_admin_inc_js("js::load(template_path() . '/lib/uniform/jquery.uniform.min.js', array(JS_FORCELOAD => true));");
            abs_admin_inc_css("css::load(template_url() . '/lib/uniform/Aristo/uniform.aristo.css', array(CSS_FORCELOAD => true));");
            break;
        case l_hint:
            abs_admin_inc_css("css::load(template_url() . '/lib/hint_css/hint.min.css', array(CSS_FORCELOAD => true));");
            break;
        case l_requier:
            $jses[] = "static_path() . '/js/requirejs/require.js'";
            abs_admin_inc_js($jses);
            break;
        case l_yepnope:
            abs_admin_inc_js("js::load(static_path() . '/js/yepnope.1.5.4-min.js', array(JS_FORCELOAD => true));");
            break;
        case l_multiselect:
            abs_admin_inc_css("css::load(static_url() . '/js/multiple-select/multiple-select.css', array(CSS_FORCELOAD => true));");
            abs_admin_inc_js("js::load(static_path() . '/js/multiple-select/jquery.multiple.select.js', array(JS_FORCELOAD => true));");
            break;
        case l_bootstrap_modal:
            $jses[] = "static_path() . '/bootstrap/bootstrap-modal/js/bootstrap-modal" . (lang_isrtl() ? '_rtl' : null) . ".js'";
            $jses[] = "static_path() . '/bootstrap/bootstrap-modal/js/bootstrap-modalmanager.min.js'";
            abs_admin_inc_js($jses, array(JS_MINIFY => false));
            abs_admin_inc_css("css::load(static_url() . '/bootstrap/bootstrap-modal/css/bootstrap-modal.css', array(CSS_FORCELOAD => true));");
            break;
        case l_tagsinput:
            abs_admin_inc_css("css::load(template_url() . '/lib/bootstrap_tagsinput/bootstrap-tagsinput.css', array(CSS_FORCELOAD => true));");
            abs_admin_inc_js("js::load(template_path() . '/lib/bootstrap_tagsinput/bootstrap-tagsinput.min.js', array(JS_FORCELOAD => true));");
            break;
    }
}

function abs_admin_place_css()
{
    global $abs_admin_inc_csscontents;
    return $abs_admin_inc_csscontents;
}

function abs_admin_place_js()
{
    global $abs_admin_inc_jscontents;
    return $abs_admin_inc_jscontents;
}