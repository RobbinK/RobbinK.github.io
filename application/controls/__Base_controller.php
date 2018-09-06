<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: __Base_controller.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:57
##########################################################
 */


class BaseController extends Controller
{

    function __construct()
    {
        parent::__construct();

        include app_path().'/user/libs/library_ulib.php';

        function _show_mysql_error($sql, $sqlError, $end = true)
        {
            @ob_clean();
            include static_path() . "/pages/sql-error-page.php";
            if ($end)
                exit;
        }

        global $abQS;
        $abQS = array('aberror', 'abtheme', 'abscheme', 'abandroid', 'abios');

        if (Setting::get_data('default_time_zone', 'val'))
            date_default_timezone_set(Setting::get_data('default_time_zone', 'val'));
        else
            date_default_timezone_set("Europe/London");

        function abversion_more_or_equal($version)
        {
            return version_compare(sys_ver, $version) >= 0;
        }

        function abversion_less_than($version)
        {
            return version_compare(sys_ver, $version) < 0;
        }

        function abversion_equal($version)
        {
            return version_compare(sys_ver, $version) == 0;
        }

        function generatingStats()
        {
            static $ret;
            if (isset($ret))
                return $ret;
            if (convert::to_bool(setting::get_data('geo_stats', 'val')) || convert::to_bool(Setting::get_data('active_trading', 'val')))
                $ret = true;
            else
                $ret = false;
            return $ret;
        }

        $this->check_visitor_banned();
        //defs
        define('ab_content_dir', content_path());
        define('ab_content_url', content_url());

        define('ab_upload_dir', ab_content_dir . '/upload');
        define('ab_upload_url', ab_content_url . '/upload');

        define('ab_tmp_dir', ab_content_dir . '/upload/tmp');
        define('ab_tmp_url', ab_content_url . '/upload/tmp');

        define('ab_game_files_dir', ab_content_dir . '/upload/games/files');
        define('ab_game_files_url', ab_content_url . '/upload/games/files');

        define('ab_game_images_dir', ab_content_dir . '/upload/games/images');
        define('ab_game_images_url', ab_content_url . '/upload/games/images');

        define('ab_submission_files_dir', ab_content_dir . '/upload/submission/files');
        define('ab_submission_files_url', ab_content_url . '/upload/submission/files');

        define('ab_submission_images_dir', ab_content_dir . '/upload/submission/images');
        define('ab_submission_images_url', ab_content_url . '/upload/submission/images');

        pengu_user_load_lib('ab_showimage_funcs');
    }

    function getPOST(&$post)
    {
        if (isset($post['encodedData'])) {
            if (validate::_is_Base64($post['encodedData']))
                $post['encodedData'] = json_decode(base64::decode($post['encodedData']), true);
            else
                $post['encodedData'] = json_decode($post['encodedData'], true);
            foreach ($post['encodedData'] as $k => $v)
                $post[$k] = $v;
            unset($post['encodedData']);
        } else {
            foreach ($post as &$v) {
                if (is_array($v))
                    $this->getPOST($v);
                else
                    $v = rawurldecode($v);
            }
        }
    }

    function check_visitor_banned()
    {
        $visitor_ip = agent::get_client_ip();
        $hashname = 'ab_' . md5('ChkBanIp' . $visitor_ip);
        if (!isset($_COOKIE[$hashname]) || $_COOKIE[$hashname] != 1) {
            $denyIps = array();
            $data = setting::get_data('members_banned_ips', 'val');
            if (!empty($data)) {
                $denyIps = explode(',', $data);
                array_walk($denyIps, create_function('&$v', '$v=trim($v);'));
                if (in_array($visitor_ip, $denyIps)) {
                    echo "<div style='position:absolute;top:43%;left:39%;text-align:center;'>";
                    echo "<b>" . L::alert_ip_banned . "</b>";
                    echo "</div>";
                    exit;
                }
            }
            setcookie($hashname, 1, time() + (10 * 60), '/');
        }
    }

    function send_mail_by_tpl($email, $subject, array $data, $tpl_name, $options = null)
    {
        static $econtent;
        $filename = app_path() . '/emails/' . path::get_filename($tpl_name) . '.tpl';
        if (empty($econtent) && file_exists($filename))
            $econtent = file_get_contents($filename);

        if (!empty($econtent)) {
            $edata = array();
            foreach ($data as $k => $v)
                $edata["[[{$k}]]"] = $v;
            $econtent = strtr($econtent, $edata);
            return $this->send_mail($email, $subject, $econtent, $options);
        }
        return false;
    }

    function send_mail($email, $subject, $body, $options = null)
    {
        if (!class_exists('PHPMailer'))
            return false;
        $mail = new PHPMailer();

        if (isset($options['sender_email'])) {
            $sender_mail = $options['sender_email'];
            $sender_name = @$options['sender_name'];
            $mail->SetFrom($sender_mail, $sender_name);
        } else if (defined('SetEmailFrom')) {
            $sender_mail = SetEmailFrom;
            $sender_name = defined('SetEmailName') ? SetEmailName : null;
            $mail->SetFrom($sender_mail, $sender_name);
        }

        $mail->AddAddress($email);
        $mail->Subject = (!empty($subject) ? $subject : "Untitled");
        $mail->MsgHTML($body);
        if ($mail->send())
            return true;
        return false;
    }

    function preview_img($path)
    {
        $w = 65;
        $h = 65;
        if (isset($_GET['size'])) {
            if ($_GET['size'] == 'auto') {
                $w = null;
                $h = null;
            } else {
                list ($w, $h) = explode('x', $_GET['size']);
                $w = !intval($w) ? null : intval($w);
                $h = !intval($h) ? null : intval($h);
            }
        }

        function showimage($src, $w = null, $h = null)
        {
            $ins = pengu_image::resize($src, $w, $h);
            $ins->ReCreate();
            $newsource = $ins->getImagePath();
            $imginfo = getimagesize($newsource);
            header("Content-type: {$imginfo['mime']}");
            readfile($newsource);
            exit;
        }

        if (empty($path) || !file_exists($path))
            $path = content_path() . '/images/no-img.jpg';
        showimage($path, $w, $h, false);
        exit;
    }

    function preview_swf($filename)
    {
        $this->view->disable();
        if (validate::_is_URL($filename)) {
            $gamefile_url = $filename;
        } elseif (file_exists(ab_tmp_dir . '/' . $filename)) {
            list($w, $h) = @getimagesize(ab_tmp_dir . '/' . $filename);
            $gamefile_url = ab_tmp_url . '/' . $filename;
        } elseif (file_exists(ab_game_files_dir . '/' . $filename)) {
            list($w, $h) = @getimagesize(ab_game_files_dir . '/' . $filename);
            $gamefile_url = ab_game_files_url . '/' . $filename;
        } elseif (file_exists(ab_submission_files_dir . '/' . $filename)) {
            list($w, $h) = @getimagesize(ab_submission_files_dir . '/' . $filename);
            $gamefile_url = ab_submission_files_url . '/' . $filename;
        } else
            exit(L::alert_file_not_found);
        $width = isset($w) ? $w . 'px' : '700px';
        $height = isset($h) ? $h . 'px' : '500px';
        $ext = path::get_extension($gamefile_url);
        ?>
        <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
            "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
        <html xmlns="http://www.w3.org/1999/xhtml">
        <style>
            * {
                margin: 0px;
                padding: 0px;
            }

            html {
                margin: 0px;
                padding: 0px;
            }

            body {
                margin: 0px;
                padding: 0px;
            }

            #cboxLoadedContent {
                background-color: black;
            }
        </style>
        <body>
        <?php
        if ($ext == 'unity3d'):
            ?>
            <script src="<?= static_url() . '/js/jquery.unity3d.js' ?>" type="text/javascript"></script>
            <script type="text/javascript">
                $(function () {
                    $("#unityPlayer").unity3d({
                        file: "<?= $gamefile_url ?>",
                        width: '<?= $width ?>',
                        height: '<?= $height ?>'
                    });
                });
            </script>
            <div id="unityPlayer" style="width:<?= $width ?>;height:<?= $height ?>">
                <div class="missing">
                    <a href="http://unity3d.com/webplayer/" title="Unity Web Player. Install now!">
                        <img alt="Unity Web Player. Install now!"
                             src="http://webplayer.unity3d.com/installation/getunity.png" width="193" height="63"/>
                    </a>
                </div>
            </div>
        <?php
        elseif ($ext == 'dcr'):
            ?>
            <object classid="clsid:166B1BCA-3F9C-11CF-8075-444553540000"
                    codebase="http://download.macromedia.com/pub/shockwave/cabs/director/sw.cab#version=11,0,0,09"
                    id="soccermomroadtrip" width="<?= $width ?>" height="<?= $height ?>">
                <param name="src" value="<?= $gamefile_url ?>"/>
                <param name="swStretchStyle" value="fill"/>
                <param name="swRemote"
                       value="swSaveEnabled='true' swVolume='true' swRestart='true' swPausePlay='true' swFastForward='true' swContextMenu='true' "/>
                <param name="bgColor" value="#000000/"/>
                <param name="PlayerVersion" value="11"/>
                <embed src="<?= $gamefile_url ?>" bgcolor="#000000" base="." name="soccermomroadtrip" sw1="140315"
                       sw2="110705" swliveconnect="true" playerversion="11" swstretchstyle="fill"
                       sw8="16a1e53cde1caebc58b55fb66da7c6b1" sw9="soccer-mom-road-trip"
                       swremote="swSaveEnabled='true' swVolume='true' swRestart='true' swPausePlay='true' swFastForward='true' swContextMenu='true' "
                       swlist="" type="application/x-director"
                       pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveDirector"
                       width="<?= $width ?>" height="<?= $height ?>"></embed>
            </object>
        <?php
        elseif ($ext == 'swf'):
            ?>
            <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
                    codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,16,0"
                    width="<?= $width ?>" height="<?= $height ?>">
                <param name="movie" value="<?= $gamefile_url ?>"/>
                <param name="quality" value="high"/>
                <param name="wmode" value="transparent"/>
                <param name="allowscriptaccess" value="never"/>
                <embed src="<?= $gamefile_url ?>" quality="high" name="FlashContent" allowscriptaccess="never"
                       type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer"
                       wmode="transparent" width="<?= $width ?>" height="<?= $height ?>"></embed>
            </object>
        <?php
        elseif (isset($gamefile_url)) :
            ?>
            <div id="GameFileWrapper" style="width:<?= $width ?>;height:<?= $height ?>;margin:0 auto;">
                <iframe style="width:100%;height:100%;margin:0 auto;" src="<?= $gamefile_url ?>"
                        frameborder="0"></iframe>
            </div>
        <?php
        endif;
        ?>
        </body>
        </html>
    <?php
    }

}
