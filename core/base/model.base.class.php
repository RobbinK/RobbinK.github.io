<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: model.base.class.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:59
##########################################################
 */


class Model extends pengu_db {

    function __construct() {
        global $ConnectOptions, $PenguInflector;
        parent::__construct($ConnectOptions);

        if (!isset($this->_table))
            $this->_table = strtolower($PenguInflector->get_plural(get_class($this)));

        global $pengu_dbhandle;
        $this->handle = $pengu_dbhandle;

        if (!$this->ping())
            pengu_enderror('Connection error!','Could not establish a connection to database!');
        else
            $pengu_dbhandle = $this->handle;
    }

}
