<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: Link.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:57
##########################################################
 */


class Link extends Model {

    protected $_table = 'abs_links';
    public $_cache_time = CacheExpireTime;
    private static $instance;
    private $rescat;

    function __construct() {
        parent::__construct();
    }

    function exec(array $params = null) {
        $data = parent::exec($params);
        if ($data)
            return $data;
        elseif (parent::errorno()) {
            _show_mysql_error(parent::lastsql(), parent::lasterror());
        }
    }

    private static function createInstance() {
        if (!isset(self::$instance)) {
            $classname = get_class();
            self::$instance = new $classname;
        }
    }

    public function have_link() {
        if (is_array($this->rescat) && current($this->rescat))
            return true;
        else
            return false;
    }

    public function the_link() {
        if (!is_array($this->rescat) || empty($this->rescat))
            return false;
        $current = current($this->rescat);
        next($this->rescat);
        return $current;
    }

    public function allLinks($limit = null, $link_type = null, $pos = null) {
        $cond = array("(ifnull(expire_date,'')='' or expire_date>='" . date('Y-m-d') . "')", 'status' => 1);
        if (is_numeric($link_type))
            $cond['link_type'] = $link_type;
        if (!empty($pos))
            $cond[] = "`position` in ($pos)";
        $this->select("partner_title as title,partner_url as url")->where($cond)->orderby('priority,insert_time desc');

        if (UseCache && intval($this->_cache_time))
            $this->cacheable($this->_cache_time, 'links');

        if ($limit !== null)
            $this->limit($limit);

        $this->rescat = $this->exec()->allrows();
        return $this->rescat;
    }

}
