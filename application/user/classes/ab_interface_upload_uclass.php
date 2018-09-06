<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: ab_interface_upload_uclass.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:58
##########################################################
 */


class ab_interface_uploadUclass {

    private $indexes = array();
    private $validExtentions = array();
    private $handle;

    function setValidExtentions($exts) {
        array_walk($exts, create_function('&$v,$k', '$v=preg_quote($v);'));
        $this->validExtentions = $exts;
    }

    function cleanupIndexes() {
        $this->files = array();
    }

    function addToIndex($filenames) {
        if (!is_array($filenames))
            $filenames = array($filenames);
        $this->indexes = array_merge($this->indexes, $filenames);
    }

    function getIndexes() {
        return $this->indexes;
    }

    function hadfile() {
        return current($this->indexes);
    }

    function nextfile() {
        return next($this->indexes);
    }

    function upload($destDir, $newFileBodyName = null) {
        $ret = false;
        if ($cindex = $this->hadfile()) {
            if (isset($_FILES[$cindex]) || $_GET[$cindex]) {
                if (isset($_FILES[$cindex]))
                    $this->handle = new Upload($_FILES[$cindex]);
                elseif (isset($_GET[$cindex]))
                    $this->handle = new upload('php:' . $_SERVER['HTTP_X_FILE_NAME']);
                $this->handle->mime_types['dcr'] = 'application/x-director';
                $this->handle->mime_types['unity3d'] = 'application/vnd.unity';
                $this->handle->allowed[]='application/x-director';
                $this->handle->allowed[]='application/vnd.unity';
                $this->handle->translation['destination_dir'] = "<label class='label label-important'>Unable to create directory " . str_replace(ROOT_PATH, '', $destDir) . "! Is its parent directory writable by the server?</label>";
                if ($this->handle->uploaded) {
                    if ($newFileBodyName)
                        $this->handle->file_new_name_body = $newFileBodyName;
                    $ext = $this->handle->file_src_name_ext;
                    if ($ext == 'dcr')
                        $this->handle->file_src_mime = $this->handle->mime_types['dcr'] ;
                    if ($ext == 'unity3d')
                        $this->handle->file_src_mime = $this->handle->mime_types['unity3d'];
                    //$this->mime_check=false;
                    if (!preg_match("/" . join('|', $this->validExtentions) . "/i", $ext)) {
                        $this->error = "File type is not accepted for '{$this->handle->file_src_name}'";
                        $ret = false;
                    } else {
                        $this->handle->Process($destDir);
                        if ($this->handle->processed) {
                            $ret = $this->handle->file_dst_name;
                            permup($this->handle->file_dst_pathname);
                        }
                    }
                    $this->handle->clean();
                }
            }
            $this->nextfile();
        }
        return $ret;
    }

    function getErrorMsg() {
        if (isset($this->error))
            return $this->error;
        if (!empty($this->handle->error))
            return $this->handle->error;
    }

    function proccess_getkey() {
        return(json_encode(array('key' => uniqid())));
    }

    function proccess_st($progresskey) {
        if (isset($progresskey)) {
            if (function_exists('apc_fetch'))
                $status = apc_fetch('upload_' . $progresskey);
        }
        else
            return(json_encode(array('success' => false)));

        $pct = 0;
        $size = 0;

        if (isset($status) && is_array($status)) {
            if (array_key_exists('total', $status) && array_key_exists('current', $status)) {
                if ($status['total'] > 0) {
                    $pct = round(( $status['current'] / $status['total']) * 100);
                    $size = round($status['total'] / 1024);
                }
            }
        }

        return json_encode(array('success' => true, 'pct' => $pct, 'size' => $size));
    }

}