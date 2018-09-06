<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: header.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:55
##########################################################
 */
 $sitename = setting::get_data('site_name', 'val'); ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" /> 
        <title><?= !empty($sitename) ? $sitename : 'ArcadeBooster' ?> - <?= L::header_administration_area; ?></title>
        <?php
        if (isLocalServer()):
            css::load(template_url() . '/css/fonts/pt-sanse/font.css', array(CSS_FORCELOAD => true));
        else:
            ?>
            <link rel = "stylesheet" href = "http://fonts.googleapis.com/css?family=PT+Sans">
        <?php endif; ?>
        <base href="<?= template_url() ?>/"/>
        <link href="img/favicon.png" rel="shortcut icon" type="image/x-icon" />
        <?= abs_admin_place_css(); ?> 
        <script type="text/javascript">
            //* hide all elements & show preloader
            document.documentElement.className += 'js';
            window.master_url = "<?= master_url ?>";
            window.myself_url = "<?= url::itself() ?>";
            window.myself_url_nonqry = "<?= url::itself()->url_nonqry() ?>";
            window.editgame_url = "<?= url::router('editgame') ?>";
            window.openfeed_url = "<?= url::router('adminopenfeed') ?>";
            window.static_url = "<?= static_url() ?>";
            window.template_url = "<?= template_url() ?>";
            window.get_auto_game_dimension = <?= convert::to_bool(setting::get_data('getdimension_after_uploading', 'val')) ? 'true' : 'false' ?>;
            window.generatingStats = <?= (generatingStats()) ? 'true' : 'false' ?>;
            window.loadingIMG="<img src='"+window.static_url+'/images/loading/loading-9.gif'+"' />";

            /* languages */
            window.alert_cache_removed = '<?= addslashes(L::alert_cache_removed); ?>';
            window.alert_deleting_cache = '<?= addslashes(L::alert_deleting_cache); ?>';
            window.alert_cache_removed = '<?= addslashes(L::alert_cache_removed); ?>';

            var dataTablesLanguages = {
                "oAria": {
                    "sSortAscending": ": activate to sort column ascending",
                    "sSortDescending": ": activate to sort column descending"
                },
                "oPaginate": {
                    "sFirst": "<?= addslashes(L::global_first); ?>",
                    "sLast": "<?= addslashes(L::global_last); ?>",
                    "sNext": "<?= addslashes(L::global_next); ?>",
                    "sPrevious": "<?= addslashes(L::global_prevoius); ?>"
                },
                "sEmptyTable": "<?= addslashes(L::global_no_data_availanle); ?>",
                "sInfo": "<?= addslashes(L::global_showing); ?> _START_ <?= L::global_pre_to; ?> _END_ <?= L::global_pre_of; ?> _TOTAL_ <?= L::global_records; ?>",
                        "sInfoEmpty": "<?= addslashes(L::global_showing_no_entries); ?>",
                        "sInfoFiltered": "(<?= L::global_filtered_from; ?> _MAX_ <?= L::global_total_entries; ?>)",
                        "sInfoPostFix": "",
                        "sInfoThousands": ",",
                        "sLengthMenu": "<?= addslashes(L::forms_show); ?> _MENU_ <?= L::global_records; ?>",
                                "sLoadingRecords": "<?= addslashes(L::global_loading); ?>",
                                "sProcessing": "<?= addslashes(L::global_processing); ?>",
                                "sSearch": "<?= addslashes(L::global_search); ?>",
                                "sUrl": "",
                                "sZeroRecords": "<?= addslashes(L::global_no_matching_records); ?>"
                            };
                            window.smoke_ok_btn = '<?= addslashes(L::global_state_yes); ?>';
                            window.smoke_cancel_btn = '<?= addslashes(L::global_cancel); ?>';
        </script>
    </head>
    <body>
        <div id="loading_layer" style="display:none;"><img src="<?= template_url() ?>/img/ajax_loader.gif" alt="" /></div>
        <div class="style_switcher">
            <div class="sepH_c">
                <div class="clearfix">
                    <a href="<?= url::itself()->fulluri(array('delallcaches' => 1)) ?>" title="Delete all caches" class="btn1 ext_disabled"><i class='icon-trash icon-white'></i> Delete All Caches</a>
                </div>
            </div>
            <div class="sepH_c">
                <p><?= L::dashboard_themes; ?> :</p>
                <div class="clearfix">
                    <a href="<?= url::itself()->fulluri(array('changetheme' => 'blue')) ?>" class="style_item jQclr blue_theme style_active ext_disabled" title="blue">blue</a>
                    <a href="<?= url::itself()->fulluri(array('changetheme' => 'dark')) ?>" class="style_item jQclr dark_theme ext_disabled" title="dark">dark</a>
                    <a href="<?= url::itself()->fulluri(array('changetheme' => 'green')) ?>" class="style_item jQclr green_theme ext_disabled" title="green">green</a>
                    <a href="<?= url::itself()->fulluri(array('changetheme' => 'brown')) ?>" class="style_item jQclr brown_theme ext_disabled" title="brown">brown</a> 
                    <a href="<?= url::itself()->fulluri(array('changetheme' => 'tamarillo')) ?>" class="style_item jQclr tamarillo_theme ext_disabled" title="tamarillo">tamarillo</a>
                </div>
                </div>
        </div>

        <div id="maincontainer" class="clearfix">
            <!-- header -->
            <header>
                <div class="navbar navbar-fixed-top">
                    <div class="navbar-inner">
                        <div class="container-fluid">
                            <a class="brand" href="<?= url::router('admindashboard') ?>">
                                <img style="margin-top:10px" src="img/ab_adminlogo.png">
                                <span style="font: 11px arial bold italic;color: #EBEBEB;">(<?= sys_ver ?>)</span>
                            </a>
                            <ul class="nav user_menu pull-right">

                                <li class="divider-vertical hidden-phone hidden-tablet"></li>
                                <li class="dropdown">
                                    <a href="javascript:void(0);" class="dropdown-toggle nav_condensed" data-toggle="dropdown"><i class="flag-<?= lang_country_code() ?>"></i> <b class="caret"></b></a>
                                    <ul class="dropdown-menu">
                                        <li><a href="<?= url::itself()->fulluri(array('lang' => agent::lang_to_code('english'))) ?>" class="ext_disabled"><i class="flag-gb"></i> English </a></li>
                                        <li><a href="<?= url::itself()->fulluri(array('lang' => agent::lang_to_code('persian'))) ?>" class="ext_disabled" style="font:13px tahoma;"><i class="flag-ir"></i> فارسی </a></li> 
                                        <li><a href="<?= url::itself()->fulluri(array('lang' => agent::lang_to_code('arabic'))) ?>" class="ext_disabled" style="font:13px tahoma;"><i class="flag-sa"></i> العربیه </a></li> 
                                        <li><a href="<?= url::itself()->fulluri(array('lang' => agent::lang_to_code('portuguese'))) ?>" class="ext_disabled" style="font:13px tahoma;"><i class="flag-br"></i> Português</a></li> 
                                        <li><a href="<?= url::itself()->fulluri(array('lang' => agent::lang_to_code('turkish'))) ?>" class="ext_disabled" style="font:13px tahoma;"><i class="flag-tr"></i> Türkçe</a></li> 
                                        <li><a href="<?= url::itself()->fulluri(array('lang' => agent::lang_to_code('russian'))) ?>" class="ext_disabled" style="font:13px tahoma;"><i class="flag-ru"></i> Russian</a></li> 
                                        <li><a href="<?= url::itself()->fulluri(array('lang' => agent::lang_to_code('romanian'))) ?>" class="ext_disabled" style="font:13px tahoma;"><i class="flag-ro"></i> Romanian</a></li> 
                                        <!--
                                        <li><a href="<?= url::itself()->fulluri(array('lang' => agent::lang_to_code('urdu'))) ?>" class="ext_disabled" style="font:13px tahoma;"><i class="flag-pk"></i> اردو </a></li> 
                                        <li><a href="<?= url::itself()->fulluri(array('lang' => agent::lang_to_code('french'))) ?>" class="ext_disabled" style="font:13px tahoma;"><i class="flag-fr"></i> French</a></li>
                                        <li><a href="<?= url::itself()->fulluri(array('lang' => agent::lang_to_code('hindi'))) ?>" class="ext_disabled" style="font:13px tahoma;"><i class="flag-in"></i> Hindi</a></li> 
                                        <li><a href="<?= url::itself()->fulluri(array('lang' => agent::lang_to_code('spanish'))) ?>" class="ext_disabled" style="font:13px tahoma;"><i class="flag-es"></i> Español</a></li> 
                                        <li><a href="<?= url::itself()->fulluri(array('lang' => agent::lang_to_code('german'))) ?>" class="ext_disabled" style="font:13px tahoma;"><i class="flag-de"></i> Deutsch</a></li> 
                                        <li><a href="<?= url::itself()->fulluri(array('lang' => agent::lang_to_code('italian'))) ?>" class="ext_disabled" style="font:13px tahoma;"><i class="flag-it"></i> Italian</a></li> 
                                        <li><a href="<?= url::itself()->fulluri(array('lang' => agent::lang_to_code('chinese'))) ?>" class="ext_disabled" style="font:13px tahoma;"><i class="flag-ch"></i> Chinese </a></li> 
                                        -->
                                    </ul>
                                </li>
                                <li class="divider-vertical hidden-phone hidden-tablet"></li>
                                <li class="dropdown">
                                    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown">
                                        <?php
                                        $avatar = Admin::data('avatar');
                                        if (!empty($avatar) && file_exists(ab_upload_dir . '/' . $avatar)) {
                                            echo pengu_image::resize(ab_upload_dir . '/' . $avatar, 50)
                                                    ->ShowIMGTag('class="user_avatar"');
                                        } else
                                            echo ' <img src="' . template_url() . '/img/user_avatar.png" alt="" class="user_avatar" />';
                                        ?>

                                        <?= Admin::data('name') ?> 
                                        <b class="caret"></b>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li><a href="<?= url::router('admin-profile') ?>"><?= L::header_my_profile; ?></a></li> 
                                        <li class="divider"></li>
                                        <li><a href="<?= url::router('adminlogout') ?>"><?= L::header_logout; ?></a></li>
                                    </ul>
                                </li>
                            </ul>
                            <ul class="nav" id="mobile-nav">
                                <?php if (isset($markateplace_total)) : ?>
                                    <li class="dropdown">
                                        <a data-toggle="dropdown" class="dropdown-toggle" href="javascript:void(0);"><i class="icon-bullhorn icon-white"></i> <?= L::header_marketplace; ?>  <?= (@$markateplace_total ? "<span style='color:#FFF' id=\"marketplace_total\">({$markateplace_total})</span>" : null) ?>  <b class="caret"></b></a>
                                        <ul class="dropdown-menu">
                                            <li><a href="<?= url::router('adminlinksale'); ?>"><?= L::header_link_sales; ?>
                                                    <?= (@$link_sale ? "<span class=\"label label-important\" id=\"post_2\">{$link_sale}</span>" : null) ?> 
                                                </a></li> 
                                            <li><a href="<?= url::router('adminsitesale'); ?>"><?= L::header_arcades_for_sale; ?>
                                                    <?= (@$arcades_for_sale ? "<span class=\"label label-important\" id=\"post_2\">{$arcades_for_sale}</span>" : null) ?> 
                                                </a></li>                                        
                                            <li><a href="<?= url::router('admindomainsale'); ?>"><?= L::header_domains_for_sale; ?>
                                                    <?= (@$domain_for_sale ? "<span class=\"label label-important\" id=\"post_3\">{$domain_for_sale}</span>" : null) ?> 
                                                </a></li>
                                            <li><a href="<?= url::router('adminlinkexchangerequests'); ?>"><?= L::header_link_exchange_requests; ?>
                                                    <?= (@$link_exchanges ? "<span class=\"label label-important\" id=\"post_5\">{$link_exchanges}</span>" : null) ?> 
                                                </a></li>
                                            <li><a href="<?= url::router('admingamesponsorship'); ?>"><?= L::header_game_sponsorship; ?>
                                                    <?= (@$game_sponsership ? "<span class=\"label label-important\" id=\"post_4\">{$game_sponsership}</span>" : null) ?> 
                                                </a></li>
                                            <li><a href="<?= url::router('adminarcadediscussions'); ?>"><?= L::header_arcade_discussions; ?>
                                                    <?= (@$requests ? "<span class=\"label label-important\" id=\"post_6\">{$requests}</span>" : null) ?> 
                                                </a></li>
                                        </ul>
                                    </li> 
                                <?php endif; ?>
                                <li>
                                    <a href="<?= url::router('admincomments') ?>"><i class="icon-envelope icon-white"></i> <?= L::header_messages; ?>  <?= ($comments_unread ? "<span style='color:#FFF' id=\"comments_unread\">({$comments_unread})</span>" : null) ?> </a>
                                </li>
                                <li class="dropdown">
                                    <?php
                                    $qs['website'] = 'http://' . lib::get_domain(HOST_URL);
                                    if (defined('ab_user_legal_name'))
                                        $qs['name'] = ab_user_legal_name;
                                    if (defined('ab_user_email'))
                                        $qs['email'] = ab_user_email;
                                    $s = '&qs=' . base64::encode($qs);
                                    ?>
                                    <a data-toggle="dropdown" class="dropdown-toggle" href="javascript:void(0);"><i class="icon-user icon-white"></i> <?= L::header_support; ?>  <b class="caret"></b></a>
                                    <ul class="dropdown-menu">
                                        <li><a href="http://www.arcadebooster.com/contactus.html?act=cmVwb3J0IGEgYnVn<?= $s ?>" class="ext_disabled" target="_blank"><?= L::header_report_a_bug; ?></a></li> 
                                        <li><a href="http://www.arcadebooster.com/contactus.html?act=c3VnZ2VzdCBhIG5ldyBmZWF0dXJl<?= $s ?>" class="ext_disabled" target="_blank"><?= L::header_suggest_a_new_feature; ?></a></li> 
                                        <li><a href="http://www.arcadebooster.com/contactus.html?act=Z2VuZXJhbCBxdWVzdGlvbg!!<?= $s ?>" class="ext_disabled" target="_blank"><?= L::header_general_question; ?></a></li> 
                                    </ul>
                                </li>
                                <li>
                                    <a href="http://www.arcadebooster.com/community" class="ext_disabled"><i class="icon-globe icon-white"></i> Community </a>
                                </li>
                                <?php if (file_exists(root_path() . '/blog/admin/')) : ?>
                                    <li>
                                        <a href="<?= root_url() ?>/blog/admin/" class="ext_disabled"><i class="icon-pencil icon-white"></i> Blog </a>                                     
                                    </li>
                                <?php endif; ?>
                            </ul> 
                        </div>
                    </div>
                </div> 
            </header>