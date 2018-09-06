<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: sqlcach.class.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:59
##########################################################
 */


class pengu_sqlcach extends pengu_tmp {

    function __construct($prefix = 'sql_', $ext = '.dat') {
        parent::__construct(cache_path() . '/mysql', 'sql_', '.dat', false);
    }

    function write($filedata) {
        return parent::write($filedata);
    }

    function read() {
        return parent::read();
    }

    function isCached() {
        return parent::checkExists();
    }

    function expireTime($seconds) {
        parent::expireTime($seconds);
    }

    function setPath($path = null) {
        parent::setPath($path);
    }

    function setKey($keyName) {
        parent::setKey($keyName);
    }

}

