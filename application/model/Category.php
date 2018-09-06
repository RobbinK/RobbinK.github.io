<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: Category.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:57
##########################################################
 */


class Category extends Model {

    protected $_table = "abs_categories";
    public $_cache_time = CacheExpireTime;
    private static $instance;
    protected $rescat;

    function __construct() {
        parent::__construct();
        $this->alias('C');
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

    public function have_categories() {
        if (is_array($this->rescat) && current($this->rescat))
            return true;
        else
            return false;
    }

    public function the_category() {
        if (!is_array($this->rescat) || empty($this->rescat))
            return false;
        $current = current($this->rescat);
        next($this->rescat);
        return $current;
    }

    public function SelectCategory($seotitle) {
        $this->select()->where(array('C.seo_title' => $seotitle));

        if (UseCache && intval($this->_cache_time))
            $this->cacheable($this->_cache_time, 'categories');

        $this->rescat = @$this->exec()->row(0);
        return $this->rescat;
    }

    public function AllCategories($limit = null, $extended = true) {
        if ($extended) {
            $this->query("SET SQL_BIG_SELECTS=1;")->exec();
            $this->select("C.cid,C.cid as id,C.title,C.seo_title,C.icon,C.featured,count(*) as num_games")
                    ->leftjoin("abs_games", "G")
                    ->on("concat(',',G.game_categories,',') like concat('%,',C.cid,',%')")
                    ->where(array(
                        'C.is_active' => 1,
                        'ifnull(G.game_is_active,0)=1',
                    ))->groupby("C.cid");
        } else
            $this->select("C.cid,C.cid as id,C.title,C.seo_title,C.icon")->where(array('C.is_active' => 1));

        if (UseCache && intval($this->_cache_time))
            $this->cacheable($this->_cache_time, 'categories');

        if ($limit !== null)
            $this->limit($limit);

        $this->rescat = $this->exec()->allrows();
        return $this->rescat;
    }

    public function FeaturedCategories($limit = null, $extended = true) {
        if ($extended) {
            $this->query("SET SQL_BIG_SELECTS=1;")->exec();
            $this->select("C.cid,C.cid as id,C.title,C.seo_title,C.icon,C.featured,count(*) as num_games")
                    ->leftjoin("abs_games", "G")
                    ->on("concat(',',G.game_categories,',') like concat('%,',C.cid,',%')")
                    ->where(array(
                        'C.is_active' => 1,
                        'C.featured' => 1,
                        'ifnull(G.game_is_active,0)=1',
                    ))->groupby("C.cid");
        } else
            $this->select("C.cid,C.cid as id,C.title,C.seo_title,C.icon")->where(array('C.is_active' => 1, 'C.featured' => 1));

        if (UseCache && intval($this->_cache_time))
            $this->cacheable($this->_cache_time, 'categories');

        if ($limit !== null)
            $this->limit($limit);

        $this->rescat = $this->exec()->allrows();
        return $this->rescat;
    }

    public function nonFeaturedCategories($limit = null, $extended = true) {
        if ($extended) {
            $this->query("SET SQL_BIG_SELECTS=1;")->exec();
            $this->select("C.cid,C.cid as id,C.title,C.seo_title,C.icon,C.featured,count(*) as num_games")
                    ->leftjoin("abs_games", "G")
                    ->on("concat(',',G.game_categories,',') like concat('%,',C.cid,',%')")
                    ->where(array(
                        'C.is_active' => 1,
                        'ifnull(C.featured,0)=0',
                        'ifnull(G.game_is_active,0)=1',
                    ))->groupby("C.cid");
        } else
            $this->select("C.cid,C.cid as id,C.title,C.seo_title,C.icon")->where(array('C.is_active' => 1, 'ifnull(C.featured,0)=0'));

        if (UseCache && intval($this->_cache_time))
            $this->cacheable($this->_cache_time, 'categories');

        if ($limit !== null)
            $this->limit($limit);


        $this->rescat = $this->exec()->allrows();
        return $this->rescat;
    }

    static function getCategorySeoById($cat_id) {
        static $res;
        if (isset($res[$cat_id]))
            return $res[$cat_id];
        self::createInstance();
        $data = self::$instance->select('C.seo_title')->where(array('C.cid' => $cat_id))->exec();
        if (isset($data->current['seo_title']))
            return $res[$cat_id] = $data->current['seo_title'];
    }

    static function getCategoryTitleBySeo($seoName) {
        static $res;
        if (isset($res[$seoName]))
            return $res[$seoName];
        self::createInstance();
        $data = self::$instance->select('C.title')->where(array('C.seo_title' => $seoName))->exec();
        if (isset($data->current['title']))
            return $res[$seoName] = $data->current['title'];
    }

    static function getCategoryIDBySeo($seoName) {
        static $res;
        if (isset($res[$seoName]))
            return $res[$seoName];
        self::createInstance();
        $data = self::$instance->select('C.cid')->where(array('C.seo_title' => $seoName))->exec();
        if (isset($data->current['cid']))
            return $res[$seoName] = $data->current['cid'];
    }

    static function getCategoriesTitle($cats_id) {
        if (is_array($cats_id)) {
            self::createInstance();
            $data = self::$instance->select('C.title')->where("C.cid in (" . join(',', $cats_id) . ")")->exec();
            $out = array();
            while ($data->fetch()) {
                $out[] = $data->current()->title;
            }
            return $out;
        } else
        if (is_string($cats_id)) {
            preg_match_all('/[^\d]/i', $cats_id, $sepMatches);
            preg_match_all('/\d+/i', $cats_id, $idsMatches);
            if (empty($idsMatches[0]))
                return null;
            self::createInstance();
            $data = self::$instance->select('C.title')->where("C.cid in (" . join(',', $idsMatches[0]) . ")")->exec();
            $out = '';
            $i = 0;
            if ($data->numrows())
                while ($data->fetch()) {
                    $out.= $data->current()->title . (isset($sepMatches[0][$i]) ? $sepMatches[0][$i] : null);
                    $i++;
                }
            return $out;
        }
    }

    static function getCatsIdByTags($genres, $outarray = false) {
        $genres = @explode(',', $genres);
        if (is_array($genres) && !empty($genres)) {
            self::createInstance();
            $cond = array();
            foreach ($genres as $k => $v)
                $cond[] = "concat(',',C.feed_tag_matching,',') like '%," . $v . ",%'";

            $data = self::$instance->select('group_concat(C.cid) as ids')->where($cond, 'or')->exec()->current;
            if (!empty($data['ids'])) {
                if ($outarray)
                    $data['ids'] = explode(',', $data['ids']);
                return $data['ids'];
            }
        }
    }

}
