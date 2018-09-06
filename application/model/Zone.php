<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: Zone.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:57
##########################################################
 */


class Zone extends Model {

    protected $_table = "abs_zones";
    public $_cache_time = CacheExpireTime;
    private static $instance;

    private static function createInstance() {
        if (!self::$instance) {
            $className = get_class();
            self::$instance = new $className;
        }
    }

    public static function getZone($zone_name) {
        self::createInstance();
        $data = self::$instance->select('id,type,adsize,show_ad')->where("lower(zone_name)='" . input::safe($zone_name) . "'")->exec()->allrows();
        if (!empty($data))
            return $data[0];
        else
            return false;
    }

    ////// OLD CODE
    public $id;
    private $ads;

    public function generate() {
        if (func_num_args() == 0 || !ShowAds)
            return;
        $params = func_get_args();
        $adsName = $params[0];

        if ((!isset($params[1]) || !is_numeric($params[1])) && agent::remote_info_tier())
            $tier = agent::remote_info_tier();
        else if (isset($params[1]))
            $tier = $params[1];
        $this->id = 'ads_' . $adsName;
        //==
        $s = new pengu_cache(null,'ads_');
        $s->setCacheKey('allads'); 
        if (!$s->isCached()) {
            $data = $this->select("adcode{$tier}")->where(array("adname" => $adsName))->exec()->allrows(); 
            $s->write($data);
        }
        else
            $data = $s->read();
        //==
        $ad = $data["adcode{$tier}"];
        $this->ads = input::htmlunsafe($ad);
    }

    public function create() {
        if ($this->ads)
            return "<div id='{$this->id}'>" . htmlspecialchars_decode($this->ads) . '</div>';
    }

}