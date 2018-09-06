<?php

/* avscript */

function custom_paths() {
    $base_path = import_get_session('base_path');
    $base_path = rtrim($base_path, '/');
    return array(
        'thumbs' => $base_path . '/games/images',
        'files' => $base_path . '/games',
        'avatars' => $base_path . '/uploads/avatars',
    );
}

function custom_tables($link) {
    return array(
        'abs_games' => 'ava_games',
        'abs_categories' => 'ava_cats',
        'abs_members' => 'ava_users',
        'abs_links' => 'ava_links',
        'abs_games_tags' => 'ava_tags'
    );
}

/* =============== Data =============== */

function custom_conversion_tags($mydb, $data, $slink = null) {
    if (!is_array($data) || empty($data))
        return false;
    return array(
        'id' => @$data['id'],
        'name' => @$data['tag_name'],
        'seo_name' => convert::seoText(@$data['tag_name']),
    );
}

function custom_conversion_ctgs($mydb, $data, $slink = null) {
    if (!is_array($data) || empty($data))
        return false;
    return array(
        'cid' => @$data['id'],
        'title' => @$data['name'],
        'seo_title' => convert::seoText(@$data['name']),
        'meta_description' => @$data['description'],
        'meta_keywords' => @$data['keywords'],
        'featured' => 1,
        'is_active' => 1
    );
}

function custom_conversion_games($mydb, $data, $slink = null) {
    if (!is_array($data) || empty($data))
        return false;
    //--tags
    $dtags = $slink->settable('ava_tag_relations')
                    ->select('GROUP_CONCAT(tag_id)')
                    ->where(array('game_id' => $data['id']))->exec();
    $tags = array(); //tags_to_ids($mydb, $data['game_tags'])
    if ($dtags->found() && is_array($dtags->current)) {
        $d = $dtags->current;
        $tags = @current($d);
    }
    //--votes
    $votes = 0;
    $dvotes = $slink->settable('ava_ratings')
                    ->select('count(*) as cnt')
                    ->where(array('game_id' => $data['id']))->exec();
    if ($dvotes->found())
        $votes = intval($dvotes->current['cnt']);

    $gamefile = null;
    if (isset($data['filetype']) && $data['filetype'] == 'code')
        $gamefile = @$data['html_code'];
    else
        $gamefile = @$data['url'];

    return array(
        'gid' => @$data['id'],
        'game_name' => @$data['name'],
        'seo_title' => convert::seoText($data['name']),
        'game_categories' => @$data['category_id'],
        'game_description' => @$data['description'],
        'game_instruction' => @$data['instructions'],
        'game_tags' => $tags,
        'game_img' => @!convert::to_bool($data['featured']) ? @$data['image'] : null,
        'featured_img' => @convert::to_bool($data['featured']) ? @$data['image'] : null,
        'game_file' => $gamefile,
        'game_width' => intval(@$data['width']),
        'game_height' => intval(@$data['height']),
        'game_adddate' => !is_numeric(@$data['date_added']) && !empty($data['date_added']) ? strtotime($data['date_added']) : $data['date_added'],
        'game_rating' => @$data['rating'],
        'game_votes' => $votes,
        'game_is_featured' => @convert::to_bool($data['featured']) ? 1 : 0,
        'game_source' => 'convert',
        'game_is_active' => @convert::to_bool($data['published']) ? 1 : -1,
    );
}

function custom_conversion_links($mydb, $data, $slink = null) {
    if (!is_array($data) || empty($data))
        return false;
    return array(
        'id' => $data['id'],
        'partner_title' => @$data['description'],
        'partner_url' => @$data['url'],
        'expire_date' => date("Y-m-d", strtotime('+1 years')),
        'link_type' => 1,
        'status' => @convert::to_bool($data['status']) ? 1 : 0,
    );
}

function custom_conversion_members($mydb, $data, $slink = null) {
    if (!is_array($data) || empty($data))
        return false;
    return array(
        'id' => @$data['id'],
        'username' => @$data['username'],
        'password' => @$data['password'],
        'email' => @$data['email'],
        'name' => @$data['username'],
        'avatar' => @basename($data['avatar']),
        'group' => 2,
        'status' => @convert::to_bool($data['activate']) ? 1 : 0,
    );
}

