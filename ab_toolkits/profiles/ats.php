<?php

/* avscript */

function custom_paths() {
    if (import_check_session('paths')) {
        return import_get_session('paths');
    }
    $base_path = import_get_session('base_path');
    $base_path = rtrim($base_path, '/');
    return array(
        'thumbs' => $base_path . '/content/icons',
        'files' => $base_path . '/content/games',
        'avatars' => null,
        'customizable' => true,
    );
}

function custom_tables($link) {
    return array(
        'abs_games' => 'ats_games',
        'abs_categories' => 'ats_categories',
        'abs_members' => null,
        'abs_links' => null,
        'abs_games_tags' => null
    );
}

/* =============== Data =============== */

function custom_conversion_ctgs($mydb, $data, $slink = null) {
    if (!is_array($data) || empty($data))
        return false;
    return array(
        'cid' => @$data['cid'],
        'title' => @$data['cName'],
        'seo_title' => convert::seoText(@$data['cName']),
        'meta_description' => @$data['cDesc'],
        'meta_keywords' => @$data['cKeywords'],
        'featured' => 1,
        'is_active' => 1
    );
}

function custom_conversion_games($mydb, $data, $slink = null) {
    if (!is_array($data) || empty($data))
        return false;
    return array(
        'gid' => @$data['gid'],
        'game_name' => @$data['gName'],
        'seo_title' => convert::seoText(@$data['gName']),
        'game_categories' => fjoin(',', cats_to_ids($mydb, @$data['cat'])),
        'game_description' => @$data['gDesc'],
        'game_keywords' => @$data['gKeywords'],
        'game_tags' => fjoin(',', tags_to_ids($mydb, $data['gTags'])),
        'game_img' => @!convert::to_bool($data['featured']) ? @$data['gIcon1'] : null,
        'featured_img' => @convert::to_bool($data['featured']) ? @$data['gIcon1'] : null,
        'game_file' => $data['gFile'],
        'game_width' => intval(@$data['width']),
        'game_height' => intval(@$data['height']),
        'game_adddate' => !is_numeric(@$data['addedOn']) && !empty($data['addedOn']) ? strtotime($data['addedOn']) : $data['addedOn'],
        'game_rating' => @$data['rating'],
        'game_votes' => intval(@$data['voteScore']),
        'game_is_featured' => @convert::to_bool($data['featured']) ? 1 : 0,
        'game_source' => 'convert',
        'game_is_active' => @convert::to_bool($data['gStatus']) ? 1 : -1,
    );
}

