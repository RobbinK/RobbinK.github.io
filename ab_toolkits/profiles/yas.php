<?php

/* yas */

function custom_paths() {
    $base_path = import_get_session('base_path');
    $base_path = rtrim($base_path, '/');
    return array(
        'thumbs' => $base_path . '/img',
        'files' => $base_path . '/swf',
        'avatars' => $base_path . '/avatars/useruploads',
    );
}

function custom_tables($link) {
    return array(
        'abs_games' => 'games',
        'abs_categories' => 'categories',
        'abs_members' => 'user',
        'abs_links' => 'links',
        'abs_games_tags' => null
    );
}

/* =============== Data =============== */

function custom_conversion_ctgs($mydb, $data, $slink = null) {
    if (!is_array($data) || empty($data))
        return false;
    return array(
        'cid' => @$data['id'],
        'title' => @$data['name'],
        'seo_title' => convert::seoText(@$data['name']),
        'meta_description' => @$data['desc'],
        'meta_keywords' => null,
        'featured' => 1,
        'is_active' => 1
    );
}

function custom_conversion_games($mydb, $data, $slink = null) {
    if (!is_array($data) || empty($data))
        return false;

    return array(
        'gid' => @$data['id'],
        'game_name' => @$data['title'],
        'seo_title' => convert::seoText($data['title']),
        'game_categories' => @$data['category'],
        'game_description' => @$data['description'],
        'game_keywords' => @$data['keywords'],
        'game_tags' => null,
        'game_img' => @$data['thumbnail'],
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
        'id' => $data['id'],
        'partner_title' => @$data['text'],
        'partner_url' => @$data['url'],
        'expire_date' => date("Y-m-d", strtotime('+1 years')),
        'link_type' => 1,
        'status' => @convert::to_bool($data['approved']) ? 1 : 0,
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
        'name' => @$data['name'],
        'avatar' => @basename($data['avatarfile']),
        'group' => 2,
        'status' => 1
    );
}

