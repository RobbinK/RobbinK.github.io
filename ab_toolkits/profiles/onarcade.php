<?php

/* avscript */

function custom_paths() {
    if (import_check_session('paths')) {
        return import_get_session('paths');
    }
    $base_path = import_get_session('base_path');
    $base_path = rtrim($base_path, '/');
    return array(
        'thumbs' => $base_path . '/files/image',
        'files' => $base_path . '/files/file',
        'avatars' => $base_path . '/images/avatars',
        'customizable' => true,
    );
}

function custom_tables($link) {
    $dbsourceinfo = import_get_session('source_con');
    if (version_compare(getMySQLVersion(), '5.0.0', '<='))
        exit("<font style='color:red'>Your MySQL version should be more than 5.0.x </font>");

    //--games
    $data = $link->query("SHOW TABLES like '%files'")->exec();
    $abs_games_table = null;
    if ($data->found()) {
        $d = $data->current;
        $abs_games_table = current($d);
    }
    //--cats 
    $data = $link->query("SHOW TABLES where   `Tables_in_{$dbsourceinfo['db']}`  like '%categories'  and  `Tables_in_{$dbsourceinfo['db']}`  not  like '%forums_categories';")->exec();
    $abs_cats_table = null;
    if ($data->found()) {
        $d = $data->current;
        $abs_cats_table = current($d);
    }
    //--users
    $data = $link->query("SHOW TABLES like '%users'")->exec();
    $abs_users_table = null;
    if ($data->found()) {
        $d = $data->current;
        $abs_users_table = current($d);
    }
    //--cats
    $data = $link->query("SHOW TABLES like '%links'")->exec();
    $abs_links_table = null;
    if ($data->found()) {
        $d = $data->current;
        $abs_links_table = current($d);
    }

    return array(
        'abs_games' => $abs_games_table,
        'abs_categories' => $abs_cats_table,
        'abs_members' => $abs_users_table,
        'abs_links' => $abs_links_table,
        'abs_games_tags' => null
    );
}

/* =============== Data =============== */

function custom_conversion_ctgs($mydb, $data, $slink = null) {
    if (!is_array($data) || empty($data))
        return false;
    return array(
        'cid' => @$data['catid'],
        'title' => @$data['name'],
        'seo_title' => convert::seoText(@$data['name']),
        'meta_description' => @$data['description'],
        'meta_keywords' => @$data['keywords'],
        'featured' => 1,
        'is_active' => @convert::to_bool($data['status']) ? 1 : -1,
    );
}

function custom_conversion_games($mydb, $data, $slink = null) {
    if (!is_array($data) || empty($data))
        return false;
    return array(
        'gid' => @$data['fileid'],
        'game_name' => @$data['title'],
        'seo_title' => convert::seoText($data['title']),
        'game_categories' => @$data['category'],
        'game_description' => @$data['description'],
        'game_keywords' => @$data['keywords'],
        'game_tags' => null,
        'game_img' => @!convert::to_bool($data['featured']) ? @$data['icon'] : null,
        'featured_img' => @convert::to_bool($data['featured']) ? @$data['icon'] : null,
        'game_file' => $data['file'],
        'game_width' => intval(@$data['width']),
        'game_height' => intval(@$data['height']),
        'game_adddate' => !is_numeric(@$data['dateadded']) && !empty($data['dateadded']) ? strtotime($data['dateadded']) : $data['dateadded'],
        'game_rating' => @$data['rating'],
        'game_votes' => intval(@$data['totalvotes']),
        'game_is_featured' => @convert::to_bool($data['featured']) ? 1 : 0,
        'game_source' => 'convert',
        'game_is_active' => @convert::to_bool($data['status']) ? 1 : -1,
    );
}

function custom_conversion_links($mydb, $data, $slink = null) {
    if (!is_array($data) || empty($data))
        return false;
    return array(
        'id' => $data['linkid'],
        'partner_title' => @$data['name'],
        'partner_url' => @$data['linkurl'],
        'expire_date' => date("Y-m-d", strtotime('+1 years')),
        'link_type' => 1,
        'status' => @convert::to_bool($data['status']) ? 1 : 0,
    );
}

function custom_conversion_members($data) {
    if (!is_array($data) || empty($data))
        return false;
    return array(
        'id' => @$data['userid'],
        'username' => @$data['username'],
        'password' => @$data['password'],
        'email' => @$data['email'],
        'name' => @$data['username'],
        'avatar' => @basename($data['avatar']),
        'group' => 2,
        'status' => @convert::to_bool($data['status']) ? 1 : 0,
    );
}

