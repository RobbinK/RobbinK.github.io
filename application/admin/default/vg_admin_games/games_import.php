<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: games_import.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:56
##########################################################
 */

### call header
abs_admin_inc(l_basic);
abs_admin_inc(l_datatable);
abs_admin_inc(l_sticky);
abs_admin_inc(l_smoke);
abs_admin_inc(l_colorbox);
get_header();
#**************
?>
    <!-- main content -->
    <div id="contentwrapper">
        <div class="main_content">
            <!-- Navigation Menu -->
            <nav>
                <div id="jCrumbs" class="breadCrumb module">
                    <ul>
                        <li>
                            <a href="<?= url::router('admindashboard'); ?>"><i class="icon-home"></i></a>
                        </li>
                        <li>
                            <?= L::sidebar_imp_game_pck; ?>
                        </li>
                    </ul>
                </div>
            </nav>
            <!-- /Navigation Menu -->
            <!--Attention-->
            <div class="alert alert-success">
                <?php $feed_thumb_size = _get_theme_setting('feed_thumb_size') ?>
                <?= L::alert_import_size_warning; ?>  <?= $feed_thumb_size ?> <br/>
                <?= L::alert_change_thumb_size; ?> <a
                    href="<?= url::router('admin-feedsetting') ?>"><?= L::forms_feed_setting; ?> </a>
            </div>
            <!--end Attention-->

            <div class="well well-small form-inline">
                <label class="checkbox">
                    <input type="checkbox" value="1" id="shuffle_chk" checked="checked">
                    <?= L::forms_shuffle_games ?>.
                </label>
                <input type="text" maxlength="2" style="width: 30px;" id="shuffle_days"
                       value="6"/> <?= L::forms_days_ago ?>

            </div>
            <!-- Game List -->
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th><?= L::global_title; ?></th>
                    <th style="width:80px"><?= L::forms_number_of_games; ?></th>
                    <th style="width:80px"><?= L::global_the_date; ?></th>
                    <th style="width:300px"><?= L::forms_available_categories; ?></th>
                    <th style="width:40px"><?= L::global_type; ?></th>
                    <th style="width:100px"><?= L::global_action; ?></th>
                </tr>
                </thead>
                <tbody>
                <?php
                if (!empty($packlist)):
                    $s = new pengu_setting;
                    $s->setSettingName('packgames');

                    foreach ($packlist as $rec) :
                        ?>
                        <tr>
                            <td><img src="<?= $rec['icon'] ?>" width="60px"/> <?= $rec['title'] ?></td>
                            <td><?= number_format($rec['number_of_games']) ?></td>
                            <td><?= $rec['date'] ?></td>
                            <td><?= str::summarize($rec['cats'], 50) ?></td>
                            <td><?php
                                if ($rec['type'] == 'mobile')
                                    echo "<img src='img/smart-phone.png' width='40'/>";
                                else
                                    echo "<img src='img/desktop.png' width='40'/>";
                                ?></td>
                            <td style="text-align: center">
                                <i class="icon-eye-open showpack" title="<?= addslashes(L::forms_show); ?>"
                                   data-url="<?= master_url . '/shop/showpackgame.html?id=' . $rec['id'] . '&size=150x150&type=' . $rec['type'] ?>"></i><br>
                                <?php if ($s->get('installed' . $rec['id']) == true) : ?>
                                    <i class="splashy-check"></i> <?= L::global_installed; ?>
                                    <button class="btn btn-mini install" data-id="<?= $rec['id'] ?>"
                                            data-type="<?= $rec['type'] ?>">Re-install
                                    </button>
                                <?php else: ?>
                                    <button class="btn btn-mini btn-success install" data-id="<?= $rec['id'] ?>"
                                            data-type="<?= $rec['type'] ?>"><i
                                            class="splashy-download"></i><?= L::forms_install; ?></button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php
                    endforeach;
                endif;
                ?>
                </tbody>
            </table>
            <!-- /Game List -->


        </div>
    </div>


<?php
get_sidebar();
get_footer('_script');
?>

    <script type="text/javascript">

        var loading_config = {
            'indicatorZIndex': 990,
            'overlayZIndex': 990
        };

        $(function () {
            $('.install').click(function () {
                $('.table').showLoading(loading_config);
                st1 = $.sticky(window.loadingIMG+' &nbsp;<?= addslashes(L::alert_installing_game);?>', {
                    autoclose: false,
                    position: "top-right",
                    type: "st-info",
                    speed: "fast"
                });
                var eid = $(this).data('id');
                var etype = $(this).data('type');
                var $this = $(this);
                data = {id: eid, type: etype};
                if ($('#shuffle_chk').is(':checked'))
                    $.extend(data || {}, {'shuffledays': $('#shuffle_days').val()});
                $.ajax({
                    type: 'POST',
                    url: '<?= url::itself()->url_nonqry() ?>?install',
                    'data': data,
                    success: function (result) {
                        $.stickyhide(st1.id);
                        $('.table').hideLoading();
                        if (result == '1') {
                            $($this).closest('td').html('<i class="splashy-check"></i> <?= L::global_installed; ?>');
                            abs_cache.clean_mysql();
                        }
                        else
                            $.sticky('<?= addslashes(L::alert_no_response);?>', {
                                autoclose: 5000,
                                position: "top-right",
                                type: "st-error",
                                speed: "fast"
                            });

                    }
                });
            });

            $('.showpack').click(function () {
                $.colorbox({
                    iframe: true,
                    innerWidth: '80%',
                    innerHeight: '80%',
                    href: $(this).data('url'),
                    opacity: '0.2',
                    loop: false,
                    fixed: true
                });
            });
        });
    </script>
<?php
get_footer();
?>