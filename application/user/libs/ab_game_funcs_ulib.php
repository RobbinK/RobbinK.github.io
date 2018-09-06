<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: ab_game_funcs_ulib.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:58
##########################################################
 */


function ab_pagination($result) {
    if (isset($result->pagination))
        echo $result->pagination;
}

function ab_pagination_total_pages($result) {
    if (isset($result->pagination_total_pages))
        return $result->pagination_total_pages;
}

function ab_pagination_set_st($i) {
    global $router_numpage;
    $router_numpage = $i;
}

function ab_pagination_get_st() {
    global $router_numpage;
    return intval($router_numpage);
}

function ab_pagination_next_st() {
    global $router_numpage;
    $router_numpage = intval($router_numpage) + 1;
    return $router_numpage;
}

function ab_pagination_prev_st() {
    global $router_numpage;
    $router_numpage = intval($router_numpage) - 1;
    return $router_numpage;
}

function ab_search_games($text, $cat = null, $item_per_page = null) {
    global $router_numpage;
    $game = mobileApp() ? new MobileGame() : new Game;
    $game->searchgames($text, $cat, null);

    if ($item_per_page) {
        $pg = new pengu_pagination;
        $pg->current_page = $router_numpage;
        $pg->items_per_page = intval($item_per_page);
        $pg->set_router('search_page', array('text' => $text));
        $pg->set_router_param('page');
        //add other Qs
        global $abQS;
        foreach ($abQS as $v)
            if (isset($_GET[$v]))
                $pg->addQsParam($v, $_GET[$v]);
        //
        $pg->merge_model($game);
    }
    $game->exec_and_get_result();
    if ($item_per_page) {
        $game->pagination_total_pages = $pg->total_pages;
        $game->pagination = $pg->render();
    }
    return $game;
}

function ab_all_games($limit = null, $cat = null, $item_per_page = null, $get_count = false,$sort='game_adddate desc,gid desc') {
    global $router_numpage;
    $game = mobileApp() ? new MobileGame() : new Game;
    if (action() == 'page_tag')
        $game->_QueryInTags = true;

    if ($get_count) {
        $game->_GetCount = true;
        $cnt = $game->getGames($limit, $cat);
        return $cnt;
    }

    $game->getGames($limit, $cat,$sort );

    if ($item_per_page) {
        $pg = new pengu_pagination;
        $pg->current_page = $router_numpage;
        $pg->items_per_page = intval($item_per_page);
        if (!empty($cat)) {
            if ($game->_QueryInTags) {
                $tag = ab_tag($cat);
                $pg->set_router('tag_page', array(
                    'tag_seo' => @$tag->seo_name,
                    'tag_id' => @$tag->id
                ));
            } else {
                $ctg = ab_category($cat);
                $pg->set_router('allgames_cat_page', array(
                    'category_seo' => @$ctg->seo_title,
                    'category_id' => @$ctg->cid
                ));
            }
        } else
            $pg->set_router('allgames_page');
        $pg->set_router_param('page');
        //add other Qs
        global $abQS;
        foreach ($abQS as $v)
            if (isset($_GET[$v]))
                $pg->addQsParam($v, $_GET[$v]);
        //
        $pg->merge_model($game);
    }
    $game->exec_and_get_result();
    if ($item_per_page) {
        $game->pagination_total_pages = $pg->total_pages;
        $game->pagination = $pg->render();
    }
    return $game;
}

function ab_related_games($limit = null, $cat = null) {
    return ab_random_games($limit, $cat);
}

function ab_new_games($limit = null, $cat = null, $item_per_page = null, $get_count = false,$sort='game_adddate desc,gid desc') {
    global $router_numpage;
    $game = mobileApp() ? new MobileGame() : new Game;
    if (action() == 'page_tag')
        $game->_QueryInTags = true;

    if ($get_count) {
        $game->_GetCount = true;
        $cnt = $game->getGames($limit, $cat);
        return $cnt;
    }
    $game->getGames($limit, $cat, $sort);

    if ($item_per_page) {
        $pg = new pengu_pagination;
        $pg->current_page = $router_numpage;
        $pg->items_per_page = intval($item_per_page);
        if (!empty($cat)) {
            $ctg = ab_category($cat);
            $pg->set_router('newgames_cat_page', array(
                'category_seo' => @$ctg->seo_title,
                'category_id' => @$ctg->cid
            ));
        } else
            $pg->set_router('newgames_page');
        $pg->set_router_param('page');
        //add other Qs
        global $abQS;
        foreach ($abQS as $v)
            if (isset($_GET[$v]))
                $pg->addQsParam($v, $_GET[$v]);
        //
        $pg->merge_model($game);
    }
    $game->exec_and_get_result();
    if ($item_per_page) {
        $game->pagination_total_pages = $pg->total_pages;
        $game->pagination = $pg->render();
    }
    return $game;
}

function ab_popular_games_today($limit = null, $cat = null, $item_per_page = null, $get_count = false,$sort= "`game_today_hits` desc, `game_adddate` DESC,`gid` desc") {
    global $router_numpage;
    $game = mobileApp() ? new MobileGame() : new Game;
    if (action() == 'page_tag')
        $game->_QueryInTags = true;

    if ($get_count) {
        $game->_GetCount = true;
        $cnt = $game->getGames($limit, $cat);
        return $cnt;
    }
    $game->_cache_time = 1800; //30min
    $game->getGames($limit, $cat,$sort);

    if ($item_per_page) {
        $pg = new pengu_pagination;
        $pg->current_page = $router_numpage;
        $pg->items_per_page = intval($item_per_page);
        if (!empty($cat)) {
            $ctg = ab_category($cat);
            $pg->set_router('populargamestoday_cat_page', array(
                'category_seo' => @$ctg->seo_title,
                'category_id' => @$ctg->cid
            ));
        } else
            $pg->set_router('populargamestoday_page');
        $pg->set_router_param('page');
        //add other Qs
        global $abQS;
        foreach ($abQS as $v)
            if (isset($_GET[$v]))
                $pg->addQsParam($v, $_GET[$v]);
        //
        $pg->merge_model($game);
    }
    $game->exec_and_get_result();
    if ($item_per_page) {
        $game->pagination_total_pages = $pg->total_pages;
        $game->pagination = $pg->render();
    }
    return $game;
}

function ab_popular_games($limit = null, $cat = null, $item_per_page = null, $get_count = false,$sort="`game_total_hits` desc, `game_adddate` DESC,`gid` desc") {
    global $router_numpage;
    $game = mobileApp() ? new MobileGame() : new Game;
    if (action() == 'page_tag')
        $game->_QueryInTags = true;

    if ($get_count) {
        $game->_GetCount = true;
        $cnt = $game->getGames($limit, $cat);
        return $cnt;
    }
    $game->getGames($limit, $cat, $sort);

    if ($item_per_page) {
        $pg = new pengu_pagination;
        $pg->current_page = $router_numpage;
        $pg->items_per_page = intval($item_per_page);
        if (!empty($cat)) {
            $ctg = ab_category($cat);
            $pg->set_router('populargames_cat_page', array(
                'category_seo' => @$ctg->seo_title,
                'category_id' => @$ctg->cid
            ));
        } else
            $pg->set_router('populargames_page');
        $pg->set_router_param('page');
        //add other Qs
        global $abQS;
        foreach ($abQS as $v)
            if (isset($_GET[$v]))
                $pg->addQsParam($v, $_GET[$v]);
        //
        $pg->merge_model($game);
    }
    $game->exec_and_get_result();
    if ($item_per_page) {
        $game->pagination_total_pages = $pg->total_pages;
        $game->pagination = $pg->render();
    }
    return $game;
}

function ab_top_rated_games($limit = null, $cat = null, $item_per_page = null, $get_count = false,$sort="`game_rating` desc, game_total_hits desc, gid desc") {
    global $router_numpage;
    $game = mobileApp() ? new MobileGame() : new Game;
    if (action() == 'page_tag')
        $game->_QueryInTags = true;

    if ($get_count) {
        $game->_GetCount = true;
        $cnt = $game->getGames($limit, $cat);
        return $cnt;
    }
    $game->getGames($limit, $cat, $sort);

    if ($item_per_page) {
        $pg = new pengu_pagination;
        $pg->current_page = $router_numpage;
        $pg->items_per_page = intval($item_per_page);
        if (!empty($cat)) {
            $ctg = ab_category($cat);
            $pg->set_router('toprategames_cat_page', array(
                'category_seo' => @$ctg->seo_title,
                'category_id' => @$ctg->cid
            ));
        } else
            $pg->set_router('toprategames_page');
        $pg->set_router_param('page');
        //add other Qs
        global $abQS;
        foreach ($abQS as $v)
            if (isset($_GET[$v]))
                $pg->addQsParam($v, $_GET[$v]);
        //
        $pg->merge_model($game);
    }
    $game->exec_and_get_result();
    if ($item_per_page) {
        $game->pagination_total_pages = $pg->total_pages;
        $game->pagination = $pg->render();
    }
    return $game;
}

function ab_featured_games($limit = 100, $cat = null, $item_per_page = null, $get_count = false) {
    global $router_numpage;
    $game = mobileApp() ? new MobileGame() : new Game;
    if (action() == 'page_tag')
        $game->_QueryInTags = true;

    if ($get_count) {
        $game->_GetCount = true;
        $cnt = $game->FeaturedGames($limit, $cat);
        return $cnt;
    }
    $game->FeaturedGames($limit, $cat);

    if ($item_per_page) {
        $pg = new pengu_pagination;
        $pg->current_page = $router_numpage;
        $pg->items_per_page = intval($item_per_page);
        $pg->set_router('featuredgames_page');
        $pg->set_router_param('page');
        //add other Qs
        global $abQS;
        foreach ($abQS as $v)
            if (isset($_GET[$v]))
                $pg->addQsParam($v, $_GET[$v]);
        //
        $pg->merge_model($game);
    }
    $game->exec_and_get_result();
    if ($item_per_page) {
        $game->pagination_total_pages = $pg->total_pages;
        $game->pagination = $pg->render();
    }
    return $game;
}

function ab_slideshow_games($limit = 10, $cat = null) {
    $cond = array('ifnull(game_show_slide,0)=1');
    $game = new Game;
    if (action() == 'page_tag')
        $game->_QueryInTags = true;

    $game->prepareCatTagCond($cond, $cat);
    $game->selectGamesByQuery(condition($cond));

    if ($limit)
        $game->limit($limit);

    $game->exec_and_get_result();
    return $game;
}

function ab_last_played_games($limit = 100, $item_per_page = null, $get_count = false) {
    global $router_numpage;
    $game = mobileApp() ? new MobileGame() : new Game;
    if ($get_count) {
        $game->_GetCount = true;
        $cnt = $game->LastPlayedGames($limit);
        return $cnt;
    }
    $game->LastPlayedGames($limit);

    if ($item_per_page) {
        $pg = new pengu_pagination;
        $pg->current_page = $router_numpage;
        $pg->items_per_page = intval($item_per_page);
        $pg->set_router('lastplayedgames_page');
        $pg->set_router_param('page');
        //add other Qs
        global $abQS;
        foreach ($abQS as $v)
            if (isset($_GET[$v]))
                $pg->addQsParam($v, $_GET[$v]);
        //
        $pg->merge_model($game);
    }
    $game->exec_and_get_result();
    if ($item_per_page) {
        $game->pagination_total_pages = $pg->total_pages;
        $game->pagination = $pg->render();
    }
    return $game;
}

function ab_user_favorite_games($limit = null, $cat = null, $item_per_page = null, $get_count = false) {
    global $router_numpage;

    $userID = Member::data('id');

    $game = mobileApp() ? new MobileGame() : new Game;
    $game->_cache_time = null;
    if (action() == 'page_tag')
        $game->_QueryInTags = true;

    if ($get_count) {
        if (!$userID)
            return 0;
        $game->_GetCount = true;
        $cnt = $game->FavoriteGames($userID, $limit, $cat);
        return $cnt;
    }
    if (!$userID) {
        return $game; //end
    }
    $game->FavoriteGames($userID, $limit, $cat);

    if ($item_per_page) {
        $pg = new pengu_pagination;
        $pg->current_page = $router_numpage;
        $pg->items_per_page = intval($item_per_page);
        $pg->set_router('userfavorites_page');
        $pg->set_router_param('page');
        //add other Qs
        global $abQS;
        foreach ($abQS as $v)
            if (isset($_GET[$v]))
                $pg->addQsParam($v, $_GET[$v]);
        //
        $pg->merge_model($game);
    }
    $game->exec_and_get_result();
    if ($item_per_page) {
        $game->pagination_total_pages = $pg->total_pages;
        $game->pagination = $pg->render();
    }
    return $game;
}

function ab_cookie_favorite_games($limit = null) {
    $game = mobileApp() ? new MobileGame() : new Game;
    $game->_cache_time = null;
    $cookieName = 'ab_' . md5('fav_games');
    if (isset($_COOKIE[$cookieName])) {
        $cookieGames = @explode(',', $_COOKIE[$cookieName]);
        $game->selectGamesByID($cookieGames, $limit);
    }
    $game->exec_and_get_result();
    return $game;
}

function ab_cookie_addto_fav($gameid) {
    $cookieName = 'ab_' . md5('fav_games');
    if (isset($_COOKIE[$cookieName]))
        $data = $_COOKIE[$cookieName];
    else
        $data = '';
    if (strpos($data . ',', ',' . $gameid . ',') === false) {
        $data.=',' . $gameid;
        setcookie($cookieName, $data, time() + 200 * 24 * 3600, '/');
        return 1;
    }
    if (strpos($data . ',', ',' . $gameid . ',') !== false) {
        $data = str_replace(',' . $gameid, null, $data);
        setcookie($cookieName, $data, time() + 200 * 24 * 3600, '/');
        return -1;
    }
    return 0;
}

function ab_random_games($limit = 50, $cat = null) {
    global $router_numpage;
    $game = mobileApp() ? new MobileGame() : new Game;
    $game->_cache_time = null;
    if (action() == 'page_tag')
        $game->_QueryInTags = true;

    $game->getGames($limit, $cat, "rand()");
    $game->exec_and_get_result();
    return $game;
}

function ab_select_game($game_seo_title) {
    static $game;
    if (isset($game[$game_seo_title]))
        return $game[$game_seo_title];
    $game[$game_seo_title] = mobileApp() ? new MobileGame() : new Game;
    $game[$game_seo_title]->selectgameBySeo($game_seo_title);
    $game[$game_seo_title]->exec_and_get_result();
    return $game[$game_seo_title];
}

function ab_select_game_byid($game_id) {
    static $game;
    if (isset($game[$game_id]))
        return $game[$game_id];
    $game[$game_id] = mobileApp() ? new MobileGame() : new Game;
    $game[$game_id]->selectgameById($game_id);
    $game[$game_id]->exec_and_get_result();
    return $game[$game_id];
}

function ab_is_myfavourite() {
    global $current_game; // gameId
    $cookieName = 'ab_' . md5('fav_games');
    if (isset($_COOKIE[$cookieName]) && isset($current_game->id)) {
        if (strpos($_COOKIE[$cookieName] . ',', ',' . $current_game->id . ',') !== false)
            return true;
    }
    $userId = Member::data('id');
    if (intval($userId)) {
        $model = new Favorite;
        if (isset($current_game->id))
            return $model->in_myfave($userId, $current_game->id);
    }
    return false;
}
