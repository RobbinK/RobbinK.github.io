<?php

/* myarcadeplugin */

function custom_paths()
{
    $base_path = import_get_session('base_path');
    $base_path = rtrim($base_path, '/');
    return array(
        'thumbs' => null, // $base_path . '/images',
        'files' => null, //$base_path . '/swfs',
        'avatars' => null, // $base_path . '/avatars',
    );
}

function custom_tables($link)
{
    // -- getting  tables name
    $wp_terms_table = $link->findTable('%_terms');
    $wp_term_taxonomy_table = $link->findTable('%_term_taxonomy');

    $categories = null;
    if ($wp_terms_table && $wp_term_taxonomy_table)
        $categories = $link->settable($wp_terms_table, 'T')
            ->select("T.term_id as catid,T. NAME as name,T.slug,TX.description as description")
            ->innerjoin($wp_term_taxonomy_table, 'TX')->on('TX.term_id = T.term_id')
            ->where("TX.taxonomy = 'category'")
            ->exec();

    return array(
        'abs_games' => $link->findTable('%_myarcadegames'),
        'abs_categories' => $categories,
        'abs_members' => $link->findTable('%_users'),
        'abs_links' => $link->findTable('%_links'),
        'abs_games_tags' => null
    );
}

/* =============== Data =============== */

function custom_conversion_ctgs($mydb, $data, $slink = null)
{
    if (!is_array($data) || empty($data))
        return false;

    return array(
        'cid' => @$data['catid'],
        'title' => @$data['name'],
        'seo_title' => @$data['slug'],
        'meta_description' => @$data['description'],
        'meta_keywords' => null,
        'featured' => 1,
        'is_active' => 1,
    );
}

function custom_conversion_games($mydb, $data, $slink = null)
{
    if (!is_array($data) || empty($data))
        return false;

    static $wp_term_relationships_table;
    if (!isset($wp_term_relationships_table)) {
        $wp_term_relationships_table = $slink->findTable('%_term_relationships');
    }

    if(isset($data['categories']))
        $categories=fjoin(',', cats_to_ids($mydb, @$data['categories']));
    elseif ($wp_term_relationships_table) {
        //--get data from wp_term_relationships
        $dcats = $slink->query("select GROUP_CONCAT(term_taxonomy_id) as cats from {$wp_term_relationships_table} where object_id='{$data['id']}'")->exec();
         if ($dcats && $dcats->found())
            $categories = $dcats->current()->cats;
    }



    return array(
        'gid' => @$data['id'],
        'game_name' => @stripslashes($data['name']),
        'seo_title' => !empty($data['slug']) ? $data['slug'] : convert::seoText($data['name']),
        'game_categories' => @$categories,
        'game_description' => @stripslashes($data['description']),
        'game_instruction' => @stripslashes($data['instructions']),
        'game_controls' => @stripslashes($data['controls']),
        'game_tags' => fjoin(',', tags_to_ids($mydb, $data['tags'])),
        'game_img' => @$data['thumbnail_url'],
        'featured_img' => null,
        'game_file' => @stripslashes($data['swf_url']),
        'game_width' => intval(@$data['width']),
        'game_height' => intval(@$data['height']),
        'game_adddate' => !is_numeric(@$data['created']) && !empty($data['created']) ? strtotime($data['created']) : $data['created'],
        'game_rating' => null,
        'game_votes' => null,
        'game_is_featured' => null,
        'game_source' => 'convert',
        'game_is_active' => ($data['status'] == 'published') ? 1 : -1,
    );
}

function custom_conversion_links($mydb, $data, $slink = null)
{
    if (!is_array($data) || empty($data))
        return false;
    return array(
        'id' => @$data['link_id'],
        'partner_title' => @$data['link_name'],
        'partner_url' => @$data['link_url'],
        'expire_date' => date("Y-m-d", strtotime('+1 years')),
        'link_type' => 1,
        'status' => @convert::to_bool($data['link_visible']) ? 1 : 0,
    );
}

function custom_conversion_members($mydb, $data, $slink = null)
{
    if (!is_array($data) || empty($data))
        return false;
    return array(
        'id' => @$data['ID'],
        'username' => @$data['user_login'],
        'password' => @$data['user_pass'],
        'email' => @$data['user_email'],
        'name' => @$data['display_name'],
        'avatar' => null,
        'group' => 2,
        'status' => 1,
    );
}

