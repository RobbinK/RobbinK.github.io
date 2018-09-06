<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: ab_ads_funcs_ulib.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:58
##########################################################
 */


function _ab_show_adcode($zone_id, $random, $type) {
    if (!isset($zone_id) || !is_numeric($zone_id))
        return false;

    $country = agent::remote_info_country_code();

    $s = new pengu_cache(cache_path() . '/etc/ads','ads_');
    $s->bezipped(false); 
    $s->setCacheKey($zone_id);

    if (!$s->isCached()) {
        $data = Ad::getAds($zone_id);
        $s->write($data);
    }
    else
        $data = $s->read();

    foreach ($data as $k => $ad) {
        if (!preg_match("/{$country},/i", "{$ad['countries']},")) { // Check Countries
            unset($data[$k]);
        }
    }
    if ($random)
        shuffle($data);
    else {
        arrayUtil::array_sort($data, 'order');
    }

    if (empty($data))
        return false;

    $count = count($data);

    if ($count > 1) {
        $allads = $data;
        if (isset($_SESSION['ab_ads_shown'][$zone_id])) {
            $shown_ads = $_SESSION['ab_ads_shown'][$zone_id];

            foreach ($allads as $k => $ad) {
                if (in_array($ad['id'], $shown_ads))
                    unset($allads[$k]);
            }
        }

        reset($allads);
        $current = current($allads);

        if (@count($allads) <= 1) {
            $_SESSION['ab_ads_shown'][$zone_id] = array();
            if ($random)
                $_SESSION['ab_ads_shown'][$zone_id][] = $current['id'];
        } else {
            $_SESSION['ab_ads_shown'][$zone_id][] = $current['id'];
        }

        $out = $current['code'];
    } else {
        reset($data);
        $current = current($data);
        $out = $current['code'];
    }
    if ($type == 'banner') {
        ob_start();
        ?>
        <html>
            <head>
                <style>body{ margin: 0;padding: 0;}</style>
            </head>
            <body><?= $out ?></body>
        </html>
        <?php
        return ob_get_clean();
    }
    else
        return $out;
}