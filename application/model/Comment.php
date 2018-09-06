<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: Comment.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:57
##########################################################
 */


class Comment extends Model {

    protected $_table = 'abs_comment';
    private static $instance;
    public $_cache_time = CacheExpireTime;
    private $resmem;

    private static function createInstance() {
        if (!self::$instance) {
            $className = get_class();
            self::$instance = new $className;
        }
    }

    public function have_comments() {
        if (is_array($this->rescm) && current($this->rescm))
            return true;
        else
            return false;
    }

    public function the_comment() {
        if (!is_array($this->rescm) || empty($this->rescm))
            return false;
        $current = current($this->rescm);
        next($this->rescm);
        return $current;
    }

    public function Allcomments($limit = null, $type = null) {
        $cond = array('status' => 2);
        if (is_numeric($type))
            $cond['type'] = $type;
        $this->select("id,`group` as game_id,user_id,user_avatar,name,email,website,country,comment,time")->where($cond)->orderby('time desc');
        if (UseCache && intval($this->_cache_time))
            $this->cacheable(60);

        if ($limit !== null)
            $this->limit($limit);

        $this->rescm = $this->exec()->allrows();
        return $this->rescm;
    }

}