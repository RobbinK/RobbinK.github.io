<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: Post_Log.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:57
##########################################################
 */


class Post_Log extends Model {

    protected $_table = 'abs_post_log';
    private static $instance;

    private static function createInstance() {
        if (!self::$instance) {
            $className = get_class();
            self::$instance = new $className;
        }
    }

    static function getLimitedData($postType, $limit = 30) {
        self::createInstance();
        return self::$instance->select('post_id,post_title,insert_time,username,is_read')->where(array('post_type' => $postType))->limit($limit)->exec();
    }

}