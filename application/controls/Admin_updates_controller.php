<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: Admin_updates_controller.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:57
##########################################################
 */


class admin_updatesController extends AdministrationController {

    protected $_model = null;

    function __construct() {
        parent::__construct();
        $this->MapViewFile_groupFolder('vg_admin_update');

        /* get comment from setting */

        function getcomment($data) {
            $result = array();
            $value = $data['comment_' . lang()];
            if (strpos($value, '#')) {
                $result['title'] = substr($value, 0, strpos($value, '#'));
                $result['text'] = substr($value, strpos($value, '#') + 1);
            } else
                $result['text'] = substr($value, 0);
            if (!empty($result['text']) || !empty($result['title']))
                return "<a class='pop_over' data-placement='right' data-original-title='" . @$result['title'] . "' data-content='" . @$result['text'] . "'>";

            return false;
        }

    }

    function updatescript() {
        $this->islogin();

        if (!_dbaffecting()) {
            $this->forbidden();
            return;
        }

        if (!isset($_GET['step'])) {
            $step = 1;
            $this->MapViewFileName('updatescript_step1.php');
        } else {
            $step = intval($_GET['step']);
            $this->MapViewFileName('updatescript_step' . $step . '.php');
        }

        $model = new Setting;
        if (validate::_is_ajax_request()) {
            $this->view->disable();
            if ($step == 1 && isset($_GET['save'])) {
                $this->getPOST($_POST);

                $error = 0;
                $json_out['save_code'] = 0;
                $json_out['save_txt'] = '';
                foreach ($_POST as $type => $data) {
                    foreach ($data as $k => $v) {
                        $result = Setting::save_value($type, $k, $v);
                        if ($result === false)
                            $error = 1;
                    }
                }

                if (!$error) {
                    $json_out['save_code'] = 1;
                    $json_out['save_txt'] = L::alert_data_save;
                } else {
                    $json_out['save_code'] = 0;
                    $json_out['save_txt'] = L::alert_err_in_saving_data;
                }
                echo json_encode($json_out);
                exit;
            } else if ($step == 2) {
                /* -------------------- */
                /* some other functions */

                function redirect($link, $time = 1000) {
                    return "  setTimeout(function() {" . ref($link)->locate() . "},{$time});";
                }

                pengu_user_load_lib('ab_update_funcs');
                /* ----- Check FTP ----- */
                if ($_POST['act'] == 'ftpcon') {
                    $server = Setting::get_data('ftp_host', 'val');
                    $username = Setting::get_data('ftp_username', 'val');
                    $password = Setting::get_data('ftp_password', 'val');
                    $path = Setting::get_data('ftp_script_path', 'val');
                    if (!empty($server) && !empty($username) && $conn_id = checkFtpConnection($server, $username, $password)) {
                        if (!ftp_is_dir($conn_id, rtrim(leftchar('/', $path), '/') . '/application') && !is_dir("ftp://{$username}:{$password}@{$server}" . rtrim(leftchar('/', $path), '/') . '/application')) {
                            $msg = "showST('" . L::alert_path_not_detected . "');";
                            $msg .= redirect(url::itself()->url_nonqry(array('step' => 1)));
                            warning(L::alert_path_not_detected)->Id('updatescriipt');
                            exit($msg);
                        }

                        /* ==== check mysql alter ==== */
                        $model = new Model();
                        $GRANTS = $model->query("SHOW GRANTS FOR CURRENT_USER()")->exec()->allrows();
                        $alter_pr_found = true;
                        if (count($GRANTS) > 1) {
                            for ($i = 1; $i < count($GRANTS); $i++) {
                                $gr = current($GRANTS[$i]);
                                if (preg_match('/GRANT\s*((?:[\s\w]*\,\s)+)/i', $gr, $matches)) {
                                    $alter_pr_found = false;
                                    if (strpos($matches[1], 'ALTER') !== false) {
                                        $alter_pr_found = true;
                                    }
                                }
                            }
                        }
                        if (!$alter_pr_found) {
                            $msg = "showST('<font style=\'color:red\'>" . L::alert_not_enough_permission . "</font>');";
                            exit($msg);
                        }
                        /* ==== //check mysql alter ==== */

                        $msg = "progress();";
                        $msg .="showST('" . L::alert_downloading_patch . "');";
                        $msg .="ajaxProcess({act: 'grabpatch'});";
                        exit($msg);
                    } else {
                        $msg = "showST('" . L::alert_invalid_ftp . "');";
                        $msg .= redirect(url::itself()->url_nonqry(array('step' => 1)));
                        warning(L::alert_invalid_ftp . " - <a href='" . master_url . "/updatemanually.html?from=" . sys_ver . "'>Update Manually</a>")->Id('updatescriipt');
                        exit($msg);
                    }
                }
                /* ----- grab/save/extraction patch ----- */
                if ($_POST['act'] == 'grabpatch') {

                    $tmpPath = ab_content_dir . '/updatescipt';
                    $extPath = $tmpPath . '/extracted';
                    if (!file_exists($tmpPath))
                        rmkdir($tmpPath);
                    if (!isEmptyDir($extPath)) {
                        $msg = "progress();";
                        $msg .="showST('The extracted files are found.');";
                        $msg .="showST('" . L::alert_making_backup . "');";
                        $msg .="ajaxProcess({act: 'backup'});";
                        die($msg);
                    }

                    $CurrentVersion = sys_ver;
                    $StabledVersion = ws_available_version();
                    if (preg_match('/[\d\.]+/', $CurrentVersion) && preg_match('/[\d\.]+/', $StabledVersion) && !empty($CurrentVersion) && $CurrentVersion != $StabledVersion) {
                        $url = master_url . "/patch.html?from=" . $CurrentVersion . "&to=" . $StabledVersion;
                        $fileurl = str_replace(" ", "%20", $url);

                        if ($patchURL = @file_get_contents($fileurl.'&getpath'))
                            $fileurl = $patchURL;

                        //--grab file
                        set_time_limit(0);
                        $filename = "{$CurrentVersion}-to-{$StabledVersion}-patch.zip";
                        if (!file_exists($tmpPath . '/' . $filename)) {
                            $fp = fopen($tmpPath . '/' . $filename, 'w+');

                            $uploaded = false;
                            if (function_exists('curl_init')) {
                                try {
                                    $ch = curl_init($fileurl);
                                    if (!@curl_setopt($ch, CURLOPT_TIMEOUT, 50))
                                        throw new Exception("");
                                    if (!@curl_setopt($ch, CURLOPT_FILE, $fp))
                                        throw new Exception("");
                                    if (!@curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true))
                                        throw new Exception("CURLOPT_FOLLOWLOCATION cannot be activated when safe_mode is enabled");
                                    if (curl_exec($ch))
                                        $uploaded = true;
                                    curl_close($ch);
                                } catch (Exception $error) {
                                    $uploaded = false;
                                    if ($data = file_get_contents($fileurl))
                                        if (fwrite($fp, $data))
                                            $uploaded = true;
                                }
                            } else {
                                if ($data = path::file_get_contents_fopen($fileurl)) {
                                    if (fwrite($fp, $data))
                                        $uploaded = true;
                                }
                            }
                            fclose($fp);
                            permup($tmpPath . '/' . $filename);
                            //--manual
                            if (!$uploaded) {
                                @unlink($tmpPath . '/' . $filename);
                                $msg = "showST('<font style=\'color:red\'>The patch file cannot be grabbed due to your server configurations.</font>');";
                                $msg .="showST('<div class=\'hint\'>Download this  <a href=\'{$fileurl}\'>patch file</a> manualy and upload to <b>/ content / updatescipt /</b> folder and then update your script again (Refresh this page)</div>');";
                                $msg .= "finish(false);";
                                die($msg);
                            }
                        }
                        if (file_exists($tmpPath . '/' . $filename)) {
                            $extractError = 0;
                            if (!file_exists($extPath))
                                rmkdir($extPath);

                            if (class_exists('ZipArchive')) {
                                include_once lib_path() . '/flx_ziparchive.class.php';
                                $za = new FlxZipArchive;
                                if ($za->open($tmpPath . '/' . $filename) === true) {
                                    $za->extractTo($extPath);
                                    if (isEmptyDir($extPath))
                                        $extractError = 1;
                                }
                                else {
                                    $extractError = 1;
                                    echo "showST('" . L::alert_extraction_failed . "');";
                                }
                                $za->close();
                            } else {
                                include_once lib_path() . "/pclzip.lib.php";
                                $archive = new PclZip($tmpPath . '/' . $filename);
                                if ($archive->extract(PCLZIP_OPT_PATH, $extPath) == 0) {
                                    $extractError = 1;
                                    echo "showST('" . $archive->errorInfo(true) . "');";
                                }
                            }

                            if ($extractError) {
                                $msg = "showST('<font style=\'color:red\'>The patch file cannot be extracted due to your server configurations.</font>');";
                                $msg .="showST('<div class=\'hint\'>Download this  <a href=\'{$fileurl}\'>patch file</a> manualy and extract it to <b>/ content / updatescipt / extracted</b> folder and then update your script again (Refresh this page)</div>');";
                                $msg .= "finish(false);";
                                die($msg);
                            }
                            $msg = "progress();";
                            $msg .="showST('" . L::alert_making_backup . "');";
                            $msg .="ajaxProcess({act: 'backup'});";
                            exit($msg);
                        } else
                            $msg = "showST('" . L::alert_not_exists . "');";
                        exit;
                    }

                    $msg = "finish(false);";
                    $msg .= "showST('" . L::alert_up_to_dated . "');";
                    exit($msg);
                }
                /* ----- Backup ----- */
                if ($_POST['act'] == 'backup') {
                    set_time_limit(10 * 60);
                    $backupfolder = content_path() . '/updatescipt/backup';
                    $backupfilename = 'source' . date('Y-m-d_H-i-s') . '.version' . sys_ver;
                    if (!file_exists($backupfolder))
                        rmkdir($backupfolder);

                    /* db backup */
                    include_once lib_path() . '/dbbackup.class.php';
                    $s = new pengu_dbbackup();
                    $s->tables = array(
                        'abs_ads',
                        'abs_blocks',
                        'abs_categories',
                        'abs_games',
                        'abs_games_mobile',
                        'abs_links',
                        'abs_members',
                        'abs_members_group',
                        'abs_pages',
                        'abs_settings',
                        'abs_submitted_games',
                        'abs_trade_history',
                        'abs_trade_plugs',
                        'abs_traders',
                        'abs_traders_domains',
                        'abs_traders_geo',
                        'abs_zones'
                    );
                    $dbbackfile = $s->execute(content_path() . '/updatescipt/backup/db.sql')->saveToFile();

                    if (@class_exists('ZipArchive')) {
                        include_once lib_path() . '/flx_ziparchive.class.php';
                        /* create zip */
                        $za = new FlxZipArchive ();
                        if ($za->open($backupfolder . '/' . $backupfilename . '.zip', ZipArchive::CREATE) === TRUE) {
                            $za->addDir(root_path() . "/application", 'application');
                            $za->addDir(root_path() . "/config", 'config');
                            $za->addDir(root_path() . "/core", 'core');
                            $za->addDir(root_path() . "/themes", 'themes');
                            $za->addDir(root_path() . "/tmp/ls", 'tmp/ls');
                            $za->addFile(root_path() . "/.htaccess", '.htaccess');
                            $za->addFile(root_path() . "/license.php", "license.php");
                            $za->addFile(root_path() . "/index.php", "index.php");
                            $za->addFile(root_path() . "/path.php", "path.php");
                            if (!empty($dbbackfile))
                                $za->addFile($dbbackfile, 'db.sql');
                            $za->close();
                        }
                    }
                    /*
                      else {
                      include_once(lib_path() . '/pclzip.lib.php');
                      $archive = new PclZip($backupfolder . '/' . $backupfilename . '.zip');
                      $files = array(
                      root_path() . "/application",
                      root_path() . "/config",
                      root_path() . "/core",
                      root_path() . "/themes",
                      root_path() . "/tmp/ls",
                      root_path() . "/.htaccess",
                      root_path() . "/license.php",
                      root_path() . "/index.php",
                      root_path() . "/path.php"
                      );
                      if (!empty($dbbackfile))
                      $files[] = $dbbackfile;

                      $v_list = $archive->create($files, PCLZIP_OPT_REMOVE_PATH, root_path());
                      if ($v_list == 0) {
                      die("showST('" . $archive->errorInfo(true) . "');");
                      }
                      }
                     */
                    $msg = "progress();";
                    $msg .="showST('" . L::alert_installing_new_version . "');";
                    $msg .="ajaxProcess({act: 'install'});";
                    exit($msg);
                }
                /* --------- install --------- */
                if ($_POST['act'] == 'install') {
                    set_time_limit(0);

                    if (isEmptyDir(content_path() . '/updatescipt/extracted/')) {
                        $msg = "showST('Extract folder is empty!');";
                        $msg .= "finish(false);";
                        die($msg);
                    }

                    function ftp_putAll($conn_id, $src_dir, $dst_dir) {
                        $d = dir($src_dir);
                        while ($file = $d->read()) { // do this for each file in the directory
                            if (strstr($file, '_dbchanges') !== false || strstr($file, '_pfiles') !== false)
                                continue;
                            if ($file != "." && $file != "..") { // to prevent an infinite loop
                                if (is_dir($src_dir . "/" . $file)) { // do the following if it is a directory
                                    if (!@ftp_chdir($conn_id, $dst_dir . "/" . $file)) {
                                        @ftp_mkdir($conn_id, $dst_dir . "/" . $file); // create directories that do not yet exist
                                    }
                                    ftp_putAll($conn_id, $src_dir . "/" . $file, $dst_dir . "/" . $file); // recursive part
                                } else {
                                    $upload = ftp_put($conn_id, $dst_dir . "/" . $file, $src_dir . "/" . $file, FTP_BINARY); // put the files
                                }
                            }
                        }
                        $d->close();
                    }

                    if (!isLocalServer()) {
                        $server = Setting::get_data('ftp_host', 'val');
                        $username = Setting::get_data('ftp_username', 'val');
                        $password = Setting::get_data('ftp_password', 'val');
                        $path = Setting::get_data('ftp_script_path', 'val');

                        if ($conn_id = checkFtpConnection($server, $username, $password)) {
                            ftp_pasv($conn_id, true);
                            ftp_putAll($conn_id, content_path() . '/updatescipt/extracted/', $path);
                        }
                    } elseif (!DEVELOP)
                        path::RecursiveCopy(content_path() . '/updatescipt/extracted/', root_path());
                    $msg = "progress();";
                    $msg .="showST('" . L::alert_installing_db . "');";
                    $msg .="ajaxProcess({act: 'installdb'});";
                    exit($msg);
                }
                /* --------- installdb --------- */
                if ($_POST['act'] == 'installdb') {
                    $sqlfiles = glob(content_path() . '/updatescipt/extracted/_dbchanges/*.sql');
                    $error = 0;
                    if (is_array($sqlfiles) && !empty($sqlfiles)) {
                        @natsort($sqlfiles);
                        $model = new Model();
                        foreach ($sqlfiles as $file) {
                            $sqldata = @file_get_contents($file);
                            if (strpos($sqldata, '/*sep*/')) {
                                $sqlrows = explode('/*sep*/', $sqldata);
                                foreach ($sqlrows as $srows) {
                                    if (!empty($srows) && !DEVELOP)
                                        if ($model->query($srows)->exec()===false)
                                            $error++;
                                }
                            }else {
                                if (!empty($sqldata) && !DEVELOP)
                                    if (!$model->multiquery($sqldata)->exec()===false)
                                        $error++;
                            }
                        }
                    }
                    if ($error) {
                        $msg = "showST('{$error} " . L::alert_error_in_db . "');";
                        $msg .="ajaxProcess({act: 'finish'});";
                    } else {
                        @rrmdir(content_path() . '/updatescipt/extracted/_dbchanges');
                        $msg = "ajaxProcess({act: 'finish'});";
                    }
                    exit($msg);
                }
                /* --------- Finish --------- */
                if ($_POST['act'] == 'finish') {
                    /* run external pfiles */
                    $pfiles = glob(content_path() . '/updatescipt/extracted/_pfiles/*.php');
                    $error = 0;
                    if (is_array($pfiles) && !empty($pfiles)) {
                        @natsort($pfiles);
                        foreach ($pfiles as $file) {
                            @include_once($file);
                        }
                    }
                    /* \\\\\\\\\\\\\/////////// */
                    rrmdir(content_path() . '/updatescipt/extracted');
                    $msg = "abs_cache.clean_mysql();";
                    $msg .= "progress();";
                    $msg .= "showST('" . L::alert_update_complete . "');";
                    $msg .= redirect(url::router('admindashboard'), 5000);
                    psuccess(L::alert_update_success . ' ' . ws_available_version())->Id('syserror')->live();
                    exit($msg);
                }
                /* ----- End ----- */
            }
        }
    }

}
