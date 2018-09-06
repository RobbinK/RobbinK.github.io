<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: sidebar.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:55
##########################################################
 */

global $routers_map;
$routers_map = array(
    'manage_links' => array('adminlinks', 'adminlinkexchange', 'adminrequestlink'),
    'manage_contents' => array('adminpages', 'adminblocks'),
    'manage_games' => array('admincategories', 'admingames', 'admin-poolimobilegamse', 'admingamesbroken', 'adminsubmitedgames', 'admingamecomments', 'adminimportpacks'),
    'manage_account' => array('adminmembers', 'adminbannedmembers'),
    'manage_ads' => array('admin-zone', 'admin-ads', 'admincountriesads'),
    'reports' => array('repabstats', 'trafficreport', 'georeport'),
    'feeds' => array('adminlatestfeeds', 'adminsubmityourgame', 'adminrevenuegames'),
    'traders' => array('admin-poolitraders', 'admin-pooliplugs', 'admin-poolitraderequest'),
    'configurations' => array('adminmainsetting', 'adminseo', 'admin-commentsetting', 'admin-gamesubmission', 'admin-membersetting', 'admin-cachesetting', 'admin-arcadeboostersetting', 'admin-scriptsetting', 'admin-feedsetting', 'admin-linkexchange-setting', 'admin-poolitradesetting', 'admin-sitemapsetting', 'admin-themesettings')
);

$dt = arrayUtil::array_search($routers_map, 'x', $route->getName());

function ab_current_ac_st($gname)
{
    static $accordion;
    if (!isset($accordion)) {
        global $routers_map, $route;
        $accordion = array();
        foreach ($routers_map as $k => $v) {
            if (in_array($route->getName(), $v))
                $accordion[$k] = 'in';
        }
    }
    if (isset($accordion[$gname]))
        return $accordion[$gname];
}

function ab_current_link_st($routename)
{
    global $route;
    if ($route->getName() == $routename)
        return 'active';
}

?>
<!-- Sidebar -->
<a href="javascript:void(0)" class="sidebar_switch on_switch ttip_r"
   title="<?= addslashes(L::sidebar_hide_sidebar); ?>">Sidebar switch</a>
<div class="sidebar">

<div class="sidebar_inner_scroll">
<div class="sidebar_inner">
<div style="height: 82px;padding: 14px 0 0 0px;">
    <div>
        <ul class="nav nav-list">
            <li style=" color: #999; font: 12px arial;margin-bottom: 9px;"><i
                    class="icon-time"></i><?= Date('H:i') ?>  &nbsp;  <?= strtoupper(Date('D Y-m-d')) ?></li>
            <li><a style="color:#222222;line-height: 18px;" href="<?= url::router('admindashboard') ?>"><i
                        class="icon-th"></i> <?= L::sidebar_view_dashboard; ?></a></li>
            <li><a style="color:#222222;line-height: 18px;" href="<?= url::router('homepage') ?>" target="_blank"><i
                        class="icon-home"></i> <?= L::sidebar_view_homepage; ?></a></li>
        </ul>
    </div>
</div>
<div id="side_accordion" class="accordion">

<div class="accordion-group">
    <div class="accordion-heading">
        <a href="#collapse_gamem" data-parent="#side_accordion" data-toggle="collapse" class="accordion-toggle">
            <i class="icon-leaf"></i> <?= L::sidebar_games_mng; ?>
        </a>
    </div>
    <div class="accordion-body collapse <?= ab_current_ac_st('manage_games') ?>" id="collapse_gamem">
        <div class="accordion-inner">
            <ul class="nav nav-list">
                <li class="<?= ab_current_link_st('admincategories') ?>"><a
                        href="<?= url::router('admincategories') ?>"><?= L::sidebar_ctg_mng; ?></a></li>
                <li class="<?= ab_current_link_st('admingames') ?>"><a
                        href="<?= url::router('admingames') ?>"><?= L::sidebar_web_games; ?></a></li>
                <li class="<?= ab_current_link_st('admin-poolimobilegamse') ?>"><a
                        href="<?= url::router('admin-poolimobilegamse') ?>"><?= L::sidebar_mob_games; ?></a>
                </li>
                <li class="<?= ab_current_link_st('admingamecomments') ?>">
                    <a href="<?= url::router('admingamecomments') ?>"><?= L::sidebar_game_cmnt; ?>
                        <?= intval($gamecomment_unread) > 0 ? '<span class="pull-right label label-important">' . intval($gamecomment_unread) . '</span>' : null ?>
                    </a>
                </li>
                <li class="<?= ab_current_link_st('adminsubmitedgames') ?>">
                    <a href="<?= url::router('adminsubmitedgames') ?>"><?= L::sidebar_subm_games; ?>
                        <?= intval($submitedGames_unread) > 0 ? '<span class="pull-right label label-important">' . intval($submitedGames_unread) . '</span>' : null ?>
                    </a>
                </li>
                <li class="<?= ab_current_link_st('admingamesbroken') ?>">
                    <a href="<?= url::router('admingamesbroken') ?>">
                        <?= L::sidebar_brkn_games; ?>
                        <?= intval($brokenGames_unread) > 0 ? '<span class="pull-right label label-important">' . intval($brokenGames_unread) . '</span>' : null ?>
                    </a>
                </li>
                <li class="<?= ab_current_link_st('adminimportpacks') ?>">
                    <a href="<?= url::router('adminimportpacks') ?>"><?= L::sidebar_imp_game_pck; ?></a>
                </li>

            </ul>
        </div>
    </div>
</div>

<div class="accordion-group">
    <div class="accordion-heading">
        <a href="#collapse_gamef" data-parent="#side_accordion" data-toggle="collapse" class="accordion-toggle">
            <i class="icon-barcode"></i> <?= L::sidebar_game_feeds; ?>
        </a>
    </div>
    <div class="accordion-body collapse <?= ab_current_ac_st('feeds') ?>" id="collapse_gamef">
        <div class="accordion-inner">
            <ul class="nav nav-list">
                <li class="<?= ab_current_link_st('adminlatestfeeds') ?>"><a
                        href="<?= url::router('adminlatestfeeds') ?>"><?= L::sidebar_new_feeds; ?></a></li>
                <li class="<?= ab_current_link_st('adminrevenuegames') ?>"><a
                        href="<?= url::router('adminrevenuegames') ?>"><?= L::sidebar_rev_share_games; ?></a>
                </li>
                <li class="<?= ab_current_link_st('adminsubmityourgame') ?>"><a
                        href="<?= master_url ?>/submitgame.html"
                        target="_blank"><?= L::sidebar_submit_game; ?></a></li>
            </ul>
        </div>
    </div>
</div>
<div class="accordion-group">
    <div class="accordion-heading">
        <a href="#collapse_adsm" data-parent="#side_accordion" data-toggle="collapse" class="accordion-toggle">
            <i class="icon-flag"></i> <?= L::sidebar_ads_mng; ?>
        </a>
    </div>
    <div class="accordion-body collapse <?= ab_current_ac_st('manage_ads') ?>" id="collapse_adsm">
        <div class="accordion-inner">
            <ul class="nav nav-list">
                <li class="<?= ab_current_link_st('admin-zone') ?>"><a
                        href="<?= url::router('admin-zone') ?>"><?= L::sidebar_mng_ad_zone; ?></a></li>
            </ul>
        </div>
    </div>
</div>
<div class="accordion-group">
    <div class="accordion-heading">
        <a href="#collapse_linkm" data-parent="#side_accordion" data-toggle="collapse" class="accordion-toggle">
            <i class="icon-qrcode"></i> <?= L::sidebar_lnk_mng; ?>
        </a>
    </div>
    <div class="accordion-body collapse <?= ab_current_ac_st('manage_links') ?>" id="collapse_linkm">
        <div class="accordion-inner">
            <ul class="nav nav-list">
                <li class="<?= ab_current_link_st('adminlinks') ?>"><a
                        href="<?= url::router('adminlinks') ?>"><?= L::sidebar_prt_lnk; ?></a></li>
                <li class="<?= ab_current_link_st('adminlinkexchange') ?>"><a
                        href="<?= url::router('adminlinkexchange') ?>"><?= L::sidebar_mng_exch; ?></a></li>
                <li class="<?= ab_current_link_st('adminrequestlink') ?>"><a
                        href="<?= url::router('adminrequestlink') ?>">
                        <?= L::sidebar_exch_req; ?>
                        <?= intval($linkRequests_unread) > 0 ? '<span class="pull-right label label-important">' . intval($linkRequests_unread) . '</span>' : null ?>
                    </a></li>
            </ul>
        </div>
    </div>
</div>
<div class="accordion-group">
    <div class="accordion-heading">
        <a href="#collapse_acm" data-parent="#side_accordion" data-toggle="collapse" class="accordion-toggle">
            <i class="icon-user"></i> <?= L::sidebar_acc_mng; ?>
        </a>
    </div>
    <div class="accordion-body collapse <?= ab_current_ac_st('manage_account') ?>" id="collapse_acm">
        <div class="accordion-inner">
            <ul class="nav nav-list">
                <li class="<?= ab_current_link_st('adminmembers') ?>">
                    <a href="<?= url::router('adminmembers') ?>">
                        <?= L::sidebar_mem_list; ?>
                        <?= intval($unactive_member) > 0 ? '<span class="pull-right label label-important">' . intval($unactive_member) . '</span>' : null ?>
                    </a>
                </li>
                <li class="<?= ab_current_link_st('adminbannedmembers') ?>"><a
                        href="<?= url::router('adminbannedmembers') ?>"><?= L::sidebar_ban_mem; ?></a></li>
            </ul>

        </div>
    </div>
</div>
<div class="accordion-group">
    <div class="accordion-heading">
        <a href="#collapse_mpg" data-parent="#side_accordion" data-toggle="collapse" class="accordion-toggle">
            <i class="icon-file"></i> <?= L::sidebar_cnt_pg_mng; ?>
        </a>
    </div>
    <div class="accordion-body collapse <?= ab_current_ac_st('manage_contents') ?>" id="collapse_mpg">
        <div class="accordion-inner">
            <ul class="nav nav-list">
                <li class="<?= ab_current_link_st('adminpages') ?>"><a
                        href="<?= url::router('adminpages') ?>"><?= L::sidebar_mng_pg; ?></a></li>
            </ul>
            <ul class="nav nav-list">
                <li class="<?= ab_current_link_st('adminblocks') ?>"><a
                        href="<?= url::router('adminblocks') ?>"><?= L::sidebar_mng_block; ?></a></li>
            </ul>
        </div>
    </div>
</div>
<div class="accordion-group">
    <div class="accordion-heading">
        <a href="#collapse_advre" data-parent="#side_accordion" data-toggle="collapse" class="accordion-toggle">
            <i class="icon-list-alt"></i> <?= L::sidebar_adv_rep; ?>
        </a>
    </div>
    <div class="accordion-body collapse <?= ab_current_ac_st('reports') ?>" id="collapse_advre">
        <div class="accordion-inner">
            <ul class="nav nav-list">
                <li class="<?= ab_current_link_st('trafficreport') ?>"><a
                        href="<?= url::router('trafficreport') ?>"><?= L::sidebar_traffic_rep; ?></a></li>
                <li class="<?= ab_current_link_st('georeport') ?>"><a
                        href="<?= url::router('georeport') ?>"><?= L::sidebar_geo_rep; ?></a></li>
                <li class=" <?= ab_current_link_st('repabstats') ?>">
                    <a href="<?= url::router('repabstats') ?>">
                        <?= L::sidebar_site_earn_rep; ?></a>
                </li>
            </ul>

        </div>
    </div>
</div>
<div class="accordion-group">
    <div class="accordion-heading">
        <a href="#collapse_trdm" data-parent="#side_accordion" data-toggle="collapse" class="accordion-toggle">
            <i class=" icon-random"></i> <?= L::sidebar_trd_mng; ?>
        </a>
    </div>
    <div class="accordion-body collapse <?= ab_current_ac_st('traders') ?>" id="collapse_trdm">
        <div class="accordion-inner">
            <ul class="nav nav-list">
                <li class="<?= ab_current_link_st('admin-poolitraders') ?>"><a
                        href="<?= url::router('admin-poolitraders') ?>"><?= L::sidebar_traders; ?></a></li>
                <li class="<?= ab_current_link_st('admin-poolitraderequest') ?>"><a
                        href="<?= url::router('admin-poolitraderequest') ?>">
                        <?= L::sidebar_trd_req; ?>
                        <?= intval($tradeRequests_unread) > 0 ? '<span class="pull-right label label-important">' . intval($tradeRequests_unread) . '</span>' : null ?>
                    </a></li>
            </ul>
        </div>
    </div>
</div>
<div class="accordion-group">
    <div class="accordion-heading">
        <a href="#collapse_configuration" data-parent="#side_accordion" data-toggle="collapse"
           class="accordion-toggle">
            <i class="icon-cog"></i> <?= L::sidebar_conf; ?>
        </a>
    </div>
    <div class="accordion-body collapse <?= ab_current_ac_st('configurations') ?>" id="collapse_configuration">
        <div class="accordion-inner">
            <ul class="nav nav-list">
                <li class="<?= ab_current_link_st('adminmainsetting') ?>"><a
                        href="<?= url::router('adminmainsetting') ?>"><?= L::sidebar_main_set; ?></a></li>
                <li class="<?= ab_current_link_st('admin-feedsetting') ?>"><a
                        href="<?= url::router('admin-feedsetting') ?>"><?= L::sidebar_feed_set; ?></a></li>
                <li class="<?= ab_current_link_st('admin-cachesetting') ?>"><a
                        href="<?= url::router('admin-cachesetting') ?>"><?= L::sidebar_cdn_set; ?></a></li>
                <li class="<?= ab_current_link_st('admin-sitemapsetting') ?>"><a
                        href="<?= url::router('admin-sitemapsetting') ?>"><?= L::sidebar_sitemap; ?></a></li>
                <li class="<?= ab_current_link_st('adminseo') ?>"><a
                        href="<?= url::router('adminseo') ?>"><?= L::sidebar_seo_set; ?></a></li>
                <li class="<?= ab_current_link_st('admin-commentsetting') ?>"><a
                        href="<?= url::router('admin-commentsetting') ?>"><?= L::sidebar_cmnt_set; ?></a></li>
                <li class="<?= ab_current_link_st('admin-membersetting') ?>"><a
                        href="<?= url::router('admin-membersetting') ?>"><?= L::sidebar_mem_set; ?></a></li>
                <li class="<?= ab_current_link_st('admin-scriptsetting') ?>"><a
                        href="<?= url::router('admin-scriptsetting') ?>"><?= L::sidebar_cstm_scrips; ?></a></li>
                <li class="<?= ab_current_link_st('admin-arcadeboostersetting') ?>">
                    <a href="<?= url::router('admin-arcadeboostersetting') ?>">
                        ArcadeBooster Setting</a>
                </li>
                <li class="<?= ab_current_link_st('admin-poolitradesetting') ?>"><a
                        href="<?= url::router('admin-poolitradesetting') ?>"><?= L::sidebar_trd_set; ?></a></li>
                <li class="<?= ab_current_link_st('admin-themesettings') ?>"><a
                        href="<?= url::router('admin-themesettings') ?>"><?= L::sidebar_theme_set; ?></a></li>
                <li><a href="<?= url::router('admindashboard')->url_nonqry(array('backupsource' => 1)); ?>"
                       onclick="return confirm('Do you want to take backup from your source?');"><i
                            class="icon-download-alt"></i> Backup Files</a></li>
                <li><a href="<?= url::router('admindashboard')->url_nonqry(array('backupdb' => 1)); ?>"
                       onclick="return confirm('Do you want to take backup from your database?');"><i
                            class="icon-download-alt"></i> Backup DataBase</a></li>
            </ul>
        </div>
    </div>
</div>
</div>

<div class="push"></div>
</div>

<div class="sidebar_info">
    <ul class="unstyled">
        <li>
            <strong>License Type :</strong>
            <span class="act act-info sepH_a" style="direction: ltr">
            <?php
            global $license_type;
            echo ucfirst($license_type);
            ?>
            </span>
            <?php if ($license_type != 'premium') : ?>
                <center><a href="<?= master_url ?>/dlpremiumversion.html?getlicense"
                           class="btn btn-info btn-medium sepH_b" target="_blank">Get premium version</a></center>
            <?php endif; ?>
        </li>
    </ul>
</div>

</div>

</div>