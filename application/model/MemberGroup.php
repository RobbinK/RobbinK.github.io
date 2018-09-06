<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: MemberGroup.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:57
##########################################################
 */


class MemberGroup extends Model {

    protected $_table = 'abs_members_group';
    static $instance;

    private static function createInstance() {
        if (!self::$instance) {
            $className = get_class();
            self::$instance = new $className;
        }
    }

    static function getAll($filter = null) {
        static $data;
        if (!isset($data)) {
            self::createInstance();
            $data = self::$instance->select()->exec();
        }
        if (!empty($data)){
            if (is_array($filter))
                return arrayUtil::array_search($data->allrows(), key($filter), current($filter));
            return $data;
        }
    }

}