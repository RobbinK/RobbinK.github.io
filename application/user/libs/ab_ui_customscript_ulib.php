<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: ab_ui_customscript_ulib.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:58
##########################################################
 */


event::register_onLoadView('_ui_customscript',20);

function _ui_customscript(&$ViewContent) {
    if (validate::_is_ajax_request())
        return;

    function _customscript_place(&$content, $code, $position) {
        if (!preg_match('/^([\s\S]*)?<script[^\>]*>/i', $code))
            $code = '<script type="text/javascript">' . $code;
        if (!preg_match('/<\/script>([\s\S]*)?/i', $code))
            $code .= '</script>';

        switch ($position) {
            case 'header':
                if (preg_match("/<\/head>/i", $content)) {
                    $content = preg_replace("/<\/head>/i", "{$code}\n</head>", $content);
                }
                break;
            case 'body':
                if (preg_match("/<body>/i", $content)) {
                    $content = preg_replace("/<body>/i", "<body>\n{$code}", $content);
                }
                break;
            case 'footer':
                if (preg_match("/<\/body>/i", $content)) {
                    $content = preg_replace("/<\/body>/i", "{$code}\n</body>", $content);
                }
                break;
        }
    }

    /* analytic code */
    $analytic_script = setting::get_data('scripts_google_analytics_code', 'val');
    if (!empty($analytic_script))
        _customscript_place($ViewContent, $analytic_script, 'header');

    /* scripts_header code */
    $header_script = setting::get_data('scripts_header', 'val');
    if (!empty($header_script))
        _customscript_place($ViewContent, $header_script, 'header');

    /* scripts_body code */
    $body_script = setting::get_data('scripts_body', 'val');
    if (!empty($body_script))
        _customscript_place($ViewContent, $body_script, 'body');

    /* scripts_footer code */
    $footer_script = setting::get_data('scripts_footer', 'val');
    if (!empty($footer_script))
        _customscript_place($ViewContent, $footer_script, 'footer');
}

