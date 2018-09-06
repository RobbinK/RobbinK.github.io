<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: MobileCategory.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:57
##########################################################
 */


class MobileCategory extends Category {

    private static $instance;

    private static function createInstance() {
        if (!isset(self::$instance)) {
            $classname = get_class();
            self::$instance = new $classname;
        }
    }

    public function select($fields = 'C.*') {
        $Mobile_Device_condition = null;
        if (function_exists('is_android'))
            $Mobile_Device_condition = is_android() ? ' and (length(G.game_android_link)>10 or length(game_html5_link)>10)' : ' and (length(G.game_ios_link)>10 or length(game_html5_link)>10)';
        return parent::select($fields)
                        ->innerjoin('abs_games_mobile', 'G')
                        ->on("concat(',',G.game_categories,',') like concat('%,',C.cid,',%')" . $Mobile_Device_condition)
                        ->groupby('C.cid');
    }
    
    
    public function AllCategories($limit = null, $extended = true) {
        if ($extended) {
            $this->select("C.cid,C.cid as id,C.title,C.seo_title,C.icon,C.featured,count(*) as num_games")
                    ->where(array(
                        'C.is_active' => 1,
                        'ifnull(G.game_is_active,0)=1',
                    ))->groupby("C.cid");
        }
        else
            $this->select("C.cid,C.cid as id,C.title,C.seo_title,C.icon")->where(array('C.is_active' => 1));

        if (UseCache && intval($this->_cache_time))
            $this->cacheable($this->_cache_time,'categories');

        if ($limit !== null)
            $this->limit($limit);

        $this->rescat = $this->exec()->allrows();
        return $this->rescat;
    }

    public function FeaturedCategories($limit = null, $extended = true) {
        if ($extended) {
            $this->select("C.cid,C.cid as id,C.title,C.seo_title,C.icon,C.featured,count(*) as num_games")
                    ->where(array(
                        'C.is_active' => 1,
                        'C.featured' => 1,
                        'ifnull(G.game_is_active,0)=1',
                    ))->groupby("C.cid");
        }
        else
            $this->select("C.cid,C.cid as id,C.title,C.seo_title,C.icon")->where(array('C.is_active' => 1, 'C.featured' => 1));

        if (UseCache && intval($this->_cache_time))
            $this->cacheable($this->_cache_time,'categories');

        if ($limit !== null)
            $this->limit($limit);

        $this->rescat = $this->exec()->allrows();
        return $this->rescat;
    }

    public function nonFeaturedCategories($limit = null, $extended = true) {
        if ($extended) {
            $this->select("C.cid,C.cid as id,C.title,C.seo_title,C.icon,C.featured,count(*) as num_games")
                    ->where(array(
                        'C.is_active' => 1,
                        'ifnull(C.featured,0)=0',
                        'ifnull(G.game_is_active,0)=1',
                    ))->groupby("C.cid");
        }
        else
            $this->select("C.cid,C.cid as id,C.title,C.seo_title,C.icon")->where(array('C.is_active' => 1, 'ifnull(C.featured,0)=0'));

        if (UseCache && intval($this->_cache_time))
            $this->cacheable($this->_cache_time,'categories');

        if ($limit !== null)
            $this->limit($limit);


        $this->rescat = $this->exec()->allrows();
        return $this->rescat;
    }

}