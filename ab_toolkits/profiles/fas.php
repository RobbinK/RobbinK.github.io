<?php

/* yas */

function custom_paths() {
    $base_path = import_get_session('base_path');
    $base_path = rtrim($base_path, '/');
    return array(
        'thumbs' => $base_path . '/games/thumbs',
        'files' => $base_path . '/games',
        'avatars' => $base_path . '/avatars',
    );
}

function custom_tables($link) {
    return array(
        'abs_games' => 'dd_games',
        'abs_categories' => 'dd_categories',
        'abs_members' => 'dd_users',
        'abs_links' => 'dd_links',
        'abs_games_tags' => null
    );
}

/* =============== Data =============== */

function custom_conversion_ctgs($mydb, $data, $slink = null) {
    if (!is_array($data) || empty($data))
        return false;
    return array(
        'cid' => @$data['ID'],
        'title' => @$data['name'],
        'seo_title' => convert::seoText(@$data['name']),
        'meta_description' => @$data['metadescr'],
        'meta_keywords' => @$data['tags'],
        'featured' => 1,
        'is_active' => 1
    );
}

function custom_conversion_games($mydb, $data, $slink = null) {
    if (!is_array($data) || empty($data))
        return false;

    return array(
        'gid' => @$data['ID'],
        'game_name' => @$data['name'],
        'seo_title' => convert::seoText($data['name']),
        'game_categories' => @$data['category'],
        'game_description' => @$data['description'],
        'game_keywords' => @$data['tags'],
        'game_tags' => null,
        'game_img' => @$data['thumb'],
        'featured_img' => null,
        'game_file' => @$data['file'],
        'game_width' => intval(@$data['width']),
        'game_height' => intval(@$data['height']),
        'game_adddate' => time(),
        'game_rating' => null,
        'game_votes' => null,
        'game_is_featured' => 0,
        'game_source' => 'convert',
        'game_is_active' => @convert::to_bool($data['active']) ? 1 : -1,
    );
}

function custom_conversion_links($mydb, $data, $slink = null) {
    if (!is_array($data) || empty($data))
        return false;
    return array(
        'id' => $data['ID'],
        'partner_title' => @$data['title'],
        'partner_url' => @$data['url'],
        'show_page_url' => @$data['linkbackat'],
        'expire_date' => date("Y-m-d", strtotime('+1 years')),
        'link_type' => 1,
        'status' => @convert::to_bool($data['activate']) ? 1 : 0,
    );
}

function custom_conversion_members($mydb, $data, $slink = null) {
    if (!is_array($data) || empty($data) || intval(@$data['userid']) <= 0)
        return false;
    return array(
        'id' => @$data['userid'],
        'username' => @$data['username'],
        'password' => @$data['password'],
        'email' => @$data['email'],
        'name' => @$data['name'],
        'avatar' => @basename($data['avatarfile']),
        'group' => 2,
        'status' => 1
    );
}