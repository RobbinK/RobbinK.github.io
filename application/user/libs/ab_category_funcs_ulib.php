<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: ab_category_funcs_ulib.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:58
##########################################################
 */


function ab_categories($limit = null, $extended = true) {
    $cats = mobileApp() ? new MobileCategory() : new Category;
    $cats->AllCategories($limit, $extended);
    return $cats;
}

function ab_featured_categories($limit = null, $extended = true) {
    $cats = mobileApp() ? new MobileCategory() : new Category;
    $cats->FeaturedCategories($limit);
    return $cats;
}

function ab_non_featured_categories($limit = null, $extended = true) {
    $cats = mobileApp() ? new MobileCategory() : new Category;
    $cats->nonFeaturedCategories($limit, $extended);
    return $cats;
}

function ab_category($category_seo_title) {
    static $res;
    if (isset($res[$category_seo_title]))
        return $res[$category_seo_title];
    $cats = mobileApp() ? new MobileCategory() : new Category;
    $res[$category_seo_title] = $cats->SelectCategory($category_seo_title);
    return $res[$category_seo_title];
}

/* tags */

function ab_tags($limit = null, $extended = true) {
    $tags = mobileApp() ? new MobileTag() : new Game_tag;
    $cats->AllTags($limit, $extended);
    return $cats;
}

function ab_tag($tag_seo_title) {
    static $res;
    if (isset($res[$tag_seo_title]))
        return $res[$tag_seo_title];
    $tags = mobileApp() ? new MobileTag() : new Game_tag;
    $res[$tag_seo_title] = $tags->SelectTag($tag_seo_title);
    return $res[$tag_seo_title];
}