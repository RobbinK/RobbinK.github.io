<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: Game_tag.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:57
##########################################################
 */


class Game_tag extends Model {

    protected $_table = "abs_games_tags";
    public $_cache_time = CacheExpireTime;
    private static $instance;
    private $restag;

    function __construct() {
        parent::__construct();
        $this->alias('T');
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

    public function have_Tag() {
        if (is_array($this->restag) && current($this->restag))
            return true;
        else
            return false;
    }

    public function the_Tag() {
        if (!is_array($this->restag) || empty($this->restag))
            return false;
        $current = current($this->restag);
        next($this->restag);
        return $current;
    }

    public function SelectTag($seoname) {
        $this->select()->where(array('T.seo_name' => $seoname));

        if (UseCache && intval($this->_cache_time))
            $this->cacheable($this->_cache_time);

        $this->restag = @$this->exec()->row(0);
        return $this->restag;
    }

    public function Alltags($limit = null, $extended = true) {
        $this->select("T.id,T.name,T.seo_name");
        if ($extended) {
            $this->query("SET SQL_BIG_SELECTS=1;")->exec();
            $this->select("T.*,count(*) as num_games")
                    ->leftjoin("abs_games", "G")
                    ->on("concat(',',G.game_tags,',') like concat('%,',T.id,',%')")
                    ->where(array(
                        'ifnull(G.game_is_active,0)=1',
                    ))->groupby("T.id");
        } else
            $this->select("T.id,T.name,T.seo_name");

        if (UseCache && intval($this->_cache_time))
            $this->cacheable($this->_cache_time);

        if ($limit !== null)
            $this->limit($limit);

        $this->restag = $this->exec()->allrows();
        return $this->restag;
    }

    static function getTagsByIds($tags_id) {
        self::createInstance();
        $ret = array();

        if (!empty($tags_id) && is_array($tags_id)) {
            foreach ($tags_id as &$id) {
                $id = intval($id);
            }
            $data = self::$instance->select('T.*')->where("T.id in (" . join(',', $tags_id) . ")")->exec();

            if ($data->found())
                $ret = $data->allrows();
        }
        return $ret;
    }

    static function getTagSeoById($tag_id) {
        self::createInstance();
        $data = self::$instance->select('T.seo_name')->where(array('T.id' => $tag_id))->exec();
        if (isset($data->current['seo_name']))
            return $data->current['seo_name'];
    }

    static function getTagNameBySeo($seoName) {
        self::createInstance();
        $data = self::$instance->select('T.name')->where(array('T.seo_name' => $seoName))->exec();
        if (isset($data->current['name']))
            return $data->current['name'];
    }

    static function getTagIdBySeo($seoName) {
        self::createInstance();
        $data = self::$instance->select('T.id')->where(array('T.seo_name' => $seoName))->exec();
        if (isset($data->current['id']))
            return $data->current['id'];
    }

    static function getTagsName($tags_id) {
        if (is_array($tags_id)) {
            self::createInstance();
            $data = self::$instance->select('T.name')->where("T.id in (" . join(',', $tags_id) . ")")->exec();
            $out = array();
            while ($data->fetch()) {
                $out[] = $data->current->name;
            }
            return $out;
        } else
        if (is_string($tags_id)) {
            preg_match_all('/[^\w]/i', $tags_id, $sepMatches);
            preg_match_all('/[\d]+/i', $tags_id, $idsMatches);
            if (empty($idsMatches[0]))
                return null;
            self::createInstance();
            $data = self::$instance->select('T.name')->where("T.id in (" . join(',', $idsMatches[0]) . ")")->exec();
            $out = '';
            $i = 0;
            if ($data->numrows())
                while ($data->fetch()) {
                    $out.= $data->current()->name . (isset($sepMatches[0][$i]) ? $sepMatches[0][$i] : null);
                    $i++;
                }
            return $out;
        }
    }

    static function tag_to_id($tag_name) {
        self::createInstance();
        $found = null;
        $data = self::$instance->select('id')->where(array('name' => $tag_name))->exec();
        if ($data->found()) {
            $found = $data->current['id'];
        }
        if (!$found) {
            if (self::$instance->insert(array('name' => $tag_name, 'seo_name' => convert::seoText($tag_name)))->exec()) {
                $found = self::$instance->lastinsid();
            }
        }
        return $found;
    }

    static function tags_to_ids($tags_name, $delimiter = ',') {
        $tags = explode($delimiter, $tags_name);
        $tagsid = array();
        if (is_array($tags))
            foreach ($tags as $tag) {
                $tg = trim($tag);
                if (is_numeric($tg))
                    $tagsid[] = $tg;
                elseif (!empty($tg))
                    $tagsid[] = self::tag_to_id($tg);
            }
        return $tagsid;
    }

}
