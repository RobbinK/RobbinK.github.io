<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: Iprange.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:57
##########################################################
 */


class Iprange extends Model {

    protected $_table = "abs_iprange";

    function exec(array $params = null) {
        $data = parent::exec($params);
        if ($data)
            return $data;
        elseif (parent::errorno()) {
            _show_mysql_error(parent::lastsql(), parent::lasterror());
        }
    }

}
