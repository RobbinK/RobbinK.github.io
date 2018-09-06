<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: ab_defined_seo_ulib.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:58
##########################################################
 */


function __defined_seo_call($type, $exactName = null) {
    static $activeAction;
    if (!$activeAction)
        $activeAction = action();
    $map = array(
        'page_index' => 'seo_homepage',
        'page_all' => 'seo_category_page',
        'page_tag' => 'seo_tag_page',
        'page_top_rate_games' => 'seo_top_rated_games_page',
        'page_new_games' => 'seo_new_games_page',
        'page_popular_games' => 'seo_popular_games_page',
        'page_popular_games_today' => 'seo_popular_games_page',
        'page_pre' => 'seo_pre-play_page',
        'page_play' => 'seo_play_page',
        'page_search' => 'seo_search_page',
        'users_submission' => array(
            'heading' => 'Submit your games',
            'title' => 'Submit your games',
            'keywords' => '[seo_homepage_keywords]',
            'description' => '[seo_homepage_description]'
        ),
        'page404' => array(
            'heading' => 'Not found',
            'title' => 'Not found',
            'keywords' => '[seo_homepage_keywords]',
            'description' => '[seo_homepage_description]'
        ),
        'contact' => array(
            'heading' => 'Contact us',
            'title' => 'Contact us',
            'keywords' => '[seo_homepage_keywords]',
            'description' => '[seo_homepage_description]'
        ), 'links' => array(
            'heading' => 'Partners Links',
            'title' => 'Partners Links',
            'keywords' => '[seo_homepage_keywords]',
            'description' => '[seo_homepage_description]'
        ),
        'page_maintenance' => array(
            'heading' => 'Not available',
            'title' => 'Not available',
            'keywords' => '[seo_homepage_keywords]',
            'description' => '[seo_homepage_description]'
        ),
        'dashboard' => array(
            'heading' => 'Members Dashboard',
            'title' => 'Members Dashboard',
            'keywords' => '[seo_homepage_keywords]',
            'description' => '[seo_homepage_description]'
        ),
        'page_favorites' => array(
            'heading' => 'My Favorite Games',
            'title' => 'My Favorite Games',
            'keywords' => '[seo_homepage_keywords]',
            'description' => '[seo_homepage_description]'
        ),
        'forget' => array(
            'heading' => 'Password Recovery',
            'title' => 'Password Recovery',
            'keywords' => '[seo_homepage_keywords]',
            'description' => '[seo_homepage_description]'
        ),
        'login' => array(
            'heading' => 'Members Login',
            'title' => 'Members Login',
            'keywords' => '[seo_homepage_keywords]',
            'description' => '[seo_homepage_description]'
        ),
        'signup' => array(
            'heading' => 'Signup',
            'title' => 'Signup',
            'keywords' => '[seo_homepage_keywords]',
            'description' => '[seo_homepage_description]'
        ),
        'profile' => array(
            'heading' => 'Member Profile',
            'title' => 'Member Profile',
            'keywords' => '[seo_homepage_keywords]',
            'description' => '[seo_homepage_description]'
        ),
    );
    if ($exactName !== null) {
        $subject = setting::get_data($exactName, 'val');
    } elseif (isset($map[$activeAction])) {
        if (is_array($map[$activeAction])) {
            $data = $map[$activeAction][$type];
            if (!function_exists('_searchsx')) {

                function _searchsx($v) {
                    return setting::get_data($v[1], 'val');
                }

            }
            $subject = preg_replace_callback('/\[([^\[\]]+)\]/i', '_searchsx', $data);
        } else {
            $subject = setting::get_data($map[$activeAction] . '_' . $type, 'val');
        }
    }
    if (@empty($subject))
        return null;

    if (!function_exists('_seoread')) {

        function _seoread($find) {
            switch (strtolower($find[1])) {
                case 'site_name': return setting::get_data('site_name', 'val');
                    break;
                case 'game_name':
                case 'game_title':
                    global $current_game;
                    return isset($current_game) ? $current_game->name : null;
                    break;
                case 'game_description' :
                case 'game_desc' :
                    global $current_game;
                    $UseMetaField = false;
                    if (ab_get_setting('meta_description_source') == 'new')
                        $UseMetaField = true;
                    //custom user settings
                    if (route_name() == 'pregame' && defined('PrePageMetaDescription')) {
                        switch (PrePageMetaDescription) {
                            case 'meta-desc-field' :$UseMetaField = true;break;
                            case 'desc-field' :$UseMetaField = false;break;
                        }
                    }
                    if (route_name() == 'playgame' && defined('PlayPageMetaDescription')) {
                        switch (PlayPageMetaDescription) {
                            case 'meta-desc-field' :$UseMetaField = true;break;
                            case 'desc-field' :$UseMetaField = false;break;
                        }
                    }
                    //---
                    if ($UseMetaField)
                        $description = isset($current_game->meta_description) ? str_replace(array('\'', '"'), '', strip_tags($current_game->meta_description)) : null;
                    else
                        $description = isset($current_game->description) ? str_replace(array('\'', '"'), '', strip_tags($current_game->description)) : null;
                    $limit = 175;
                    if ($meta_description_length = intval(ab_get_setting('meta_description_length')))
                        $limit = $meta_description_length;
                    return str::summarize($description, $limit, false, ' ', null);
                    break;
                case 'game_tags' :
                case 'game_keywords' :
                    global $current_game;
                    return isset($current_game) ? str_replace(array('\'', '"'), '', strip_tags($current_game->keywords)) : null;
                    break;
                case 'tag_name':
                case 'tag_title':
                case 'tag':
                    global $current_category;
                    return isset($current_category->name) ? $current_category->name : null;
                    break;
                case 'category_name':
                case 'category_title':
                case 'category':
                    global $current_category;
                    return isset($current_category->title) ? $current_category->title : null;
                    break;
                case 'category_description':
                case 'category_desc':
                    global $current_category;
                    return isset($current_category->meta_description) ? str_replace(array('\'', '"'), '', strip_tags($current_category->meta_description)) : null;
                    break;
                case 'category_keywords':
                case 'category_tags':
                    global $current_category;
                    return isset($current_category->meta_keywords) ? str_replace(array('\'', '"'), '', strip_tags($current_category->meta_keywords)) : null;
                    break;
                case 'categories_name':
                case 'categories_title':
                case 'categories':
                    global $current_game;
                    return isset($current_game) ? $current_game->categories_title : null;
                    break;
                case 'page_number':
                case 'page':
                    global $router_numpage;
                    return isset($router_numpage) ? $router_numpage : null;
                    break;
                case 'search_text' :
                case 'search' :
                    global $search_title;
                    return isset($search_title) ? $search_title : null;
                    break;
            }
            return null;
        }

    }

    $out = preg_replace_callback('/\{([^\{\}]+)\}/', '_seoread', $subject);
    $out = ucfirst($out);
    return $out;
}
