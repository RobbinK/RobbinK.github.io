<?php

/* /-------------------------\ */
/* \      Source backup      / */
/* /-------------------------\ */
if (isset($_GET['delbackfilesrc'])) {
    $msgID = $_GET['backupmsg'];
    $backupfolder = tmp_path() . '/etc/' . $_GET['delbackfilesrc'];
    if (@unlink($backupfolder)) {
        CleanUpStrict($msgID);
        psuccess("the file <b>/tmp/etc/{$_GET['delbackfilesrc']}</b> was deleted successfuly. {$buttons}")->Id($msgID);
    } else
        perror("Unable to delete file!")->Id($msgID);
    ref(url::itself()->fulluri(array('delbackfilesrc' => null, 'backupmsg' => null)))->redirect();
}

if (isset($_GET['backupsource'])) {
    set_time_limit(10 * 60);
    $backupfolder = tmp_path() . '/etc';
    $backupfilename = "ab_sourcebackup_" . date('Y-m-d__H-i-s');
    $msgID = _syserror_get_msgID();
    
    if (@class_exists('ZipArchive')) {
        include_once lib_path() . '/flx_ziparchive.class.php';
        /* create zip */
        $za = new FlxZipArchive ();
        if ($za->open($backupfolder . '/' . $backupfilename . '.zip', ZipArchive::CREATE) === TRUE) {
            $za->addDir(root_path() . "/application", 'application');
            $za->addDir(root_path() . "/content/upload", 'content/upload');
            $za->addDir(root_path() . "/config", 'config');
            $za->addDir(root_path() . "/core", 'core');
            $za->addDir(root_path() . "/themes", 'themes');
            $za->addDir(root_path() . "/tmp/ls", 'tmp/ls');
            $za->addFile(root_path() . "/.htaccess", '.htaccess');
            $za->addFile(root_path() . "/license.php", "license.php");
            $za->addFile(root_path() . "/index.php", "index.php");
            $za->addFile(root_path() . "/path.php", "path.php");
            $za->close();

            $buttons = '  <a href="' . tmp_url() . '/etc/' . $backupfilename . '.zip' . '" class="btn btn-mini btn-success">Download</a>'
                    . ' <a href="' . url::router('admindashboard')->fulluri(array('delbackfilesrc' => $backupfilename . '.zip', 'backupmsg' => $msgID)) . '" class="btn btn-mini btn-danger">Delete</a>';
            psuccess("Your backup file was saved in <b>/tmp/etc/{$backupfilename}.zip</b> successfuly. {$buttons}")->Id($msgID)->live();
        } else
            perror("Unable to backup files!")->Id($msgID);
    } else {
        include_once(lib_path() . '/pclzip.lib.php');
        $archive = new PclZip($backupfolder . '/' . $backupfilename . '.zip');
        $files = array(
            root_path() . "/application",
            root_path() . "/content/upload",
            root_path() . "/config",
            root_path() . "/core",
            root_path() . "/themes",
            root_path() . "/tmp/ls",
            root_path() . "/.htaccess",
            root_path() . "/license.php",
            root_path() . "/index.php",
            root_path() . "/path.php"
        );
        $v_list = @$archive->create($files, PCLZIP_OPT_REMOVE_PATH, root_path());
        if ($v_list == 0)
            perror("Unable to backup files!")->Id($msgID);
        else {
            $buttons = '  <a href="' . tmp_url() . '/etc/' . $backupfilename . '.zip' . '" class="btn btn-mini btn-success">Download</a>'
                    . ' <a href="' . url::router('admindashboard')->fulluri(array('delbackfilesrc' => $backupfilename . '.zip','backupmsg' => $msgID)) . '" class="btn btn-mini btn-danger">Delete</a>';
            psuccess("Your backup file was saved in <b>/tmp/etc/{$backupfilename}.zip</b> successfuly. {$buttons}")->Id($msgID)->live();
        }
    }
    ref(url::itself()->fulluri(array('backupsource' => null)))->redirect();
}

/* /-------------------------\ */
/* \     DataBase backup     / */
/* /-------------------------\ */

if (isset($_GET['delbackfiledb'])) {
    $msgID = $_GET['backupmsg'];
    $backupfolder = tmp_path() . '/etc/' . $_GET['delbackfiledb'];
    if (@unlink($backupfolder)) {
        CleanUpStrict($msgID);
        psuccess("The file <b>/tmp/etc/{$_GET['delbackfiledb']}</b> was deleted successfuly. {$buttons}")->Id($msgID);
    } else
        perror("Unable to delete file!")->Id($msgID);
    ref(url::itself()->fulluri(array('delbackfiledb' => null, 'backupmsg' => null)))->redirect();
}


if (isset($_GET['backupdb'])) {
    set_time_limit(10 * 60);
    $backupfolder = tmp_path() . '/etc';
    $backupfilename = "ab_dbbackup_" . date('Y-m-d__H-i-s');

    $msgID = _syserror_get_msgID();
    /* db backup */
    include_once lib_path() . '/dbbackup.class.php';
    $s = new pengu_dbbackup();
    if ($dbbackfile = $s->execute($backupfolder . '/' . $backupfilename, true)->saveToFile()) {
        $backupfilename = basename($dbbackfile);
        $buttons = '  <a href="' . tmp_url() . '/etc/' . $backupfilename . '" class="btn btn-mini btn-success">Download</a>'
                . ' <a href="' . url::router('admindashboard')->fulluri(array('delbackfiledb' => $backupfilename,'backupmsg' => $msgID)) . '" class="btn btn-mini btn-danger">Delete</a>';
        psuccess("Your backup file was saved in <b>/tmp/etc/{$backupfilename}</b> successfuly. {$buttons}")->Id($msgID)->live();
    } else
        perror("Unable to backup files!")->Id($msgID);

    ref(url::itself()->fulluri(array('backupdb' => null)))->redirect();
}