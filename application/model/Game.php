<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: Game.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:57
##########################################################
 */


class Game extends Model {

    protected $_table = "abs_games";
    public $_cache_time = CacheExpireTime;
    public $_GetCount = false;
    public $_QueryInTags = false;
    public $_cat_table = "abs_categories";
    protected $result_games = array();
    private static $instance;
    protected $GameField = array(
        'gid' => 'id',
        'game_name' => 'name',
        'game_description' => 'description',
        'game_meta_description' => 'meta_description',
        'game_tags' => 'tags',
        'game_keywords' => 'keywords',
        'ribbon_type' => 'ribbon',
        'ribbon_expiration' => 'ribbon_ex',
        'game_instruction' => 'instruction',
        'game_controls' => 'controls',
        'game_image_source' => 'image_source',
        'game_file_source' => 'file_source',
        'game_img' => 'img',
        'featured_img' => 'featured_img',
        'game_slide_image' => 'slideshow_img',
        'game_file' => 'file',
        'game_url_parameters' => 'parameters',
        'game_height' => 'height',
        'game_width' => 'width',
        'game_is_featured' => 'featured',
        'game_total_hits' => 'total_hits',
        'game_today_hits' => 'today_hits',
        'game_last_hit' => 'last_hit',
        'round(game_rating)' => 'rate',
        'game_votes' => 'votes',
        'game_adddate' => 'add_date',
        'game_upddate' => 'upd_date',
        'abs_games.seo_title' => 'seotitle',
        'game_categories' => 'categories_id',
        'abs_categories.title' => 'category_title',
        'abs_categories.cid' => 'category_id',
        'abs_categories.seo_title' => 'category_seotitle',
        'group_concat(abs_categories.title)' => 'categories_title',
        'abs_categories.meta_description' => 'category_description',
        'abs_categories.meta_keywords' => 'category_keywords'
    );

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

    ###########################################
    ##<<<<<<<<<<<<< Other Function ############

    private static function createInstance() {
        if (!isset(self::$instance)) {
            $classname = get_class();
            self::$instance = new $classname;
        }
    }

    static function check_duplicate($gamename, $gid = null) {
        self::createInstance();
        $cond = array('seo_title' => convert::seoText($gamename));
        if ($gid > 0)
            $cond[] = "gid<>{$gid}";
        if (self::$instance->where($cond)->getcount() > 0)
            return true;
        return false;
    }

    static function check_InstalledFromFeed($feedId = null) {
        self::createInstance();
        $cond = array('game_source_id' => intval($feedId));
        if (self::$instance->where($cond)->getcount() > 0)
            return true;
        return false;
    }

    public function have_games() {
        if (is_array($this->result_games) && current($this->result_games))
            return true;
        else
            return false;
    }

    public function the_game() {
        if (!is_array($this->result_games) || empty($this->result_games))
            return false;
        $current = current($this->result_games);
        next($this->result_games);
        return $current;
    }

    public function exec_and_get_result() {
        $this->result_games = array();
        $result = $this->exec();
        $data = array();
        if (!$result)
            return false;
        else {
            while ($result->fetch())
                $data[] = $result->current();
        }
        if (count($data) > 0) {
            $this->result_games = $data;
            return true;
        }
        return false;
    }

    private function GetSelectedField() {
        $fields = null;
        foreach ($this->GameField as $kField => $vField)
            $fields[] = $kField . ' as ' . $vField;
        $res = join(',', $fields);
        if (empty($res))
            return "*";
        else
            return $res;
    }

    ############################################

    public function gamehits($gameSeoTitle) {
        $changes = array(
            'game_last_hit' => time(),
            'game_today_hits= ifnull(game_today_hits,0)+1',
            'game_total_hits= ifnull(game_total_hits,0)+1',
        );
        $this->update($changes)->where(array('seo_title' => $gameSeoTitle));
        if ($this->exec())
            return true;
        return false;
    }

    public function showrate($gameid) {
        $data = $this->select("game_rating,game_votes")->where(array('gid' => $gameid))->exec();
        if ($data->numrows())
            return round($data->current()->game_rating, 2);
    }

    public function selectGamesByQuery($sql) {
        $this->result_games = array();
        if (!preg_match("#select\s(.*?\s*)\sfrom#i", $sql)) {
            $fields = $this->GetSelectedField();
            $this->select($fields)
                    ->innerjoin($this->_cat_table)
                    ->on("concat(',',{$this->_table}.game_categories,',') like concat('%,',{$this->_cat_table}.cid,',%')")
                    ->groupby("gid")
                    ->where(array($sql, 'ifnull(game_is_active,0)=1', "{$this->_cat_table}.is_active=1"));
        } else {
            $sql = preg_replace("/select\s+\*/i", 'select ' . $this->GetSelectedField(), $sql);
            $this->query($sql);
        }

        if ($this->_GetCount)
            return $this->getcount();
    }

    public function showGameByQuery($sql) {
        return $this->selectGamesByQuery($sql);
    }

    public function selectGamesByID($games, $limit) {
        global $numpage;
        if (empty($games))
            return false;
        $filteredGames = array();
        if (is_array($games)) {
            foreach ($games as $gid)
                if (intval($gid))
                    $filteredGames[] = intval($gid);
        }
        elseif (is_string($games)) {
            $games = explode(',', $games);
            foreach ($games as $gid)
                if (intval($gid))
                    $filteredGames[] = intval($gid);
        } else
            return false;
        if (!count($filteredGames))
            return false;

        $fields = $this->GetSelectedField();
        $this->select($fields)
                ->innerjoin($this->_cat_table)
                ->on("concat(',',{$this->_table}.game_categories,',') like concat('%,',{$this->_cat_table}.cid,',%')")
                ->groupby("gid")
                ->where(array("gid in(" . join(',', $filteredGames) . ")", 'ifnull(game_is_active,0)=1', "{$this->_cat_table}.is_active=1"));
        if ($limit !== null)
            $this->limit($limit);
        return $this;
    }

    public function selectgameById($id) {
        return $this->selectgame(null, $id);
    }

    public function selectgameBySeo($seoName) {
        return $this->selectgame($seoName, null);
    }

    private function selectgame($gameSeoName = null, $gameId = null) {
        $this->result_games = array();
        $cond = array('ifnull(game_is_active,0)=1');

        if (is_numeric($gameId))
            $cond[$this->_table . '.gid'] = $gameId;
        else if (is_string($gameSeoName))
            $cond[$this->_table . '.seo_title'] = $gameSeoName;

        $fields = $this->GetSelectedField();
        $this->select($fields)
                ->innerjoin($this->_cat_table)
                ->on("concat(',',{$this->_table}.game_categories,',') like concat('%,',{$this->_cat_table}.cid,',%')")
                ->groupby("gid")
                ->where($cond);


        if (UseCache && intval($this->_cache_time))
            $this->cacheable($this->_cache_time, 'games');
        return $this;
    }

    public function searchgames($textSearch = null, $category = null, $orderBy = 'game_adddate desc, gid desc') {
        global $numpage;
        $searchCond = array('ifnull(game_is_active,0)=1', "{$this->_cat_table}.is_active=1");
        $this->result_games = array();


        if (isset($textSearch)) {
            foreach (explode('_', $textSearch) as $text)
                $searchCond[] = "({$this->_table}.`seo_title` like '%{$text}%' or `game_name` like '%{$text}%' or `game_description` like '%{$text}%')";
        }

        if (is_numeric($category))
            $searchCond[$this->_cat_table . '.cid'] = $category;
        else if (is_string($category))
            $searchCond[$this->_cat_table . '.seo_title'] = $category;

        $fields = $this->GetSelectedField();
        $OrderBy = (array_search($orderBy, $this->GameField) ? array_search($orderBy, $this->GameField) : $orderBy);
        $this->select($fields)
                ->innerjoin($this->_cat_table)
                ->on("concat(',',{$this->_table}.game_categories,',') like concat('%,',{$this->_cat_table}.cid,',%')")
                ->groupby("gid")
                ->orderby($OrderBy)
                ->where($searchCond, 'and');

        if (UseCache && intval($this->_cache_time))
            $this->cacheable($this->_cache_time, 'games');
        return $this;
    }

    public function prepareCatTagCond(&$cond, $category) {
        if ($this->_QueryInTags) {
            $tag_id = $category;
            if (is_numeric($tag_id))
                $cond[] = "concat (',',{$this->_table}.game_tags,',') like '%,{$tag_id},%'";
            else if (is_string($tag_id)) {
                $tag_id = Game_tag::getTagIdBySeo($category);
                if (!$tag_id)
                    $tag_id = 'null';
                $cond[] = "concat (',',{$this->_table}.game_tags,',') like '%,{$tag_id},%'";
            }
        } else {
            if (is_numeric($category))
                $cond[$this->_cat_table . '.cid'] = $category;
            else if (is_string($category))
                $cond[$this->_cat_table . '.seo_title'] = $category;
        }
    }

    public function getGames($limit = null, $category = null, $orderBy = null) {

        $cond = array('ifnull(game_is_active,0)=1', "{$this->_cat_table}.is_active=1");
        $this->prepareCatTagCond($cond, $category);

        $OrderBy = (array_search($orderBy, $this->GameField) ? array_search($orderBy, $this->GameField) : $orderBy);
        $fields = $this->GetSelectedField();
        $this->select($fields)
                ->innerjoin($this->_cat_table)
                ->on("concat(',',{$this->_table}.game_categories,',') like concat('%,',{$this->_cat_table}.cid,',%')")
                ->groupby("gid")
                ->orderby($OrderBy)
                ->where($cond, 'and');


        if (UseCache && intval($this->_cache_time)) {
            $this->cacheable($this->_cache_time, 'games');
        }

        if ($this->_GetCount)
            return $this->getcount();

        if ($limit !== null)
            $this->limit($limit);
        return $this;
    }

    public function LastPlayedGames($limit = null, $category = null) {
        $cookieName = 'ab_' . md5((isset($this->LastPlayedCookieName) ? $this->LastPlayedCookieName : 'last_played'));
        if (isset($_COOKIE[$cookieName])) {
            $lastPlayGames = @explode(',', $_COOKIE[$cookieName]);
            $res = $this->selectGamesByID($lastPlayGames, $limit);
            if ($this->_GetCount)
                return $this->getcount();
            return $res;
        }
        return false;
    }

    public function FeaturedGames($limit, $category = null, $orderBy = '`game_total_hits` desc, `game_adddate` DESC,`gid` desc') {
        $cond = array('ifnull(game_is_active,0)=1', "{$this->_cat_table}.is_active=1");
        $this->prepareCatTagCond($cond, $category);
        $cond['game_is_featured'] = 1;
        $fields = $this->GetSelectedField();
        $this->select($fields)
                ->innerjoin($this->_cat_table)
                ->on("concat(',',{$this->_table}.game_categories,',') like concat('%,',{$this->_cat_table}.cid,',%')")
                ->groupby("gid")
                ->where($cond, 'and');

        if (UseCache && intval($this->_cache_time)) {
            $this->cacheable($this->_cache_time, 'games');
        }

        if ($this->_GetCount)
            return $this->getcount();

        if ($limit !== null)
            $this->limit($limit);
        return $this;
    }

    function FavoriteGames($userID, $limit, $category = null, $orderBy = 'game_adddate desc, gid desc') {

        $cond = array('ifnull(game_is_active,0)=1');
        $this->prepareCatTagCond($cond, $category);

        $fv = new Favorite();
        $games = $fv->getFavouritesGameId($userID);
        if (empty($games))
            return false;
        $this->selectGamesByID($games, $limit);

        $OrderBy = (array_search($orderBy, $this->GameField) ? array_search($orderBy, $this->GameField) : $orderBy);
        $this->orderby($OrderBy);
        $this->where($cond, 'and', false);


        if ($this->_GetCount)
            return $this->getcount();
        return $this;
    }

}
