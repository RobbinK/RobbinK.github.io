<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: MobileGame.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:57
##########################################################
 */


class MobileGame extends Game {

    protected $_table = "abs_games_mobile";
    public $_cat_table = "abs_categories";
    private static $instance;
    protected $GameField = array(
        'gid' => 'id',
        'game_name' => 'name',
        'game_description' => 'description',
        'game_tags' => 'tags',
        'game_instruction' => 'instruction',
        'game_img' => 'img',
        'game_android_link' => 'android_link',
        'game_ios_link' => 'ios_link',
        'game_html5_link' => 'html5_link',
        'game_is_featured' => 'featured',
        'featured_img' => 'featured_img',
        'game_image_source' => 'image_source',
        'game_total_hits' => 'total_hits',
        'game_today_hits' => 'today_hits',
        'game_last_hit' => 'last_hit',
        'round(game_rating)' => 'rate',
        'game_adddate' => 'add_date',
        'game_upddate' => 'upd_date',
        'abs_games_mobile.seo_title' => 'seotitle',
        'abs_categories.title' => 'category_title',
        'abs_categories.seo_title' => 'category_seotitle',
        'game_categories' => 'categories_id',
        'group_concat(abs_categories.title)' => 'categories_title'
    );

    public function where($array, $opration = 'and', $replace = true, $safe = true) {
        $cond = array(condition($array, $opration));
        if (function_exists('mobileApp') && mobileApp()) {
            $s = 'LENGTH(game_html5_link)>10';
            if (is_android())
                $s.= ' or  LENGTH(game_android_link)>10';
            elseif (is_ios())
                $s.= ' or LENGTH(game_ios_link)>10';
            $cond[] = '(' . $s . ')';
        }
        return parent::where($cond, 'and', $replace, $safe);
    }

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

}