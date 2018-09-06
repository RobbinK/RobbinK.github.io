<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: lc.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:59
##########################################################
 */


function __pengu_load_m_c($c)
{
    global $PenguInflector;
    static $lschk;
    error_reporting(0);
    if (!isset($lschk)) {
        __2nd_ls_process();
    }
    $lschk = true;
    //Cn
    $controllerName = ucwords(str_replace('Controller', null, $c));
    $dest2 = ROOT_PATH . "/application/controls/";
    if (file_exists($dest2 . $controllerName . '_controller.php')) {
        require_once($dest2 . $controllerName . '_controller.php');
    } elseif ($cnFile = glob($dest2 . '_*' . $controllerName . '_controller.php')) {
        if (isset($cnFile[0]) && file_exists($cnFile[0]))
            require_once($cnFile[0]);
    }
    //Md
    $dest3 = ROOT_PATH . "/application/model/" . ucwords($PenguInflector->get_singular($controllerName)) . '.php';
    if (file_exists($dest3))
        require_once($dest3);
    error_reporting(PENGU_ERROR_STATUS);
}

eval(base64_decode("ZnVuY3Rpb24gX2xkbWMxKCRhKXtfX3Blbmd1X2xvYWRfbV9jKCRhKTt9"));

function __2nd_ls_process()
{
    static $i;
    if (isset($i))
        return true;
    $i = 1;
    eval(base64_decode('aW5jbHVkZShST09UX1BBVEguJy9saWNlbnNlLnBocCcpOw=='));
    $l = eval(base64_decode('cmV0dXJuIEAkbGljZW5zZTs='));
    if (!eval(base64_decode('cmV0dXJuIGlzc2V0KCRsaWNlbnNlKTs='))) {
        eval(base64_decode('cGVuZ3VfZW5kZXJyb3IoJ2xpY2Vuc2UgZmlsZSBlcnJvciEnLCdUaGUgc2NyaXB0IGxpY2Vuc2Ugd2FzIG5vdCBmb3VuZCEgLSBlcnJvcigyMDIpJyk7')); //202
    }
    if ((isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'))
        return false;

    eval(base64_decode('ZnVuY3Rpb24gX18ybmRfY2hrX2xzKCRscykKICAgIHsKICAgICAgICAvKiBTY3JpcHQgTGljZW5zaW5nICovCiAgICAgICAgJGRvbWFpbiA9IGdldF9kb21haW4oSE9TVF9VUkwsIGZhbHNlKTsKICAgICAgICAkc2RvbWFpbiA9IGdldF9kb21haW4oSE9TVF9VUkwsIHRydWUpOwoKICAgICAgICAkaXBwID0gIi9eKD86MlswLTRdXGR8MjVbMC01XXxbMDFdP1xkXGQ/KVwuKD86MlswLTRdXGR8MjVbMC01XXxbMDFdP1xkXGQ/KVwuKD86MlswLTRdXGR8MjVbMC01XXxbMDFdP1xkXGQ/KVwuKD86MlswLTRdXGR8MjVbMC01XXxbMDFdP1xkXGQ/KSQvIjsKICAgICAgICBpZiAocHJlZ19tYXRjaCgkaXBwLCAkZG9tYWluKSkgewogICAgICAgICAgICBnbG9iYWwgJGxpY2Vuc2VfdHlwZTsKICAgICAgICAgICAgJGxpY2Vuc2VfdHlwZSA9ICdwcmVtaXVtJzsKICAgICAgICAgICAgcmV0dXJuIHRydWU7CiAgICAgICAgfQoKICAgICAgICBpZiAoc3RycG9zKCRscywgbWQ1KCRkb21haW4gLiAnX2ZyZWVMaWNlbnNlJyAuICdoZG1zYW4nKSkgIT09IGZhbHNlIHx8IHN0cnBvcygkbHMsIG1kNSgkc2RvbWFpbiAuICdfZnJlZUxpY2Vuc2UnIC4gJ2hkbXNhbicpKSAhPT0gZmFsc2UpIHsKICAgICAgICAgICAgZ2xvYmFsICRsaWNlbnNlX3R5cGU7CiAgICAgICAgICAgICRsaWNlbnNlX3R5cGUgPSAnZnJlZSc7CiAgICAgICAgICAgIGV2ZW50OjpyZWdpc3Rlcl9vbkNhbGxDb250cm9sbGVyKCdfX25vdF9wcmVtaXVtJyk7CiAgICAgICAgICAgIHJldHVybiB0cnVlOwogICAgICAgIH0KICAgICAgICBpZiAoc3RycG9zKCRscywgbWQ1KCRkb21haW4gLiAnX3ByZW1pdW1MaWNlbnNlJyAuICdoZG1zYW4nKSkgIT09IGZhbHNlIHx8IHN0cnBvcygkbHMsIG1kNSgkc2RvbWFpbiAuICdfcHJlbWl1bUxpY2Vuc2UnIC4gJ2hkbXNhbicpKSAhPT0gZmFsc2UpIHsKICAgICAgICAgICAgZ2xvYmFsICRsaWNlbnNlX3R5cGU7CiAgICAgICAgICAgICRsaWNlbnNlX3R5cGUgPSAncHJlbWl1bSc7CiAgICAgICAgICAgIHJldHVybiB0cnVlOwogICAgICAgIH0KICAgICAgICBwZW5ndV9lbmRlcnJvcignbGljZW5zZSBlcnJvciEnLCAnVGhlIHNjcmlwdCBsaWNlbnNlIGlzIG5vdCB2YWxpZCEgLSBlcnJvcigyMDEpJyk7CiAgICB9CgogICAgZnVuY3Rpb24gX19ub3RfcHJlbWl1bSgpCiAgICB7CiAgICAgICAgZ2xvYmFsICRyb3V0ZTsKICAgICAgICBpZiAoZnVuY3Rpb25fZXhpc3RzKCdtb2JpbGVBcHAnKSAmJiBtb2JpbGVBcHAoKSkgewogICAgICAgICAgICBnbG9iYWwgJG1vYmlsZWFwcDsKICAgICAgICAgICAgJG1vYmlsZWFwcCA9IGZhbHNlOwogICAgICAgICAgICBkaXJlY3Rpb246OnNldFZpZXdGb2xkZXIoJycpOwogICAgICAgICAgICByZXR1cm47CiAgICAgICAgfQoKICAgICAgICBpZiAoJHJvdXRlLT5nZXROYW1lKCkgIT0gJ2FkbWluY2hhbmdldG9wcmVtaXVtJyAmJiBzdHJwb3MoJHJvdXRlLT5nZXROYW1lKCksICdwb29saScpICE9PSBmYWxzZSkgewogICAgICAgICAgICBpZiAoZmlsZV9leGlzdHModGVtcGxhdGVfcGF0aCgpIC4gJy9ub3RwcmltdW1lLnBocCcpKQogICAgICAgICAgICAgICAgcmVmKHVybDo6cm91dGVyKCdhZG1pbmNoYW5nZXRvcHJlbWl1bScpKS0+cmVkaXJlY3QoKTsKICAgICAgICAgICAgZWxzZQogICAgICAgICAgICAgICAgcGVuZ3VfZW5kZXJyb3IoJ2xpY2Vuc2UgZXJyb3InLCAnQ2huYWdlIHRvIHByZW1pdW0gYWNjb3VudCEgLSBlcnJvcigyMDApJyk7CiAgICAgICAgfQogICAgfQoKICAgIGZ1bmN0aW9uIF8zdGhfY2hrX2xzKCYkYykKICAgIHsKICAgICAgICBzdGF0aWMgJGRvbmU7CiAgICAgICAgZ2xvYmFsICRsaWNlbnNlX3R5cGUsICR5Q2h0LCAkcm91dGU7CiAgICAgICAgaWYgKGlzc2V0KCRkb25lKSkKICAgICAgICAgICAgcmV0dXJuOwogICAgICAgICRkb25lID0gdHJ1ZTsKICAgICAgICBpZiAoc3RycG9zKHRlbXBsYXRlX3BhdGgoKSwgJy9hcHBsaWNhdGlvbi9hZG1pbicpICE9PSBmYWxzZSkKICAgICAgICAgICAgcmV0dXJuOwogICAgICAgICRwYXR0ZXJuID0gJy88XCFcLVwte1Nwb25zb3JMaW5rfVwtXC0+L2knOwogICAgICAgIGlmIChpbl9hcnJheShhY3Rpb24oKSwgYXJyYXkoJ3BhZ2U0MDQnLCAncGFnZV9tYWludGVuYW5jZScpKSkKICAgICAgICAgICAgcmV0dXJuOwogICAgICAgIGVsc2VpZiAoKCFwcmVnX21hdGNoKCRwYXR0ZXJuLCAkYykgfHwgIWRlZmluZWQoJ3lDaHQnKSkgJiYgJGxpY2Vuc2VfdHlwZSAhPSAncHJlbWl1bScpCiAgICAgICAgICAgIHBlbmd1X2VuZGVycm9yKCd0ZW1wbGF0ZSBlcnJvciEnLCAnVGhlIHRlbXBsYXRlIHN0cnVjdHVyZSBpcyBub3QgY29ycmVjdCEgLSBlcnJvcigyMDYpLCBtYXliZSB5b3VcJ3ZlIHJlbW92ZWQgIjwhLS17U3BvbnNvckxpbmt9LS0+IiBpbiB5b3VyIHRlbXBsYXRlIScpOwogICAgICAgIGVsc2UgaWYgKCFkZWZpbmVkKCd5Q2h0JykgJiYgJGxpY2Vuc2VfdHlwZSA9PSAncHJlbWl1bScpCiAgICAgICAgICAgIHJldHVybjsKICAgICAgICAkcyA9IG5ldyBwZW5ndV9zZXR0aW5nOwogICAgICAgICRzLT5zZXRTZXR0aW5nTmFtZSh5Q2h0KTsKICAgICAgICAkcy0+ZXhwaXJlVGltZSgyNCAqIDM2MDApOwogICAgICAgIGlmICgkcy0+ZXhpc3RzKCkpIHsKICAgICAgICAgICAgJGRlYyA9ICRzLT5nZXQoKTsKICAgICAgICB9IGVsc2UgewogICAgICAgICAgICBpZiAoKCEkZGVjID0gZGVjcnlwdCh5Q2h0LCAnaGRtc2FuJywgJ2NyeXB0JykpICYmICRsaWNlbnNlX3R5cGUgIT0gJ3ByZW1pdW0nKQogICAgICAgICAgICAgICAgcGVuZ3VfZW5kZXJyb3IoJ3RlbXBsYXRlIGVycm9yIScsICdUaGUgdGVtcGxhdGUgc3RydWN0dXJlIGlzIG5vdCBjb3JyZWN0ISAtIGVycm9yKDIwNyksIG1heWJlIHlvdVwndmUgcmVtb3ZlZCAiPCEtLXtTcG9uc29yTGlua30tLT4iIGluIHlvdXIgdGVtcGxhdGUhJyk7CiAgICAgICAgICAgIGVsc2UgewogICAgICAgICAgICAgICAgJHMtPnNhdmUoJGRlYyk7CiAgICAgICAgICAgIH0KICAgICAgICB9CiAgICAgICAgJGMgPSBAcHJlZ19yZXBsYWNlKCRwYXR0ZXJuLCAkZGVjLCAkYyk7CiAgICB9'));

    __2nd_chk_ls($l);
    event::register_onLoadView('_3th_chk_ls');
}
