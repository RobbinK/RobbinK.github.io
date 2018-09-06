<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: dashboard.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:54
##########################################################
 */

global $agoLanguage;
### call header
abs_admin_inc(l_basic);
abs_admin_inc(l_colorbox);
abs_admin_inc(l_sparkline);
abs_admin_inc(l_touch_punch);
abs_admin_inc(l_wookmark);
abs_admin_inc(l_mediaTable);
abs_admin_inc(l_smoke);
abs_admin_inc(l_flot);
abs_admin_inc(l_hint);
abs_admin_inc(l_yepnope);
abs_admin_inc(l_bootstrap_modal);
abs_admin_inc_js(template_path() . '/js/abs_dashboard.js');
get_header();
#************** 
?>
<!-- main content -->
<div id="contentwrapper">
<div class="main_content">
<?php
/* show system messages */

function __ArrayTOmessage($data)
{
    if (is_array($data))
        foreach ($data as $k => $msgs) :
            $class = 'alert-info';
            switch ($k) {
                case 'info' :
                    $class = 'alert-info';
                    break;
                case 'error' :
                    $class = 'alert-error';
                    break;
                case 'warning' :
                    $class = 'alert-warning';
                    break;
                case 'success' :
                    $class = 'alert-success';
                    break;
            }
            ?>
            <?php foreach ($msgs as $msg) : ?>
            <div class="alert <?= $class ?> systemMessagess">
                <a class="close">×</a>
                <?= $msg ?>
            </div>
        <?php
        endforeach;
        endforeach;
}

$i = 100;
while ($i >= 1) {
    if (isAlert('syserror' . $i))
        __ArrayTOmessage(alert('syserror' . $i)->options(array(ALERT_OP_ARRAY => true))->getResult());
    $i--;
}
if (isAlert('syserror'))
    __ArrayTOmessage(alert('syserror')->options(array(ALERT_OP_ARRAY => true))->getResult());
?>
<?php
/*  messages */
$data = alert('privatemessages')->options(array(ALERT_OP_ARRAY => true))->getResult();
if (is_array($data))
    foreach ($data as $k => $msgs) :
        $class = 'alert-info';
        switch ($k) {
            case 'info' :
                $class = 'alert-info';
                break;
            case 'error' :
                $class = 'alert-error';
                break;
            case 'warning' :
                $class = 'alert-warning';
                break;
            case 'success' :
                $class = 'alert-success';
                break;
        }
        ?>
        <?php foreach ($msgs as $msg) : ?>
        <div class="alert <?= $class ?> privatemessage">
            <a class="close msg-dismiss">×</a>
            <?= $msg ?>
        </div>
    <?php
    endforeach;
    endforeach;
?>

<!--News-->
<?php
if (!empty($limitnews)):
    foreach ($limitnews as $news):
        if (!isset($_COOKIE['dismissNews' . $news['id']])):
            ?>
            <div class="alert alert-block alert-info fade in news-attention">
                <a class="close news-dismiss" data-newsid="<?= $news['id'] ?>">×</a>
                <h4 class="alert-heading"><?= $news['title'] ?></h4>
                <?= str::summarize($news['description'], 100); ?>

                <div style="height: 22px; padding-top: 6px;">
                    <a href="javascript:void(0);" class="btn btn-info btn-mini news-detail"
                       data-id="<?= $news['id'] ?>"><?= L::dashboard_view_details; ?></a>
                </div>
            </div>

            <?php
            break;
        endif;
    endforeach;
endif;
?>
<!--end News-->

<!--Updating attentions-->
<?php if (!isset($_COOKIE['dismissUpdate']) && version_compare(sys_ver, get_available_version(), '<') && get_available_version()): ?>
    <div class="alert alert-block  fade in update-attention">
        <h4 class="alert-heading"><?= L::alert_new_version; ?></h4>

        <p>
            <?= L::alert_update_message; ?> <br>
            <?= L::alert_current_vesrion; ?> : <?= sys_ver ?> <br>
            <?= L::alert_latest_version; ?> : <?= get_available_version() ?>
        </p>

        <div style="height: 22px; padding-top: 6px;">
            <a href="<?= url::router('admin-updatescript') ?>"
               class="btn btn-success btn-mini"><?= L::dashboard_update; ?></a>
            <a href="javascript:void(0);"
               class="btn btn-warning btn-mini update-dismiss"><?= L::dashboard_dismiss; ?></a>
        </div>
    </div>
<?php endif; ?>
<!--end updating attentions-->

<?php if (generatingStats()) : ?>
    <div class="row-fluid">
        <div class="span12 tac">
            <ul class="ov_boxes">
                <li>
                    <div class="p_canvas flt" style="position: relative;height: 32px;overflow: hidden;direction: rtl;">
                        <div class="p_bar_down" style="position: absolute;">[0,<?= join(',', $chart_pageview) ?>]</div>
                    </div>
                    <div class="ov_text">
                        <strong><?= number_format($chart_pageview[count($chart_pageview) - 1]) ?></strong>
                        <?= L::dashboard_page_views; ?>
                    </div>
                </li>
                <li>
                    <div class="p_canvas flt" style="position: relative;height: 32px;overflow: hidden;direction: rtl;">
                        <div class="p_bar_up" style="position: absolute;">[0,<?= join(',', $chart_pageview_avg) ?>]
                        </div>
                    </div>

                    <div class="ov_text">
                        <strong><?= $chart_pageview_avg[count($chart_pageview_avg) - 1] ?></strong>
                        <?= L::dashboard_pageview_avg; ?>
                    </div>
                </li>
                <li>
                    <div class="p_canvas flt" style="position: relative;height: 32px;overflow: hidden;direction: rtl;">
                        <div class="p_line_up" style="position: absolute;">[0,<?= join(',', $chart_gameplays) ?>]</div>
                    </div>
                    <div class="ov_text">
                        <strong><?= number_format($chart_gameplays[count($chart_gameplays) - 1]) ?></strong>
                        <?= L::dashboard_game_plays; ?>
                    </div>
                </li>
                <li>
                    <div class="p_canvas flt" style="position: relative;height: 32px;overflow: hidden;direction: rtl;">
                        <div class="p_line_down" style="position: absolute;">[0,<?= join(',', $chart_bounce_rate) ?>]
                        </div>
                    </div>
                    <div class="ov_text">
                        <strong><?= $chart_bounce_rate[count($chart_bounce_rate) - 1] ?>%</strong>
                        <?= L::dashboard_bounce_rate; ?>
                    </div>
                </li>
            </ul>
        </div>
    </div>
<?php endif; ?>

<div class="row-fluid">
    <div class="span12">
        <ul class="dshb_icoNav tac">
            <li><a href="<?= url::router('admingames') ?>?new"
                   style="background-image: url(img/gCons/add-item.png)"><?= L::dashboard_add_new_game; ?></a></li>
            <li><a href="<?= url::router('adminimportpacks') ?>" style="background-image: url(img/gCons/lab.png)"
                   class="import"><?= L::dashboard_import_games; ?></a></li>
            <li><a href="<?= url::router('admin-poolitraders') ?>"
                   style="background-image: url(img/gCons/connected.png)"><?= L::dashboard_traders; ?></a></li>
            <li><a href="javascript:void(0)"
                   style="background-image: url(img/gCons/rss.png);"><?= L::dashboard_game_feed; ?></a></li>
            <li><a href="<?= url::router('admin-zone') ?>"
                   style="background-image: url(img/gCons/network-pc.png);"><?= L::dashboard_manage_ads; ?></a></li>
            <li><a href="<?= url::router('adminmembers') ?>"
                   style="background-image: url(img/gCons/multi-agents.png)"><?= L::dashboard_members; ?></a></li>
            <li><a href="<?= url::router('admingamecomments') ?>" style="background-image: url(img/gCons/chat-.png)">
                    <?= intval($gamecomment_unread) > 0 ? '<span class="pull-right label label-important">' . intval($gamecomment_unread) . '</span>' : null ?>
                    <?= L::dashboard_comments; ?></a></li>
        </ul>
    </div>
</div>


<?php if (generatingStats()) : ?>
    <div class="row-fluid">
        <div class="span4">
            <div class="heading clearfix">
                <h3 class="pull-left"><?= L::dashboard_geo_traffic_overview; ?></h3>
                <span class="pull-right label label-important"><?= L::global_today; ?></span>
            </div>
            <table class="table table-bordered table-condensed">
                <thead>
                <tr>
                    <th class="essential persist"><?= L::global_country; ?></th>
                    <th class="optional"><?= L::global_statistics; ?></th>
                </tr>
                </thead>
                <tbody>
                <?php
                if (isset($v_countries_today))
                    while ($data = current($v_countries_today)) :
                        ?>
                        <tr>
                            <td>
                                <?php if (!empty($data['code'])) { ?>
                                    <i class="flag-<?= $data['code'] ?>"
                                       style="border: 0"></i>&nbsp;&nbsp;<?= str::summarize($data['country'], 25) ?>
                                <?php
                                } else
                                    echo L::global_unknown;
                                ?>
                            </td>
                            <td><?= $data['visit'] ?>
                                <span class="pull-right label label-info"><?= @round($data['ratio'] * 100, 2) ?>%</span>
                            </td>
                        </tr>
                        <?php
                        next($v_countries_today);
                    endwhile;
                ?>
                </tbody>
            </table>
        </div>
        <div class="span8" style="overflow:hidden">
            <div class="heading clearfix">
                <h3 class="pull-left"><?= L::dashboard_daily_traffic; ?></h3>
            </div>
            <?php
            if (isset($v_daily)) {
                $xy = array();
                foreach ($v_daily as $v) {
                    $xy[] = "['" . $v['date'] . "'," . $v['visit'] . "]";
                }
                $v_daily_res = join(',', $xy);
            }
            ?>
            <div id="fl_1" data-xy="<?= @$v_daily_res ?>" style="height:270px;width:100%;"></div>
        </div>
    </div>
<?php endif; ?>

<!--====== Latest Feed Games ======-->
<?php if (!empty($limitfeed)) : ?>
    <!--Modal-->
    <div id="feed-modal" class="modal hide fade" tabindex="-1">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h3><?= L::forms_install; ?></h3>
        </div>
        <div class="modal-body">
        </div>
        <div class="modal-footer">
            <div class="btn-group dropup text-left">
                <button data-toggle="dropdown" class="btn btn-success dropdown-toggle"><?= L::global_action; ?> <span
                        class="caret"></span></button>
                <ul class="dropdown-menu">
                    <li><a href="javascript:void(0);" class="install"><?= L::forms_install_activate; ?></a></li>
                    <li><a href="javascript:void(0);" class="installedit"><?= L::forms_install_edit; ?></a></li>
                    <li><a href="javascript:void(0);" class="installqueue"><?= L::forms_install_queue; ?></a></li>
                </ul>
            </div>
            <button type="button" data-dismiss="modal" class="btn"><?= L::global_cancel; ?></button>
        </div>
    </div>
    <!----->
    <div class="row-fluid">
        <div class="span12" style="position: relative">
            <div class="heading clearfix">
                <h3 class="pull-left"><?= L::dashboard_new_revenue_sharing_games; ?>
                    <!--small>(<?= L::dashboard_no_ingame_ads; ?>)</small-->
                </h3>
                <span class="pull-right label label-important"><?= L::dashboard_exclusive; ?></span>
            </div>

            <a class="pull-right btn btn-mini" href="<?= url::router('adminrevenuegames') ?>"
               style="position: absolute;bottom: -5px;right: 0px;"><?= L::dashboard_more_games; ?></a>

            <div id="last30feeds" class="contentHolder">
                <div id="small_grid" class="wmk_grid" data-width="80">
                    <ul>
                        <?php
                        foreach ($limitfeed as $feed):
                            ?>
                            <li class="thumbnail">
                                <a href="#feed-modal" class="pop_over" style="display: block"
                                   data-id="<?= $feed['fid'] ?>" data-container="body" data-toggle="popover"
                                   data-placement="top" data-content="<?= $feed['short_disc'] ?>" title=""
                                   data-original-title="<?= str::summarize($feed['name'], 35) ?>">
                                    <?php if (isset($feed['installed']) && $feed['installed']) : ?>
                                        <div class='overlay'>
                                            <span>  Installed<i class="splashy-check"></i></span>
                                        </div>
                                    <?php endif; ?>
                                    <img alt="<?= $feed['name'] ?>"
                                         src="<?= master_rev_sharing_games_images_dir . '/' . $feed['thumbnail_100x100'] ?>"/>
                                </a>
                            </li>
                        <?php
                        endforeach;
                        ?>
                    </ul>
                </div>

            </div>
        </div>
    </div>
<?php endif; ?>

<div class="row-fluid">

    <?php
    $span = 12;
    if (convert::to_bool(Setting::get_data('active_trading', 'val'))) : $span -= 0; //4;
        ?>
        <div class="span4">
            <div class="heading clearfix">
                <h3 class="pull-left"><?= L::dashboard_top_traffic_traders; ?>
                    <a class="help-inline" href="<?= url::router('admin-poolitradesetting') ?>">Setting</a>
                </h3>
                <span class="pull-right label label-important"><?= L::global_today; ?></span>
            </div>
            <table class="table table-bordered table-condensed">
                <thead>
                <tr>
                    <th class="essential persist"><?= L::dashboard_trader; ?></th>
                    <th class="optional"><?= L::dashboard_today_in; ?></th>
                    <th class="optional"><?= L::dashboard_today_out; ?></th>
                </tr>
                </thead>
                <tbody>
                <?php
                if (isset($traders_today))
                    while ($data = current($traders_today)) :
                        ?>
                        <tr>
                            <td><?= str::summarize($data['title'], 20) ?></td>
                            <td><?= number_format($data['total_in']) ?></td>
                            <td><?= number_format($data['total_out']) ?></td>
                        </tr>
                        <?php
                        next($traders_today);
                    endwhile;
                ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <div class="span<?= $span ?>">
        <div class="heading clearfix">
            <h3 class="pull-left">
                Your revenue (arcadebooster)
                <a class="help-inline" href="<?= url::router('admin-arcadeboostersetting') ?>">Setting</a>
            </h3>
        </div>
        <?php
        if (isset($abs_daily)) {
            $xy = array();
            foreach ($abs_daily as $v) {
                $xy[] = "['" . $v['date'] . "'," . $v['earns'] . "]";
            }
            $abs_daily_res = join(',', $xy);
        }
        ?>
        <div id="fl_2" data-xy="<?= @$abs_daily_res ?>" style="height:270px;width:100%;"></div>
    </div>

</div>

<?php if (isset($link_sale_Data)) : ?>
    <div class="row-fluid">
    <div>

    <div class="heading clearfix">
        <h3 class="pull-left"><?= L::dashboard_arcadebooster_market; ?></h3>
        <!--<span class="pull-right label label-important"><?= L::global_today; ?></span>-->
    </div>

    <style>
        .tab-pane a {
            color: #222222;
        }

        .tab-pane a:hover {
            text-decoration: underline;
        }
    </style>
    <!--Modal-->
    <div id="marketplace-modal" class="modal hide fade" tabindex="-1">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h3></h3>
        </div>
        <div class="modal-body">
        </div>
        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn"><?= L::global_cancel; ?></button>
        </div>
    </div>
    <div class="tabbable tabs-left">
    <ul class="nav nav-tabs">
        <li class="active"><a href="#tab_br1" data-toggle="tab"><?= L::header_link_sales; ?></a></li>
        <li><a href="#tab_br2" data-toggle="tab"><?= L::header_arcades_for_sale; ?></a></li>
        <li><a href="#tab_br3" data-toggle="tab"><?= L::header_domains_for_sale; ?></a></li>
        <li><a href="#tab_br4" data-toggle="tab"><?= L::header_game_sponsorship; ?></a></li>
        <li><a href="#tab_br5" data-toggle="tab"><?= L::header_link_exchange_requests; ?></a></li>
        <li><a href="#tab_br6" data-toggle="tab"><?= L::header_arcade_discussions; ?> </a></li>
    </ul>
    <div class="tab-content">
    <!--Tab1-->
    <div class="tab-pane active" id="tab_br1">
        <table class="table table-condensed">
            <thead>
            <tr>
                <th role="columnheader" rowspan="1" colspan="1" aria-label="" style="width: 16px;"><i
                        class="splashy-mail_light"></i></th>
                <th><?= L::global_subject; ?></th>
                <th style="width: 60px"><?= L::global_sender; ?></th>
                <th style="width: 60px"><?= L::global_time; ?></th>
            </tr>
            </thead>
            <tbody>
            <?php
            while ($data = $link_sale_Data->fetch()) :
                if (convert::to_bool($data->is_read)) {
                    $icon = '<i class="splashy-mail_light_stuffed"></i>';
                    $msg = str::summarize($data->post_title, 120);
                } else {
                    $icon = '<i class="splashy-mail_light_new_2"></i>';
                    $msg = '<b>' . str::summarize($data->post_title, 120) . '</b>';
                }
                ?>
                <tr>
                    <td class=""> <?= $icon ?></td>
                    <td>
                        <a href="javascript:void(0);" class="post-title" data-id='<?= $data->post_id ?>'><?= $msg ?></a>
                    </td>
                    <td><?= $data->username ?></td>
                    <td style="font:9px arial;"> <?= pengu_date::ago($data->insert_time, lang_isrtl(), $agoLanguage) ?>  </td>
                </tr>
            <?php
            endwhile;
            ?>
            </tbody>
        </table>
        <a href="<?= url::router('adminlinksale'); ?>" class="pull-right btn btn-mini"><?= L::dashboard_more; ?></a>
    </div>
    <!--Tab2-->
    <div class="tab-pane" id="tab_br2">
        <table class="table table-condensed">
            <thead>
            <tr>
                <th class="sorting_disabled" role="columnheader" rowspan="1" colspan="1" aria-label=""
                    style="width: 16px;"><i class="splashy-mail_light"></i></th>
                <th><?= L::global_subject; ?></th>
                <th style="width: 60px"><?= L::global_sender; ?></th>
                <th style="width: 60px"><?= L::global_time; ?></th>
            </tr>
            </thead>
            <tbody>
            <?php
            while ($data = $arcades_for_sale_Data->fetch()) :
                if (convert::to_bool($data->is_read)) {
                    $icon = '<i class="splashy-mail_light_stuffed"></i>';
                    $msg = str::summarize($data->post_title, 120);
                } else {
                    $icon = '<i class="splashy-mail_light_new_2"></i>';
                    $msg = '<b>' . str::summarize($data->post_title, 120) . '</b>';
                }
                ?>
                <tr>
                    <td class=""> <?= $icon ?></td>
                    <td>
                        <a href="javascript:void(0);" class="post-title" data-id='<?= $data->post_id ?>'><?= $msg ?></a>
                    </td>
                    <td><?= $data->username ?></td>
                    <td style="font:9px arial;"> <?= pengu_date::ago($data->insert_time, lang_isrtl(), $agoLanguage) ?>  </td>
                </tr>
            <?php
            endwhile;
            ?>
            </tbody>
        </table>
        <a href="<?= url::router('adminsitesale'); ?>" class="pull-right btn btn-mini"><?= L::dashboard_more; ?></a>
    </div>

    <!--Tab3-->
    <div class="tab-pane" id="tab_br3">
        <table class="table table-condensed">
            <thead>
            <tr>
                <th class="sorting_disabled" role="columnheader" rowspan="1" colspan="1" aria-label=""
                    style="width: 16px;"><i class="splashy-mail_light"></i></th>
                <th><?= L::global_subject; ?></th>
                <th style="width: 60px"><?= L::global_sender; ?></th>
                <th style="width: 60px"><?= L::global_time; ?></th>
            </tr>
            </thead>
            <tbody>
            <?php
            while ($data = $domain_for_sale_Data->fetch()) :
                if (convert::to_bool($data->is_read)) {
                    $icon = '<i class="splashy-mail_light_stuffed"></i>';
                    $msg = str::summarize($data->post_title, 120);
                } else {
                    $icon = '<i class="splashy-mail_light_new_2"></i>';
                    $msg = '<b>' . str::summarize($data->post_title, 120) . '</b>';
                }
                ?>
                <tr>
                    <td class=""> <?= $icon ?></td>
                    <td>
                        <a href="javascript:void(0);" class="post-title" data-id='<?= $data->post_id ?>'><?= $msg ?></a>
                    </td>
                    <td><?= $data->username ?></td>
                    <td style="font:9px arial;"> <?= pengu_date::ago($data->insert_time, lang_isrtl(), $agoLanguage) ?>  </td>
                </tr>
            <?php endwhile;
            ?>
            </tbody>
        </table>
        <a href="<?= url::router('admindomainsale'); ?>" class="pull-right btn btn-mini"><?= L::dashboard_more; ?></a>
    </div>

    <!--Tab4-->
    <div class="tab-pane" id="tab_br4">
        <table class="table table-condensed">
            <thead>
            <tr>
                <th class="sorting_disabled" role="columnheader" rowspan="1" colspan="1" aria-label=""
                    style="width: 16px;"><i class="splashy-mail_light"></i></th>
                <th><?= L::global_subject; ?></th>
                <th style="width: 60px"><?= L::global_sender; ?></th>
                <th style="width: 60px"><?= L::global_time; ?></th>
            </tr>
            </thead>
            <tbody>
            <?php
            while ($data = $game_sponsership_Data->fetch()) :
                if (convert::to_bool($data->is_read)) {
                    $icon = '<i class="splashy-mail_light_stuffed"></i>';
                    $msg = str::summarize($data->post_title, 120);
                } else {
                    $icon = '<i class="splashy-mail_light_new_2"></i>';
                    $msg = '<b>' . str::summarize($data->post_title, 120) . '</b>';
                }
                ?>
                <tr>
                    <td class=""> <?= $icon ?></td>
                    <td>
                        <a href="javascript:void(0);" class="post-title" data-id='<?= $data->post_id ?>'><?= $msg ?></a>
                    </td>
                    <td><?= $data->username ?></td>
                    <td style="font:9px arial;"> <?= pengu_date::ago($data->insert_time, lang_isrtl(), $agoLanguage) ?>  </td>
                </tr>
            <?php endwhile;
            ?>
            </tbody>
        </table>
        <a href="<?= url::router('admingamesponsorship'); ?>"
           class="pull-right btn btn-mini"><?= L::dashboard_more; ?></a>
    </div>

    <!--Tab5-->
    <div class="tab-pane" id="tab_br5">
        <table class="table table-condensed">
            <thead>
            <tr>
                <th class="sorting_disabled" role="columnheader" rowspan="1" colspan="1" aria-label=""
                    style="width: 16px;"><i class="splashy-mail_light"></i></th>
                <th><?= L::global_subject; ?></th>
                <th style="width: 60px"><?= L::global_sender; ?></th>
                <th style="width: 60px"><?= L::global_time; ?></th>
            </tr>
            </thead>
            <tbody>
            <?php
            while ($data = $link_exchanges_Data->fetch()) :
                if (convert::to_bool($data->is_read)) {
                    $icon = '<i class="splashy-mail_light_stuffed"></i>';
                    $msg = str::summarize($data->post_title, 120);
                } else {
                    $icon = '<i class="splashy-mail_light_new_2"></i>';
                    $msg = '<b>' . str::summarize($data->post_title, 120) . '</b>';
                }
                ?>
                <tr>
                    <td class=""> <?= $icon ?></td>
                    <td>
                        <a href="javascript:void(0);" class="post-title" data-id='<?= $data->post_id ?>'><?= $msg ?></a>
                    </td>
                    <td><?= $data->username ?></td>
                    <td style="font:9px arial;"> <?= pengu_date::ago($data->insert_time, lang_isrtl(), $agoLanguage) ?>  </td>
                </tr>
            <?php endwhile;
            ?>
            </tbody>
        </table>
        <a href="<?= url::router('adminlinkexchangerequests'); ?>"
           class="pull-right btn btn-mini"><?= L::dashboard_more; ?></a>

    </div>
    <!--Tab6-->
    <div class="tab-pane" id="tab_br6">
        <table class="table table-condensed">
            <thead>
            <tr>
                <th class="sorting_disabled" role="columnheader" rowspan="1" colspan="1" aria-label=""
                    style="width: 16px;"><i class="splashy-mail_light"></i></th>
                <th><?= L::global_subject; ?></th>
                <th style="width: 60px"><?= L::global_sender; ?></th>
                <th style="width: 60px"><?= L::global_time; ?></th>
            </tr>
            </thead>
            <tbody>
            <?php
            while ($data = $requests_Data->fetch()) :
                if (convert::to_bool($data->is_read)) {
                    $icon = '<i class="splashy-mail_light_stuffed"></i>';
                    $msg = str::summarize($data->post_title, 120);
                } else {
                    $icon = '<i class="splashy-mail_light_new_2"></i>';
                    $msg = '<b>' . str::summarize($data->post_title, 120) . '</b>';
                }
                ?>
                <tr>
                    <td class=""> <?= $icon ?></td>
                    <td>
                        <a href="javascript:void(0);" class="post-title" data-id='<?= $data->post_id ?>'><?= $msg ?></a>
                    </td>
                    <td><?= $data->username ?></td>
                    <td style="font:9px arial;"> <?= pengu_date::ago($data->insert_time, lang_isrtl(), $agoLanguage) ?>  </td>
                </tr>
            <?php endwhile;
            ?>
            </tbody>
        </table>
        <a href="<?= url::router('adminarcadediscussions'); ?>"
           class="pull-right btn btn-mini"><?= L::dashboard_more; ?></a>
    </div>
    </div>
    </div>

    </div>
    </div>
<?php endif; ?>


<div class="row-fluid">

    <div class="span6">
        <div class="heading clearfix">
            <h3 class="pull-left"><?= L::dashboard_new_arcadebooster_themes; ?></h3>
        </div>
        <div class="tabbable">
            <div style="width: 100%;">
                <?php
                if (isset($limittheme[0])):
                    ?>
                    <div style="width: 47%;" class="pull-left">

                        <a class="thumbnail pull-left ext_disabled" title="Image 12"
                           href="<?= master_url . "/shop/themeinfoproduct.html?id=" . base64::encode($limittheme[0]['id']) ?>"
                           target="_blank">
                            <img style="width:100%;float: left" src="<?= @$limittheme[0]['thumb'] ?>" alt="">
                        </a>

                        <div class="pull-left" style="padding-top: 10px;width:100%">
                            <span class="label label-inverse pull-left"><?= $limittheme[0]['name'] ?></span>
                            <span
                                class="label label-success pull-right"><?= ($limittheme[0]['price'] > 0) ? '$' . $limittheme[0]['price'] : L::forms_free ?></span>
                        </div>
                    </div>
                <?php endif; ?>
                <?php if (isset($limittheme[1])) : ?>
                    <div style="width:47%;" class="pull-right">
                        <a class="thumbnail pull-left ext_disabled" title="Image 12"
                           href="<?= master_url . "/shop/themeinfoproduct.html?id=" . base64::encode($limittheme[1]['id']) ?>"
                           target="_blank">
                            <img style="width:100%;float: left" src="<?= @$limittheme[1]['thumb'] ?>" alt="">
                        </a>

                        <div class="pull-left" style="padding-top: 10px;width:100%">
                            <span class="label label-inverse pull-left"><?= $limittheme[1]['name'] ?></span>
                            <span
                                class="label label-success pull-right"><?= ($limittheme[1]['price'] > 0) ? '$' . $limittheme[1]['price'] : L::forms_free ?></span>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="span6">
        <div class="heading clearfix">
            <h3 class="pull-left"><?= L::dashboard_latest_arcadebooster_news; ?></h3>
        </div>
        <div class="tabbable tabbable-bordered">
            <?php
            if (!empty($limitnews))
                foreach ($limitnews as $news):
                    ?>
                    <div class="news-block external_link" data-id="<?= $news['id'] ?>">
                        <div><b style="color:#08C"><?= $news['title'] ?></b></div>
                        <div>
                            <?= str::summarize($news['description'], 100); ?>
                            <span class="help-block"
                                  style="color:#ccc;font:10px arial;padding-top: 6px;"><?= pengu_date::ago($news['time'], lang_isrtl(), $agoLanguage) ?></span>
                        </div>
                    </div>
                    <div class="formSep"></div>
                <?php endforeach; ?>

        </div>
    </div>

</div>

</div>
</div>

<?php
get_sidebar();
get_footer('_script');
?>
<script type="text/javascript">
    jQuery(document).ready(function () {
        if ($('#last30feeds').length)
            $('#last30feeds').slimScroll({
                allowPageScroll: true
            }).parent().addClass('well').css('background-color','#4B4F54');
    });
</script>
<?php
get_footer();
?>
